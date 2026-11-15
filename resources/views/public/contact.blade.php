<x-layouts.app title="Contact">
    <x-page-header kicker="Une question ?" title="Nous contacter">
        Votre message est transmis directement à la bonne personne selon le sujet choisi.
    </x-page-header>
    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-10 lg:grid-cols-[1fr_340px]">
        <form method="POST" action="{{ route('contact.store') }}" class="card space-y-4" novalidate>
            @csrf
            <div class="hidden" aria-hidden="true"><label>Ne pas remplir <input type="text" name="site_web" tabindex="-1" autocomplete="off"></label></div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="label" for="first_name">Prénom</label><input class="input" id="first_name" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name">@error('first_name')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
                <div><label class="label" for="last_name">Nom</label><input class="input" id="last_name" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name">@error('last_name')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
            </div>
            <div><label class="label" for="email">Adresse e-mail</label><input class="input" type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">@error('email')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="label" for="sender_type">Je suis</label>
                    <select class="input" id="sender_type" name="sender_type" required>
                        @foreach ($senderTypes as $key => $label)<option value="{{ $key }}" @selected(old('sender_type', 'curieux') === $key)>{{ $label }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="subject">Sujet</label>
                    <select class="input" id="subject" name="subject" required>
                        @foreach ($subjects as $key => $label)<option value="{{ $key }}" @selected(old('subject') === $key)>{{ $label }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div><label class="label" for="message">Message</label><textarea class="input" id="message" name="message" rows="6" required>{{ old('message') }}</textarea>@error('message')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
            <button class="btn-primary">Envoyer mon message</button>
        </form>
        <aside class="space-y-4">
            <div class="card">
                <p class="font-bold">🙋 Nous rencontrer</p>
                <p class="mt-1 text-sm text-doux">Lors d'une distribution, sans rendez-vous :</p>
                <ul class="mt-2 space-y-1 text-sm font-bold">
                    @foreach ($nextDistributions as $d)<li>{{ $d->label() }}, {{ $d->timeRange() }}</li>@endforeach
                </ul>
                <p class="mt-2 text-sm">{{ config('amap.distribution.place') }}<br>{{ config('amap.distribution.address') }}</p>
            </div>
            <div class="card text-sm">
                <p class="font-bold">✉️ Adresse postale</p>
                <p class="mt-1">{!! implode('<br>', array_map('e', config('amap.postal_address'))) !!}</p>
            </div>
        </aside>
    </div>
</x-layouts.app>
