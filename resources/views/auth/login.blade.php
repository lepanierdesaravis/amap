<x-layouts.app title="Connexion">
    <div class="mx-auto max-w-md px-4 py-14">
        <h1 class="text-3xl font-extrabold">Se connecter</h1>
        <p class="mt-1 text-doux">Adhérents, producteurs et référents : un seul accès pour tous.</p>
        <form method="POST" action="{{ route('login') }}" class="card mt-6 space-y-4">
            @csrf
            <div><label class="label" for="email">Adresse e-mail</label><input class="input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">@error('email')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
            <div><label class="label" for="password">Mot de passe</label><input class="input" type="password" id="password" name="password" required autocomplete="current-password"></div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" class="rounded text-vert"> Rester connecté sur cet appareil</label>
            <button class="btn-vert w-full py-3">Se connecter</button>
            <p class="text-center text-sm"><a href="{{ route('password.request') }}" class="text-vert">Mot de passe oublié ?</a></p>
        </form>
        <p class="mt-6 text-center text-sm">Pas encore de compte ? <a href="{{ route('register') }}" class="font-bold text-vert">Créer mon compte</a></p>

        @if (app()->isLocal())
            <div class="mt-8 rounded-2xl bg-miel-clair p-4 text-sm ring-1 ring-miel/40">
                <p class="font-bold">Prototype : comptes de démonstration</p>
                <p class="text-doux">Mot de passe pour tous : <code class="font-bold">panier2027</code></p>
                <div class="mt-3 grid gap-2">
                    @foreach ([
                        ['claire.martin@exemple.fr', '🧑‍🌾 Claire, adhérente', route('espace.index')],
                        ['zoe.nouvelle@exemple.fr', '🆕 Zoé, adhésion non réglée', route('espace.index')],
                        ['jean.perrier@exemple.fr', '🤝 Jean, référent', url('/gestion')],
                        ['corti@exemple.fr', '🚜 Le Corti des Aravis, producteur', route('producteur.index')],
                        ['admin@exemple.fr', '🛠️ Anne, administratrice', url('/gestion')],
                    ] as [$email, $label, $to])
                        <a href="{{ route('demo.login', ['email' => $email, 'vers' => $to]) }}" class="btn-ghost justify-start py-2 text-sm">{{ $label }} <span class="ml-auto text-xs font-normal text-doux">{{ $email }}</span></a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
