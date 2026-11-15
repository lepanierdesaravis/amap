<x-layouts.app title="Nouveau mot de passe">
    <div class="mx-auto max-w-md px-4 py-14">
        <h1 class="text-3xl font-extrabold">Nouveau mot de passe</h1>
        <form method="POST" action="{{ route('password.update') }}" class="card mt-6 space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div><label class="label" for="email">Adresse e-mail</label><input class="input" type="email" id="email" name="email" value="{{ old('email', $email) }}" required>@error('email')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
            <div><label class="label" for="password">Nouveau mot de passe</label><input class="input" type="password" id="password" name="password" required autocomplete="new-password">@error('password')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
            <div><label class="label" for="password_confirmation">Confirmation</label><input class="input" type="password" id="password_confirmation" name="password_confirmation" required></div>
            <button class="btn-vert w-full">Enregistrer</button>
        </form>
    </div>
</x-layouts.app>
