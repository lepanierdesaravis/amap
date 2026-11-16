# Le Panier des Aravis – refonte du site

Application web sur mesure (Laravel + Filament) pour l'AMAP Le Panier des Aravis (Thônes) : site public, espace adhérent, espace producteur et back-office pour les référents et administrateurs.

## Lancer le prototype

Il faut seulement **Docker**. PHP, Composer et Node tournent dans les conteneurs ([Laravel Sail](https://laravel.com/docs/sail)).

```sh
# 1. Dépendances PHP (première fois seulement)
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html \
  laravelsail/php84-composer:latest composer install --ignore-platform-reqs
cp .env.example .env

# 2. Conteneurs (application, MariaDB, Mailpit)
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan storage:link

# 3. Base de démonstration et feuilles de style
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail npm ci
./vendor/bin/sail npm run build
```

| Adresse | Contenu |
|---|---|
| http://localhost:8080 | Site public et espace adhérent |
| http://localhost:8080/gestion | Back-office (référents et administrateurs) |
| http://localhost:8025 | Mailpit : les e-mails envoyés par le site (aucun envoi réel) |

**Comptes de démonstration** (mot de passe `panier2027`). La page de connexion propose aussi une connexion en un clic, uniquement en local.

| Compte | Rôle |
|---|---|
| `claire.martin@exemple.fr` | Adhérente |
| `jean.perrier@exemple.fr` | Référent des producteurs sous contrat |
| `marc.blanc@exemple.fr` | Référent des commandes groupées |
| `corti@exemple.fr` | Producteur (Le Corti des Aravis) |
| `admin@exemple.fr` | Administrateur |
| `zoe.nouvelle@exemple.fr` | Nouvelle adhérente, cotisation non réglée (ne peut pas commander) |

Les producteurs et leurs photos viennent du site actuel. Les adhérents, les commandes et les prix sont fictifs.

Commandes utiles :
```sh
./vendor/bin/sail artisan test              # tests automatiques
./vendor/bin/sail artisan migrate:fresh --seed   # remettre la démonstration à zéro
./vendor/bin/sail npm run dev               # feuilles de style rechargées en direct
./vendor/bin/sail artisan queue:work        # envoi des e-mails groupés (annonces, échanges)
./vendor/bin/sail artisan schedule:work     # annonces automatiques à l'ouverture
./vendor/bin/sail pint                      # mise en forme du code PHP
./vendor/bin/sail down                      # arrêter les conteneurs
```

**Pas encore dans le prototype :** permanences, adhésion en ligne (HelloAsso), e-mails groupés de l'administration, validation de l'adresse e-mail, mise en ligne.

Les icônes des produits viennent de [game-icons.net](https://game-icons.net) (Lorc, Delapouite et contributeurs), sous licence [CC BY 3.0](https://creativecommons.org/licenses/by/3.0/).

## Documents
| Fichier | Contenu |
|---|---|
| [docs/01-audit-et-plan-preliminaire.md](docs/01-audit-et-plan-preliminaire.md) | Audit du site Wix actuel (15/11/2026) |
| [docs/02-cahier-des-charges.md](docs/02-cahier-des-charges.md) | Cahier des charges, exigences numérotées |
| [docs/03-plan-de-developpement.md](docs/03-plan-de-developpement.md) | Phases, technologies, organisation GitHub, budget, prototype |
| [docs/diagrammes/plan-du-site.pdf](docs/diagrammes/plan-du-site.pdf) | Plan du site par niveau d'accès (source HTML, export PNG et PDF) |
| [docs/diagrammes/modele-de-donnees.svg](docs/diagrammes/modele-de-donnees.svg) | Modèle de données simplifié (source Graphviz `.dot`) |
| [presentation/presentation-refonte.pdf](presentation/presentation-refonte.pdf) | Présentation de la refonte (source HTML) |
| [assets/brand/](assets/brand/) | Logo, couleurs, typographies |
| [assets/wix-import/](assets/wix-import/) | Images et PDF récupérés du site actuel, avec leur page d'origine |

## Regénérer les documents
```sh
# Présentation et plan du site (Chrome headless)
google-chrome --headless=new --no-pdf-header-footer --virtual-time-budget=8000 \
  --print-to-pdf=presentation/presentation-refonte.pdf presentation/presentation-refonte.html
google-chrome --headless=new --no-pdf-header-footer \
  --print-to-pdf=docs/diagrammes/plan-du-site.pdf docs/diagrammes/plan-du-site.html
# Modèle de données (Graphviz)
dot -Tsvg docs/diagrammes/modele-de-donnees.dot -o docs/diagrammes/modele-de-donnees.svg
```
