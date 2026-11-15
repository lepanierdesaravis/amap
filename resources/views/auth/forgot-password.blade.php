<x-layouts.app title="Mot de passe oublié">
    <div class="mx-auto max-w-md px-4 py-14">
        <h1 class="text-3xl font-extrabold">Mot de passe oublié</h1>
        <p class="mt-1 text-doux">Indiquez votre adresse e-mail : nous vous enverrons un lien pour choisir un nouveau mot de passe.</p>
        <form method="POST" action="{{ route('password.email') }}" class="card mt-6 space-y-4">
            @csrf
            <div><label class="label" for="email">Adresse e-mail</label><input class="input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>@error('email')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
            <button class="btn-vert w-full">Recevoir le lien</button>
        </form>
    </div>
</x-layouts.app>
