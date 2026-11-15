<x-layouts.app title="Créer mon compte">
    <div class="mx-auto max-w-xl px-4 py-14">
        <h1 class="text-3xl font-extrabold">Créer mon compte</h1>
        <p class="mt-1 text-doux">Votre compte vous permettra de souscrire vos contrats, de commander et de vous inscrire aux permanences, une fois votre adhésion validée.</p>
        <form method="POST" action="{{ route('register') }}" class="card mt-6 space-y-4">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="label" for="first_name">Prénom</label><input class="input" id="first_name" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name">@error('first_name')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
                <div><label class="label" for="last_name">Nom</label><input class="input" id="last_name" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name">@error('last_name')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
            </div>
            <div><label class="label" for="email">Adresse e-mail</label><input class="input" type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">@error('email')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
            <div><label class="label" for="phone">Téléphone</label><input class="input" type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel"></div>
            <label class="flex items-start gap-2 text-sm"><input type="checkbox" name="share_phone" value="1" class="mt-1 rounded text-vert" @checked(old('share_phone'))> J'accepte que mon téléphone soit visible des autres adhérents, pour s'organiser lors des permanences.</label>
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="label" for="password">Mot de passe</label><input class="input" type="password" id="password" name="password" required autocomplete="new-password">@error('password')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
                <div><label class="label" for="password_confirmation">Confirmation</label><input class="input" type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"></div>
            </div>
            <label class="flex items-start gap-2 text-sm"><input type="checkbox" name="consent" value="1" class="mt-1 rounded text-vert" required> J'accepte que mes données soient utilisées pour le fonctionnement de l'AMAP (<a href="{{ route('page', 'confidentialite') }}" class="text-vert" target="_blank">politique de confidentialité</a>).</label>
            @error('consent')<p class="text-sm text-tomate">{{ $message }}</p>@enderror
            <button class="btn-primary w-full py-3">Créer mon compte</button>
        </form>
    </div>
</x-layouts.app>
