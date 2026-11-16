<?php

namespace App\Http\Controllers;

use App\Enums\MessageAudience;
use App\Enums\Role;
use App\Mail\MemberMessageMail;
use App\Models\MemberMessage;
use App\Models\Producer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * « Échanges entre adhérents » : écrire à tous les adhérents, au référent d'un producteur
 * ou au bureau, sans que les adresses e-mail soient visibles.
 */
class ExchangeController extends Controller
{
    /** Messages à tous les adhérents par personne et par jour, pour éviter les abus. */
    private const DAILY_LIMIT = 3;

    public function show(Request $request): View
    {
        return view('member.exchanges', [
            'producers' => Producer::published()->whereNotNull('referent_id')->with('referent')->orderBy('name')->get(),
            'messages' => MemberMessage::where('audience', MessageAudience::Members)->with('sender')->latest()->take(20)->get(),
            'canWriteToAll' => $request->user()->canOrder(),
            'audience' => $request->query('a', MessageAudience::Members->value),
            'producerId' => $request->integer('producteur') ?: null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'audience' => ['required', Rule::enum(MessageAudience::class)],
            'producer_id' => ['nullable', 'required_if:audience,'.MessageAudience::Referent->value, Rule::exists('producers', 'id')],
            'subject' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'min:10', 'max:5000'],
        ]);
        $audience = MessageAudience::from($data['audience']);

        if ($audience === MessageAudience::Members) {
            if (! $user->canOrder()) {
                throw ValidationException::withMessages(['audience' => 'Seuls les adhérents à jour de cotisation peuvent écrire à tous les adhérents.']);
            }
            $sentToday = MemberMessage::where('sender_id', $user->id)->where('audience', $audience)->where('created_at', '>=', now()->subDay())->count();
            if ($sentToday >= self::DAILY_LIMIT) {
                throw ValidationException::withMessages(['audience' => 'Vous avez déjà envoyé '.self::DAILY_LIMIT.' messages à tous les adhérents aujourd\'hui.']);
            }
        }

        $producer = $audience === MessageAudience::Referent ? Producer::find($data['producer_id']) : null;
        $recipients = $this->recipients($audience, $producer, $user);

        $message = MemberMessage::create([
            'sender_id' => $user->id,
            'audience' => $audience,
            'producer_id' => $producer?->id,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'recipients_count' => $recipients->count(),
        ]);
        foreach ($recipients as $recipient) {
            Mail::to($recipient)->queue(new MemberMessageMail($message));
        }

        return redirect()->route('espace.echanges')->with('status', match ($audience) {
            MessageAudience::Members => "Votre message est envoyé à {$recipients->count()} adhérents. Les réponses arriveront dans votre boîte e-mail.",
            MessageAudience::Referent => "Votre message est transmis au référent de {$producer->name}.",
            MessageAudience::Board => 'Votre message est transmis au bureau.',
        });
    }

    /** @return Collection<int, User> */
    private function recipients(MessageAudience $audience, ?Producer $producer, User $sender): Collection
    {
        $board = fn () => User::role(Role::Admin->value)->get();

        return match ($audience) {
            MessageAudience::Members => User::membersInGoodStanding()->where('accepts_member_messages', true)->whereKeyNot($sender->id)->get(),
            // Sans référent, le message va au bureau.
            MessageAudience::Referent => $producer?->referent ? collect([$producer->referent]) : $board(),
            MessageAudience::Board => $board(),
        };
    }
}
