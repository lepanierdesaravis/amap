<x-layouts.app title="Échanges entre adhérents">
    <x-member-nav />
    <div class="mx-auto max-w-6xl px-4 py-8">
        <h1 class="text-3xl font-extrabold">Échanges entre adhérents</h1>
        <p class="mt-1 text-doux">Proposer un covoiturage, échanger un panier pendant vos vacances, poser une question à un référent… Les adresses e-mail restent masquées : les réponses arrivent directement dans votre boîte.</p>

        @if (session('status'))
            <p class="mt-6 rounded-2xl bg-vert-clair p-4 font-bold ring-1 ring-vert/30" role="status">✓ {{ session('status') }}</p>
        @endif

        <div class="mt-6 grid gap-8 lg:grid-cols-[1fr_380px]">
            <form method="POST" action="{{ route('espace.echanges.store') }}" class="card space-y-4" x-data="{ audience: @js(old('audience', $audience)) }" novalidate>
                @csrf
                <fieldset>
                    <legend class="label">J'écris à</legend>
                    <div class="grid gap-2 sm:grid-cols-3">
                        @foreach (\App\Enums\MessageAudience::cases() as $case)
                            @php($disabled = $case === \App\Enums\MessageAudience::Members && ! $canWriteToAll)
                            <label class="cursor-pointer rounded-xl p-3 text-sm ring-1 transition"
                                   :class="audience === '{{ $case->value }}' ? 'bg-vert text-white ring-vert' : 'bg-white ring-brun/20 hover:bg-vert-clair'"
                                   @if ($disabled) aria-disabled="true" style="opacity:.5;cursor:not-allowed" @endif>
                                <input type="radio" name="audience" value="{{ $case->value }}" x-model="audience" class="sr-only" @disabled($disabled)>
                                <span class="block text-lg" aria-hidden="true">{{ ['adherents' => '👥', 'referent' => '🧑‍🌾', 'bureau' => '🏛️'][$case->value] }}</span>
                                <strong>{{ $case->getLabel() }}</strong>
                            </label>
                        @endforeach
                    </div>
                    @unless ($canWriteToAll)
                        <p class="mt-2 text-xs text-doux">Écrire à tous les adhérents est réservé aux adhérents à jour de cotisation.</p>
                    @endunless
                    @error('audience')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror
                </fieldset>

                <div x-show="audience === 'referent'" x-cloak>
                    <label class="label" for="producer_id">Au sujet du producteur</label>
                    <select class="input" id="producer_id" name="producer_id">
                        <option value="">Choisissez…</option>
                        @foreach ($producers as $producer)
                            <option value="{{ $producer->id }}" @selected((int) old('producer_id', $producerId) === $producer->id)>{{ $producer->name }} (référent : {{ $producer->referent->first_name }})</option>
                        @endforeach
                    </select>
                    @error('producer_id')<p class="mt-1 text-sm text-tomate">Choisissez un producteur.</p>@enderror
                </div>

                <div><label class="label" for="subject">Objet</label><input class="input" id="subject" name="subject" value="{{ old('subject') }}" maxlength="120" required>@error('subject')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
                <div><label class="label" for="body">Message</label><textarea class="input" id="body" name="body" rows="7" required>{{ old('body') }}</textarea>@error('body')<p class="mt-1 text-sm text-tomate">{{ $message }}</p>@enderror</div>
                <p class="text-xs text-doux" x-show="audience === 'adherents'">Votre nom et votre adresse e-mail seront visibles des destinataires, pour qu'ils puissent vous répondre. Le message apparaît aussi dans la liste ci-contre.</p>
                <button class="btn-primary">Envoyer</button>
            </form>

            <aside>
                <h2 class="text-xl font-bold">Derniers messages à tous</h2>
                <ul class="mt-3 space-y-3">
                    @forelse ($messages as $message)
                        <li class="card">
                            <p class="text-xs text-doux">{{ $message->sender->first_name }} {{ mb_substr($message->sender->last_name, 0, 1) }}. · {{ $message->created_at->translatedFormat('j F à H\hi') }}</p>
                            <p class="mt-1 font-bold">{{ $message->subject }}</p>
                            <details class="mt-1 text-sm">
                                <summary class="cursor-pointer text-vert">Lire</summary>
                                <p class="mt-2 whitespace-pre-line text-doux">{{ $message->body }}</p>
                                <a href="mailto:{{ $message->sender->email }}?subject={{ rawurlencode('Re: '.$message->subject) }}" class="mt-2 inline-block font-bold text-vert">Répondre à {{ $message->sender->first_name }}</a>
                            </details>
                        </li>
                    @empty
                        <li class="text-sm text-doux">Aucun message pour le moment.</li>
                    @endforelse
                </ul>
            </aside>
        </div>
    </div>
</x-layouts.app>
