# Plan de développement

*Version 1 du 15/11/2026. Elle s'appuie sur le [cahier des charges v1](02-cahier-des-charges.md).*

## 1. Les dates qui imposent le calendrier

| Date | Contrainte | Conséquence |
|---|---|---|
| **Décembre 2026** | Campagne d'adhésion 2027, sur le site Wix actuel | On ne touche pas au site en production. |
| **13/02/2027** | Le domaine `lepanierdesaravis.fr` expire | On le transfère chez Infomaniak **en janvier 2027**, en recopiant à l'identique les enregistrements DNS (site → Wix, e-mail → IONOS). Le transfert ne coupe donc rien. |
| Toute l'année 2027 | Commandes groupées et contrats en cours sur la boutique Wix | La bascule doit reprendre les commandes groupées en cours, ou attendre une période creuse. |
| **Fin 2027** | Échéance de l'abonnement Wix (date exacte à vérifier) | C'est la bascule finale au plus tard. |
| **Décembre 2027** | Campagne d'adhésion et de contrats 2028 | **Objectif : la faire sur le nouveau site.** |

Il y a donc **deux mises en production** :
1. **Septembre 2027** : nouveau site public, comptes adhérents, commandes groupées, permanences et pointage. Le site Wix s'arrête alors.
2. **Novembre 2027** : contrats (commandes standard) et adhésion en ligne, juste à temps pour la campagne 2028.

## 2. Les technologies

| Élément | Choix | Pourquoi |
|---|---|---|
| Langage et framework | **PHP 8.3+**, **Laravel** (dernière version stable) | Fonctionne sur l'hébergement web Infomaniak, très répandu |
| Back-office (gestion) | **Filament** | Génère les formulaires, les listes, les exports et le tableau de bord des référents et administrateurs |
| Espace adhérent | **Livewire** + **Tailwind CSS** | Écrans simples, interactifs, pensés pour le mobile (panier et calendrier qui se mettent à jour en direct) |
| Site public | Gabarits Blade + Tailwind | Pages légères et rapides |
| Base de données | **MariaDB/MySQL**, incluse dans l'hébergement | Aucun serveur supplémentaire à gérer |
| Rôles et droits | `spatie/laravel-permission` + *Filament Shield* | Rôles cumulables, droits « ses producteurs uniquement » |
| Photos et documents | `spatie/laravel-medialibrary` | Recadrage et formats optimisés automatiquement |
| PDF | **DomPDF** (100 % PHP) | Fonctionne sur un hébergement mutualisé, sans navigateur sur le serveur |
| Excel | Export intégré à Filament | Récapitulatifs et listes |
| E-mails | SMTP Infomaniak (`ne-pas-repondre@`) + file d'envoi | Envois groupés étalés dans le temps |
| Tâches automatiques | Planificateur Laravel, lancé par le **cron Infomaniak** | Ouverture et clôture des commandes, rappels |
| Paiement de l'adhésion | **HelloAsso** (API et notifications automatiques) | Gratuit pour l'association |
| Tests | **Pest** | Lancés automatiquement à chaque modification |
| Code et suivi | **GitHub** (organisation de l'association), GitHub Actions | Tickets, revues, déploiement automatique |
| Licence | **AGPL-3.0** (proposition) | Libre et réutilisable. Les AMAP qui l'améliorent partagent leurs améliorations. |

## 3. L'organisation du travail sur GitHub

- **Organisation GitHub `lepanierdesaravis`** (gratuite). Au moins **2 propriétaires** (membres du bureau). Les développeurs sont membres. Le dépôt appartient à l'association, pas à une personne. On peut ensuite demander le programme *GitHub for Nonprofits* (offre Team gratuite), mais ce n'est pas nécessaire au départ.
- **Dépôt `amap`** (nom générique, pour qu'il soit réutilisable), public.
- **Façon de travailler :**
  1. Un **ticket** décrit le besoin. Tout le monde peut en ouvrir, grâce à des modèles « Bug » et « Idée ».
  2. Une **branche** par ticket, développée avec Claude Code.
  3. Une **demande de fusion** (pull request) : les tests automatiques passent, puis au moins une personne relit.
  4. La fusion dans `main` → **déploiement automatique sur le site de test** (`test.lepanierdesaravis.fr`).
  5. Une **version** (étiquette `v1.2.0`) → **déploiement en production**.
- **Jalons** GitHub = les phases ci-dessous. Un **tableau de projet** avec les colonnes À faire / En cours / À relire / Fait.
- **Étiquettes** : `adhérent`, `référent`, `producteur`, `admin`, `public`, `bug`, `idée`, `bon-premier-ticket`.
- **Un ticket est terminé quand** : la fonctionnalité est testée automatiquement, vérifiée sur mobile, documentée si elle change l'usage, et relue.
- Un fichier `CLAUDE.md` dans le dépôt décrit les conventions, pour que Claude Code les respecte à chaque session.

## 4. Les phases

### Phase 0 : prototype et fondations (novembre 2026 à janvier 2027)
**Objectif : valider les choix avec de vrais écrans avant d'engager le gros du travail.**
- **Prototype local** dans ce dossier (détail au §5).
- Démonstration au bureau et à 2 ou 3 référents, puis ajustement du cahier des charges.
- Création de l'organisation GitHub et du dépôt, licence, `README`, `CLAUDE.md`, modèles de tickets.
- **Janvier 2027** : ouverture du compte Infomaniak (Web + Mail), **transfert du domaine** sans changer la destination du site ni des e-mails.

### Phase 1 : le socle (janvier à mars 2027)
- Comptes : inscription, validation de l'e-mail, mot de passe oublié, profil, consentements.
- Rôles et droits (adhérent, producteur, référent, admin), attribution des référents aux producteurs.
- Paramètres de l'AMAP (nom, logo, couleurs, adresses, cotisation), pour la réutilisation par d'autres AMAP.
- **Producteurs et produits** dans le back-office, avec génération des fiches publiques.
- **Site public** : accueil, l'AMAP, producteurs, contact (formulaire avec routage par sujet), pages légales.
- Hébergement de test chez Infomaniak, déploiement automatique, sauvegardes.
- *Livrable : site de test en ligne, sur lequel les référents commencent à saisir leurs producteurs.*

### Phase 2 : distributions et permanences (mars à mai 2027)
- Calendrier des distributions : génération annuelle, lieux, producteurs présents, séries A/B.
- Calendrier public, export PDF du planning annuel (équivalent du « Planning 2026 »).
- **Permanences** : vœux, constitution des équipes, responsable, échanges et remplaçants, inscription par un référent.
- E-mails de rappel de permanence.
- *Livrable : on peut gérer les permanences de l'été 2027 en parallèle de Wix (essai réel).*

### Phase 3 : commandes groupées, documents et pointage (mai à juillet 2027)
- **Commandes groupées** : création par formulaire, ouverture et clôture automatiques, annonce par e-mail, duplication.
- Parcours adhérent : panier, calendrier, confirmation par e-mail, modification jusqu'à la clôture.
- **Documents** : récapitulatif producteur, feuille d'émargement (d'après vos modèles PDF), envoi par e-mail.
- **Espace producteur** : récapitulatifs, pointage.
- **Pointage des paiements**, vu par l'adhérent en lecture seule.
- *Livrable : une vraie commande groupée testée de bout en bout avec un producteur et 10 adhérents.*

### Phase 4 : mise en production 1 (août à septembre 2027)
- Contenus définitifs, photos, redirections depuis les anciennes adresses Wix.
- Bascule des **e-mails** d'IONOS vers Infomaniak (boîtes et alias), puis du **site** (DNS).
- Invitation de tous les adhérents à créer leur compte (e-mail et aide à la permanence).
- Arrêt de la boutique Wix, résiliation de Wix et d'IONOS.
- *Livrable : lepanierdesaravis.fr tourne sur la nouvelle application.*

### Phase 5 : contrats et adhésion en ligne (septembre à novembre 2027)
- **Offres de contrat** (saison, produits, fréquences, échéanciers, ouverture et clôture, texte du contrat), avec « Dupliquer l'an dernier ».
- **Parcours de souscription** : produit, quantité, fréquence, calendrier en direct, panier, échéancier par producteur, confirmation et contrat en PDF.
- Génération des livraisons. Récapitulatifs par distribution. Clôture de distribution par l'admin, avec envoi aux producteurs.
- **Adhésion en ligne** (HelloAsso) et pointage des cotisations.
- E-mails groupés (lettre d'information, annonces ciblées).
- Pilote en octobre avec 2 producteurs et une quinzaine d'adhérents volontaires.
- *Livrable : campagne d'adhésion et de contrats 2028 entièrement sur le site (décembre 2027).*

### Phase 6 : après le lancement (2028)
Améliorations demandées par les utilisateurs, puis évolutions du §15 du cahier des charges : rappels avancés, tutoriels, blog, et éventuellement le paquet pour d'autres AMAP.

## 5. Le prototype local (phase 0)

**But :** montrer et faire tester, pas mettre en production. Il tourne sur l'ordinateur, avec des données d'exemple réalistes : les vrais producteurs et les vraies photos du site actuel, mais des adhérents fictifs.

**Contenu proposé :**
1. Site public aux couleurs de l'AMAP : accueil, l'AMAP, liste et fiches producteurs (reprises du site Wix), calendrier, contact.
2. Connexion avec 4 comptes de démonstration : adhérent, référent, producteur, admin.
3. **Back-office référent** : « Ajouter un producteur » (formulaire → fiche publique), produits, « Nouvelle commande groupée ».
4. **Espace adhérent** : tableau de bord et **parcours de commande groupée** complet (panier, calendrier, confirmation).
5. **Ébauche du parcours contrat** : choix d'un produit, d'une quantité et d'une fréquence, avec calendrier et échéancier. C'est l'écran le plus délicat, à valider tôt.
6. **Feuille d'émargement et récapitulatif producteur en PDF.**
7. E-mails visibles dans une boîte de test locale (aucun envoi réel).

**Pas dans le prototype :** paiement en ligne, envois réels, mise en ligne, permanences complètes.

**Outillage nécessaire :** PHP 8.3+ et Composer ne sont pas installés sur cette machine. Il y a deux options : les installer, ou utiliser **Docker** (déjà présent) avec *Laravel Sail*, l'environnement officiel de Laravel. Voir les questions à trancher.

## 6. Budget annuel estimé

| Poste | Coût |
|---|---|
| Hébergement Web + Mail Infomaniak (5 adresses incluses) | environ 87 €/an (7,25 €/mois, à confirmer) |
| Nom de domaine `.fr` | environ 10 à 15 €/an |
| GitHub (organisation) | 0 € |
| HelloAsso | 0 € (contribution volontaire des payeurs) |
| Laravel, Filament et toutes les bibliothèques | 0 € (open source) |
| **Total** | **environ 100 €/an**, contre environ 168 €/an aujourd'hui |

Le développement est bénévole, accéléré par Claude Code. Il faut prévoir l'abonnement Claude du ou des développeurs, s'il n'est pas déjà couvert.

## 7. Risques et parades

| Risque | Parade |
|---|---|
| Une seule personne connaît le code | Au moins 2 développeurs ou relecteurs, `CLAUDE.md`, documentation, code standard Laravel |
| Le calendrier glisse | Deux mises en production indépendantes. Wix reste en place tant que la phase 4 n'est pas faite. |
| Des adhérents peu à l'aise avec l'informatique | Écrans très simples, aide à la permanence, inscription possible par un référent |
| Perte de données | Sauvegardes quotidiennes + test de restauration chaque trimestre |
| Faille de sécurité | Mises à jour mensuelles, droits testés automatiquement, aucun secret dans GitHub |
| Limites d'envoi d'e-mails de l'hébergement | Envois étalés par une file d'attente. Vérifier les quotas Infomaniak avant la phase 5. |
