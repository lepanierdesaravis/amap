# Le Panier des Aravis – conventions du dépôt

Application de l'AMAP Le Panier des Aravis (Thônes). Le besoin est décrit dans `docs/02-cahier-des-charges.md`, les phases dans `docs/03-plan-de-developpement.md`. Le `README.md` explique comment lancer le projet.

Chaque exigence du cahier des charges a un numéro (`ADH-07`, `REF-13`…), qui ne change jamais. `docs/04-suivi-conformite.md` donne l'état de chacune : mettez-le à jour quand une fonctionnalité change, et citez le numéro dans les commits et les tickets.

## Environnement
- Tout passe par Docker avec Laravel Sail : `./vendor/bin/sail artisan …`, `./vendor/bin/sail npm …`, `./vendor/bin/sail pint`. N'installez ni PHP ni Composer sur la machine.
- Laravel 13, PHP 8.4, MariaDB 11, Filament 5 (API de type v4 : `Filament\Schemas\Schema`, `->components()`, `Filament\Actions\*`), Livewire 4, Tailwind CSS v4, spatie/laravel-permission, barryvdh/laravel-dompdf.
- Avant de proposer un changement : `./vendor/bin/sail artisan test` et `./vendor/bin/sail pint`.

## Principes métier
- **Simple pour les adhérents.** On ne leur explique pas les semaines paires et impaires : le calendrier se remplit tout seul quand ils choisissent une fréquence. Chaque écran adhérent doit rester utilisable sur téléphone, sans mode d'emploi.
- **L'AMAP n'encaisse rien.** Les adhérents paient les producteurs par chèque ou virement. Le site gère seulement les échéances et leur pointage (attendu → reçu → encaissé). Seule la cotisation pourra passer par HelloAsso.
- Un **référent** gère uniquement les producteurs qu'il a créés (`producers.referent_id`). Un **producteur** consulte son espace, mais ses modifications passent par son référent. L'**administrateur** a tous les droits (`Gate::before`).
- Pas de modification d'un contrat après sa clôture, ni après le pointage d'un de ses paiements.
- La table `deliveries` est la référence pour savoir qui reçoit quoi et à quelle distribution. Les contrats et les commandes groupées l'alimentent via `App\Services\OrderService`.
- L'ouverture et la clôture des offres et des commandes groupées se calculent à partir des dates (`opens_at`, `closes_at`). Seules les annonces par e-mail passent par une tâche planifiée (`amap:annonces`, voir `routes/console.php`) ; les e-mails groupés passent par la file d'attente.
- Un **montant d'engagement** est un produit avec `is_amount` : son prix vaut pour la saison. Une commande groupée liée à une offre (`contract_offer_id`) est déduite du contrat des adhérents engagés (`group_orders.contract_id`, sans échéance) et réglée sur place par les autres.
- Icônes des produits : banque `config/icones.php` + `resources/icons/produits/*.svg` (game-icons.net, CC BY 3.0), affichées par `App\Support\Icons` ou `<x-icone>`. Une icône dans une couleur ne sert qu'à un producteur.
- Le code doit rester réutilisable par d'autres AMAP : nom, adresses, cotisation et sujets de contact sont dans `config/amap.php`, pas en dur.

## Conventions de code
- Montants en **centimes** (entiers, colonnes `*_cents`). Affichage avec `App\Support\Money::format()`. Répartition des échéances avec `Money::split()` : le reste va sur la dernière.
- Les valeurs fermées sont des enums dans `app/Enums` (valeurs en français, libellés via `getLabel()`).
- Le code (classes, méthodes, colonnes) est en anglais. Tout ce que voit l'utilisateur est en français : textes, URL (`/mon-espace`, `/gestion/producteurs`), e-mails, PDF.
- Commentaires en français, courts, seulement quand le *pourquoi* n'est pas évident.
- Droits : une policy par modèle dans `app/Policies`. Les listes Filament des référents sont filtrées par `Fields::scopeToReferent()`.
- Back-office Filament : une ressource par dossier (`Schemas/`, `Tables/`, `Pages/`, `RelationManagers/`). Les libellés français passent par le trait `FrenchLabels`.
- Espace adhérent : composants Livewire dans `app/Livewire/Member`, gabarits Blade avec les classes du thème (`resources/css/app.css` : couleurs `vert`, `terre`, `miel`, `brun`, `creme`…).
- Tests PHPUnit dans `tests/`. Le trait `Tests\Support\BuildsAmap` construit les données de test (producteur, distributions, offre…).

## Données de démonstration
- `DemoSeeder` : vrais producteurs (`database/seeders/data/producers.json` et `database/seeders/media`), adhérents fictifs en `@exemple.fr`, mot de passe `panier2027`.
- La route `/demo/connexion/{email}` n'existe qu'en environnement local.

## Ton
Dans la documentation et les textes destinés à l'association, décrivez le site actuel de façon factuelle, sans terme péjoratif. Il a été fait par des bénévoles.
