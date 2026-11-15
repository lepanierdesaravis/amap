<x-layouts.app title="Mon compte">
    <x-member-nav />
    <div class="mx-auto grid max-w-5xl gap-8 px-4 py-8 lg:grid-cols-[1fr_300px]">
        <form method="POST" action="{{ route('espace.compte.update') }}" class="card space-y-4">
            @csrf @method('PUT')
            <h1 class="text-2xl font-extrabold">Mes informations</h1>
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="label" for="first_name">Prénom</label><input class="input" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required></div>
                <div><label class="label" for="last_name">Nom</label><input class="input" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required></div>
                <div><label class="label" for="phone">Téléphone</label><input class="input" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"></div>
                <div><label class="label" for="phone_secondary">Autre téléphone</label><input class="input" id="phone_secondary" name="phone_secondary" value="{{ old('phone_secondary', $user->phone_secondary) }}"></div>
            </div>
            <div><label class="label" for="address">Adresse</label><input class="input" id="address" name="address" value="{{ old('address', $user->address) }}"></div>
            <div class="grid gap-4 sm:grid-cols-[140px_1fr]">
                <div><label class="label" for="postal_code">Code postal</label><input class="input" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}"></div>
                <div><label class="label" for="city">Commune</label><input class="input" id="city" name="city" value="{{ old('city', $user->city) }}"></div>
            </div>
            <label class="flex items-start gap-2 text-sm"><input type="checkbox" name="share_phone" value="1" class="mt-1 rounded text-vert" @checked($user->share_phone)> J'accepte que mon téléphone soit visible des autres adhérents, pour s'organiser lors des permanences.</label>
            <p class="text-sm text-doux">E-mail de connexion : <strong>{{ $user->email }}</strong></p>
            <button class="btn-vert">Enregistrer</button>
        </form>
        <aside class="card h-fit">
            <h2 class="text-lg font-bold">Mes adhésions</h2>
            <ul class="mt-3 space-y-2 text-sm">
                @forelse ($memberships as $m)
                    <li class="flex justify-between"><span>{{ $m->year }}</span>@if ($m->paid_at)<span class="badge bg-vert-clair text-vert-fonce">✓ Réglée</span>@else<span class="badge bg-miel-clair">En attente</span>@endif</li>
                @empty
                    <li class="text-doux">Aucune adhésion enregistrée.</li>
                @endforelse
            </ul>
        </aside>
    </div>
</x-layouts.app>
