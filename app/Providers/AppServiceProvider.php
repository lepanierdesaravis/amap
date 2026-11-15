<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));

        // L'administrateur a tous les droits ; les autres rôles passent par les policies.
        Gate::before(fn (User $user) => $user->isAdmin() ? true : null);

        ResetPassword::toMailUsing(fn (User $user, string $token) => (new MailMessage)
            ->subject('Choisir un nouveau mot de passe')
            ->greeting("Bonjour {$user->first_name},")
            ->line('Vous avez demandé à changer le mot de passe de votre compte '.config('amap.name').'.')
            ->action('Choisir un nouveau mot de passe', route('password.reset', ['token' => $token, 'email' => $user->email]))
            ->line('Ce lien est valable 60 minutes. Si vous n\'êtes pas à l\'origine de cette demande, ignorez cet e-mail.')
            ->salutation(config('amap.name')));
    }
}
