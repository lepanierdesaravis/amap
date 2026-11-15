<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Distribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('public.contact', [
            'subjects' => collect(config('amap.contact_subjects'))->map(fn ($s) => $s['label']),
            'senderTypes' => config('amap.contact_sender_types'),
            'nextDistributions' => Distribution::upcoming()->take(3)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Champ piège invisible : rempli uniquement par les robots.
        if (filled($request->input('site_web'))) {
            return back()->with('status', 'Merci, votre message a bien été envoyé.');
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:160'],
            'sender_type' => ['required', Rule::in(array_keys(config('amap.contact_sender_types')))],
            'subject' => ['required', Rule::in(array_keys(config('amap.contact_subjects')))],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $message = ContactMessage::create($data);
        $recipient = config("amap.contact_subjects.{$data['subject']}.email", config('amap.contact_email'));

        Mail::to($recipient)->send(new ContactMessageReceived($message));
        Mail::to($data['email'])->send(new ContactMessageReceived($message, acknowledgement: true));

        return redirect()->route('contact')->with('status', 'Merci, votre message a bien été envoyé. Nous vous répondrons rapidement.');
    }
}
