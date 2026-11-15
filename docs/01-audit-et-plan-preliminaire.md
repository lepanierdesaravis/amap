# Le Panier des Aravis : audit du site actuel et plan préliminaire

*Audit réalisé le 15/11/2026 sur https://www.lepanierdesaravis.fr*

> **Note sur le domaine :** `lepanierdesaravis.com` n'existe pas (le domaine n'est pas enregistré).
> Le site et l'adresse e-mail de l'AMAP utilisent **`lepanierdesaravis.fr`**.

---

## 1. Audit du site actuel

### 1.1 Infrastructure (ce que la migration devra reprendre)

| Élément | Constat | Conséquence |
|---|---|---|
| Domaine | `lepanierdesaravis.fr`, créé en 2011, **expire le 13/02/2027** | Renouveler ou transférer avant cette date. Le bureau d'enregistrement (registrar) reste à identifier. |
| DNS | Gérés par Wix (`ns14/ns15.wixdns.net`) | Il faudra reprendre la zone DNS, y compris les enregistrements MX, avant de quitter Wix. |
| E-mail | MX chez **IONOS (1&1)** : `mx00/mx01.1and1.fr`. Adresse connue : `contact@lepanierdesaravis.fr` | Un troisième prestataire est en jeu. Migrer les boîtes vers Infomaniak demande une bascule MX coordonnée. |
| Hébergement | Wix (serveur « Pepyaka », CDN Cloudflare), avec un abonnement Premium et une boutique | L'abonnement Wix est à résilier après la bascule. Vérifier sa date d'échéance. |
| HTTPS / HSTS | OK | — |

À récupérer avant la migration : les accès au compte Wix, au compte IONOS et au registrar, ainsi que la date de renouvellement de chaque abonnement.

### 1.2 Structure et navigation

- **31 pages**, un blog (10 articles) et **environ 280 fiches produits** dans la boutique Wix.
- Le menu principal contient un sous-menu « Nos Producteurs » de **17 entrées**. C'est trop long, surtout sur mobile, et il faut le modifier à la main à chaque changement de producteur.
- Les **URL des pages ne correspondent pas à leur contenu**. Les pages producteurs ont été créées par copier-coller, ce qui montre la difficulté d'ajouter un producteur aujourd'hui :

  | URL | Contenu réel |
  |---|---|
  | `/copie-de-la-ferme-de-pezières-1` | Le Corti des Aravis |
  | `/copie-de-la-ferme-de-pezières` | Le Fournil des Paysans |
  | `/copie-de-la-ferme-de-pezières-2` | La Ferme du Ponant |
  | `/copie-de-le-fournil-des-paysans` | Vale Douro |
  | `/copie-de-le-fournil-des-paysans-1` | Boulangerie chez Geoffrey |
  | `/copie-de-la-cabane-marjolaine` | La Chèvrerie de Lily |
  | `/copie-de-château-farizeau` | Mas d'Intras |
  | `/cotesdescaris` | Château Farizeau |
  | `/epicesttout` | Lait'able des Frangins |

- **Pages orphelines ou obsolètes encore indexées par Google :**
  - `/fermedespetitesreines` : la page s'intitule « Zzz La Ferme des Petites Reines ». C'est un ancien producteur, masqué mais toujours publié.
  - `/copie-de-modèle` : la page des Paysans Voyageurs. Elle n'est pas dans le menu, alors que leurs confitures sont vendues dans la boutique.
  - `/planning-2024-pdf` : une page vide intitulée « x ».
  - `/event` et `/events` : la page « Nos Événements » est vide.

### 1.3 Contenu

| Page | Constat |
|---|---|
| Accueil | Elle ne contient qu'un titre, un bouton « ADHÉRER » et deux PDF (bulletin d'adhésion, code de bonne conduite). Aucune actualité, prochaine distribution ou date d'adhésion n'y figure. Pourtant, la page *Demande d'adhésion* annonce que « les dates [des permanences d'adhésion] sont communiquées fin novembre sur la page d'accueil ». |
| Qu'est-ce qu'une AMAP / Notre fonctionnement | Le contenu est clair et à jour. Le planning des distributions se trouve sur une page séparée (`/planning`, « Planning Distributions 2026 »). |
| Nos producteurs | Une grille de 17 vignettes. Les fiches sont riches (texte, adresse, téléphone, site web, PDF) mais leur mise en page n'est pas homogène. |
| Demande d'adhésion | Cotisation 2026 : 20 €. Un **mot de passe commun** est remis en permanence pour accéder à l'espace adhérent. |
| Nous contacter | Elle ne donne que deux adresses postales : **aucune adresse e-mail, aucun formulaire, aucun téléphone.** |
| Blog | Le dernier article date de janvier 2025 et la plupart datent de 2017 à 2021. |
| Pied de page | Il affiche « © 2023 » et un lien « Webmaster Login ». |
| Mentions légales | **La page `/mentionslegales` est en réalité une copie de l'accueil** (elle est en noindex et sans contenu légal). Les mentions légales sont pourtant une obligation légale (loi LCEN). |

### 1.4 Espace adhérents et commandes : le point clé

L'AMAP utilise la **boutique Wix (e-commerce) comme outil de contrats et de commandes** :

- Les contrats annuels sont saisis comme des produits : « Œufs par 24 – 180 € », « Panier à 14 € », « Engagement de 50 € jusqu'à fin mars », « Engagement de 1000 € pour l'année »…
- On compte **environ 280 fiches produits**, dont de nombreux doublons (`-1`, `-2`, `copie-de-…`) et des produits périmés (vins de 2008 à 2015, par exemple).
- La page `/shop` est **publique** : prix et produits sont visibles sans connexion.
- On accède à l'espace adhérent par un mot de passe commun, puis on crée son propre compte.

**Ce que le modèle « boutique » ne gère pas** (d'après le fonctionnement décrit) : un contrat annuel lié à un producteur, un échéancier de paiement en 1 à 3 règlements, le calendrier des distributions, les permanences et le suivi par producteur (qui a commandé quoi, pour quelle distribution). Tout cela est probablement géré à côté, dans des tableurs, des PDF ou des e-mails. **C'est le principal gisement d'amélioration.**

### 1.5 Technique, accessibilité, SEO, RGPD

- **Poids** : plus de 700 Ko de HTML par page et 11 scripts externes. C'est le poids habituel d'un site Wix, lourd sur mobile en montagne.
- **Accessibilité** : beaucoup d'images n'ont pas de texte alternatif (20/20 dans la boutique, 8/19 sur la page producteurs). Le titre « **A**ssociation pour le **M**aintien… » est découpé en 8 balises `h6`, si bien qu'un lecteur d'écran l'épelle.
- **SEO** : la plupart des pages n'ont pas de meta description, et les titres sont génériques (« lepanierdesaravis »). Les pages « copie-de-… » et « Zzz » sont indexées.
- **Divers** : la langue de l'interface est mélangée (« More » ou « Plus » selon la page). Les pages publiées contiennent un lien vers l'éditeur Wix.
- **RGPD** : des traceurs Facebook et Google (gtag) sont présents. Il faut vérifier le bandeau de consentement. Aucune politique de confidentialité n'a été trouvée, alors que le site gère des comptes adhérents (données personnelles).

### 1.6 Points forts à conserver

- Les contenus de présentation (AMAP, fonctionnement, fiches producteurs) sont de bonne qualité.
- Les adhérents ont déjà l'habitude de commander en ligne.
- Le domaine est ancien et bien référencé localement (il est cité par la ville d'Annecy).

---

## 2. Infomaniak : premiers éléments (à confirmer)

| Besoin | Offre Infomaniak | Remarque |
|---|---|---|
| Site « clé en main » | **Site Creator** : gratuit pour 1 page. L'offre Pro donne des pages illimitées et une boutique. | **Aucun espace membres ni gestion des rôles n'a été trouvé** dans la documentation. Il paraît inadapté aux contrats et aux comptes adhérents. |
| Site sur mesure ou CMS | **Hébergement Web** : PHP, Node.js, MySQL/MariaDB, SSH, Git, cron, essai gratuit de 30 jours | Il permet d'héberger WordPress ou une application sur mesure déployée depuis GitHub. |
| Besoins plus lourds | Serveur Cloud managé (à partir de 29 €/mois environ) | Probablement inutile pour une AMAP. |
| Adresses e-mail | **Service Mail** : plusieurs adresses, alias et redirections sur `lepanierdesaravis.fr` | Il permet d'avoir des adresses par fonction (voir §3.4). |
| Domaine | Transfert du `.fr` chez Infomaniak | Il simplifie la gestion : domaine, DNS, mail et site au même endroit. |
| Données | Hébergement en Suisse | La Suisse bénéficie d'une décision d'adéquation de l'UE, donc c'est compatible avec le RGPD. |
| Tarif associatif | Aucun trouvé | Le demander au support. |

---

## 3. Plan préliminaire

### 3.1 Contrainte de calendrier

Les adhésions ont lieu **début décembre** et les contrats démarrent **en janvier**. Il ne faut **pas basculer entre novembre et février**. La campagne 2027 se fera donc sur le site Wix actuel. Deux cibles sont réalistes :

- **Printemps ou été 2027** : la partie publique (vitrine et producteurs), avec la bascule du domaine et des e-mails avant le 13/02/2027 si on transfère le domaine.
- **Novembre 2027** : l'espace adhérent et les contrats, prêts pour la campagne d'adhésion 2028, après une phase de test avec quelques adhérents et producteurs.

### 3.2 Les options techniques à comparer après le cahier des charges

| Option | Facilité pour les super-utilisateurs | Espace adhérent et contrats | Travail avec Claude Code et GitHub | Coût et maintenance |
|---|---|---|---|---|
| **A. Infomaniak Site Creator** | Très bonne | Insuffisant (pas d'espace membres) | Faible (outil fermé, pas de code) | Faible |
| **B. CMS existant + extension AMAP** (par ex. WordPress avec l'extension *Amapress*, conçue pour les AMAP : contrats, distributions, permanences) sur l'Hébergement Web Infomaniak | Bonne (interface d'administration standard) | Bon, à vérifier sur le cahier des charges | Moyen (thème et extensions personnalisés dans le dépôt GitHub) | Mises à jour régulières à faire |
| **C. Application sur mesure** (framework web, base MySQL, back-office) sur l'Hébergement Web Infomaniak | Bonne si le back-office est bien conçu | Excellent (fait exactement pour le besoin) | Excellent (tout le code est dans GitHub) | Le plus de développement. Dépend des développeurs bénévoles sur la durée. |
| **D. Logiciel AMAP en ligne existant** (Cagette, Clic'AMAP, AmapJ… à évaluer) + site vitrine | Bonne | Bon (outil dédié) | Faible pour la partie commandes | Abonnement possible. Données hors de notre contrôle. |

**Premier avis :** l'option A ne couvre pas le besoin principal. Il faut **évaluer B et D avant de se lancer dans C**. Un outil existant conçu pour les AMAP couvre peut-être déjà 80 % du besoin. L'option C ne vaut le coup que si le cahier des charges fait apparaître des besoins spécifiques qu'aucun outil ne couvre, et si une équipe peut la maintenir sur plusieurs années.

### 3.3 Rôles envisagés

| Rôle | Peut… |
|---|---|
| Visiteur | consulter la présentation, les producteurs et le calendrier, demander une adhésion, contacter l'AMAP |
| Adhérent | gérer son profil, souscrire et consulter ses contrats, voir ses échéances, s'inscrire aux permanences, participer aux commandes groupées |
| Super-utilisateur (référent producteur, coordinateur) | créer ou modifier une fiche producteur, ouvrir un contrat ou une commande groupée, voir les listes de commandes et de distributions de ses producteurs |
| Administrateur | tout ce qui précède, plus la gestion des comptes et des rôles, les paramètres, les exports et la cotisation |
| *(à discuter)* Producteur | consulter ses commandes et mettre à jour sa fiche |

### 3.4 Adresses e-mail par fonction (proposition)

`contact@` (général), `adhesion@`, `producteurs@` ou une adresse par référent, `permanences@`, `tresorerie@`, `webmaster@`, `bureau@`. Il s'agit d'alias ou de boîtes partagées : la personne qui reçoit change sans changer d'adresse.

### 3.5 Plan d'architecture du nouveau site (première version)

```
Accueil (prochaine distribution, actualités, période d'adhésion, CTA « Adhérer »)
├── L'AMAP (qu'est-ce qu'une AMAP, notre fonctionnement, charte, code de bonne conduite)
├── Producteurs (liste filtrable par type de produit → fiche producteur générée automatiquement)
├── Calendrier (distributions, permanences, événements)
├── Adhérer (étapes, cotisation, dates des permanences d'adhésion, formulaire)
├── Actualités et recettes (ancien blog)
├── Contact (formulaire avec choix du destinataire : adhésion, producteurs, bureau…)
├── Espace adhérent 🔒
└── Mentions légales · Confidentialité · Accessibilité
```

Avec des adresses propres comme `/producteurs/la-ferme-du-coteau`, et des **redirections 301** depuis les anciennes adresses Wix pour ne pas perdre le référencement.

### 3.6 Étapes

| # | Étape | Livrable |
|---|---|---|
| 0 | **Inventaire des accès et des données** : comptes Wix, IONOS et registrar, échéances, export des membres, commandes, produits, PDF et images | Liste des accès et sauvegarde complète |
| 1 | **Cahier des charges** (prochaine étape, à partir de vos éléments) | Document validé par le bureau |
| 2 | **Choix de la solution** : grille A/B/D/C, essais rapides de 1 ou 2 outils AMAP | Décision argumentée |
| 3 | **Mise en place de l'infrastructure** : dépôt GitHub (issues, modèles de tickets, tableau de projet), compte Infomaniak, environnement de test | Dépôt et environnement de test en ligne |
| 4 | **V1 : site public** et gestion des fiches producteurs par les super-utilisateurs | Site public |
| 5 | **Bascule** du domaine, des DNS et des e-mails vers Infomaniak, redirections 301, résiliation de Wix et IONOS | Site en production |
| 6 | **V2 : espace adhérent**, contrats, échéancier, permanences, commandes groupées | Test avec quelques adhérents, puis campagne d'adhésion |
| 7 | **Formation et documentation** des super-utilisateurs (guide « ajouter un producteur » en 1 page) | Guides et transmission |

### 3.7 Questions pour le cahier des charges

1. **Paiements** : sont-ils faits en ligne aujourd'hui (via la boutique Wix) ou par chèque ou virement ? Faut-il un paiement en ligne demain (frais, encaissement par l'AMAP ou par chaque producteur) ?
2. **Contrats** : quels types existent (annuel, trimestriel, ponctuel, « engagement » d'un montant) ? Les paniers ont-ils des tailles et des fréquences variables ?
3. **Commandes groupées** : qui les ouvre, combien de temps elles durent, qui récupère la liste et sous quel format ?
4. **Permanences** : comment les inscriptions se font-elles aujourd'hui ? Faut-il des rappels par e-mail ?
5. **Volume** : combien d'adhérents, de producteurs et de super-utilisateurs ?
6. **Producteurs** : doivent-ils avoir un accès direct ?
7. **Communication** : faut-il des e-mails groupés aux adhérents (lettre d'information, rappels de distribution) ?
8. **Données à reprendre** depuis Wix : comptes adhérents, historique des commandes ?
9. **Budget annuel** acceptable et **équipe** : qui développe, et qui maintiendra dans 3 ans ?
10. **Identité visuelle** : faut-il garder le logo et les couleurs ?

---

### Sources

- Audit direct du site : pages, sitemap, robots.txt, enregistrements DNS et données RDAP de l'AFNIC
- [Ville d'Annecy : liste des AMAP](https://www.annecy-ville.fr/2023/09/amap-annecy/) (`contact@lepanierdesaravis.fr`)
- [Infomaniak : tarifs de l'hébergement](https://www.infomaniak.com/fr/hebergement/tarifs-et-caracteristiques)
- [Infomaniak : hébergement Node.js](https://www.infomaniak.com/en/hosting/nodejs-hosting)
- [Infomaniak : Site Creator](https://www.infomaniak.com/fr/site-creator)
- [Infomaniak : boutique dans Site Creator](https://infomaniak.com/fr/support/faq/2450/creer-une-boutique-e-commerce-dans-site-creator)
- [Infomaniak : offres Starter (mail et web)](https://infomaniak.com/fr/support/faq/2002/commander-un-produit-starter-service-mail-hebergement-web)
- [Journal du Geek : hébergeurs Node.js (prix 2026)](https://www.journaldugeek.com/hebergeur/node-js/)
- [InfoSwitch : migration des associations vers Infomaniak](https://infoswitch.fr/blog/associations-migration-infomaniak)
