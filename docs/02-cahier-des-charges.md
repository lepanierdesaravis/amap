# Cahier des charges (v1 – 15/11/2026)

*Version mise en forme du cahier des charges initial (fichier org de l'AMAP). Elle intègre les réponses à l'audit et les règles tirées des documents existants : contrat type 2026 du Corti des Aravis, code de bonne conduite 2026, bulletin d'adhésion et planning 2026.*

## 1. Présentation du projet

### 1.1 Contexte

-   AMAP « Le Panier des Aravis », Thônes (74). Site actuel sur Wix : <https://www.lepanierdesaravis.fr>
-   Environ **100 adhérents**, **9 producteurs sous contrat**, environ **20 producteurs en commandes groupées**, environ **10 référents**.
-   Une distribution **tous les 15 jours** (semaines calendaires impaires), le samedi de 9h30 à 10h30, à l'EHPAD « Le Chant du Fier » à Thônes. Les permanents arrivent à 8h30.
-   Les contrats couvrent l'année civile (de janvier à décembre). Les adhésions ont lieu début décembre.

### 1.2 Objectifs

1.  Remplacer le site Wix par une **application web sur mesure, open source**, hébergée chez Infomaniak.
2.  Donner à chaque personne un **compte personnel** avec un rôle : adhérent, producteur, référent ou administrateur.
3.  **Gérer en ligne** les contrats (« commandes standard »), les commandes groupées, les distributions, les permanences et le pointage des paiements.
4.  Permettre aux référents de **créer une page producteur ou une commande groupée en remplissant un formulaire**, sans connaissances techniques.
5.  **Générer automatiquement** les documents de distribution (récapitulatifs producteurs, feuilles d'émargement) et les e-mails (confirmations, rappels).
6.  Rendre l'application **réutilisable par d'autres AMAP** (nom, logo, couleurs, lieux, règles paramétrables).

### 1.3 Décisions prises

| Sujet                   | Décision                                                                                          |
|-------------------------|---------------------------------------------------------------------------------------------------|
| Type de solution        | Application 100 % sur mesure                                                                      |
| Technologies            | Laravel (PHP) + Filament (back-office) + MySQL/MariaDB                                            |
| Hébergement             | Hébergement Web Infomaniak (offre Web + Mail)                                                     |
| Code source             | Open source, dans une *organisation* GitHub appartenant à l'association                           |
| Paiements des contrats  | **L'AMAP n'encaisse rien** : chèques ou virements à l'ordre du producteur, avec pointage en ligne |
| Paiement de l'adhésion  | En ligne possible (HelloAsso recommandé), sinon chèque ou virement                                |
| Reprise des données Wix | Aucune. Chaque adhérent crée son compte. Seuls les contenus et les images sont repris.            |
| Identité visuelle       | Logo et couleurs conservés, présentation modernisée                                               |
| Blog                    | Mis de côté (hors V1)                                                                             |

### 1.4 Hors périmètre de la V1 (évolutions futures, §15)

Scénarios d'e-mail marketing avancés, tutoriels interactifs, vidéos pédagogiques, blog, paiement en ligne des contrats.

## 2. Glossaire

| Terme                       | Définition                                                                                                   |
|-----------------------------|--------------------------------------------------------------------------------------------------------------|
| Adhérent (amapien·ne)       | Personne à jour de sa cotisation annuelle                                                                    |
| Producteur                  | Paysan ou paysanne partenaire. Il a un espace et des produits.                                               |
| Référent                    | Adhérent bénévole qui suit un ou plusieurs producteurs ou commandes groupées (le « super-utilisateur »)      |
| Contrat (commande standard) | Engagement d'un adhérent auprès d'un producteur pour une saison : produits, quantités, fréquence, échéancier |
| Offre de contrat            | Ce que propose un producteur pour une saison (période, produits, prix, fréquences et échéanciers possibles)  |
| Commande groupée            | Commande ponctuelle, avec une date d'ouverture et une date de clôture, livrée lors d'une distribution donnée |
| Distribution                | Une date de remise des paniers (n°, date, lieu, horaires, producteurs présents)                              |
| Permanence                  | Participation d'un adhérent à l'organisation d'une distribution (installation, distribution, nettoyage)      |
| Responsable de distribution | Personne qui coordonne une distribution et envoie le rappel aux permanents                                   |
| Échéance                    | Un règlement prévu (montant, mois d'encaissement, mode chèque ou virement, bénéficiaire)                     |
| Pointage                    | Le fait de marquer une échéance comme reçue ou encaissée                                                     |
| Feuille d'émargement        | Liste imprimable « adhérent / quantité / signature », une par produit et par distribution                    |

## 3. Rôles et droits

Les rôles se cumulent : un référent est aussi adhérent, et un administrateur a tous les droits.

| Fonction                                               | Visiteur | Adhérent    | Producteur             | Référent            | Admin      |
|--------------------------------------------------------|----------|-------------|------------------------|---------------------|------------|
| Voir la vitrine, les producteurs, le calendrier public | ✔        | ✔           | ✔                      | ✔                   | ✔          |
| Formulaire de contact, demande d'adhésion              | ✔        | ✔           | ✔                      | ✔                   | ✔          |
| Gérer son compte                                       |          | ✔           | ✔                      | ✔                   | ✔          |
| Souscrire des contrats, commander en commande groupée  |          | ✔           |                        | ✔                   | ✔          |
| Voir ses commandes, son calendrier, ses échéances      |          | ✔           |                        | ✔                   | ✔          |
| S'inscrire aux permanences                             |          | ✔           |                        | ✔                   | ✔          |
| Voir le pointage de ses propres paiements              |          | ✔ (lecture) |                        | ✔                   | ✔          |
| Voir les récapitulatifs de commandes qui le concernent |          |             | ✔ (les siennes)        | ✔ (ses producteurs) | ✔          |
| Pointer les paiements                                  |          |             | ✔ (les siens)          | ✔ (ses producteurs) | ✔          |
| Créer ou modifier une page producteur et ses produits  |          |             | (à décider : sa fiche) | ✔ (ses producteurs) | ✔ (tous)   |
| Créer ou gérer une commande groupée                    |          |             |                        | ✔ (les siennes)     | ✔ (toutes) |
| Inscrire un adhérent à une permanence                  |          |             |                        | ✔                   | ✔          |
| Gérer les distributions et valider leur clôture        |          |             |                        |                     | ✔          |
| Modifier les pages d'information et la page d'accueil  |          |             |                        |                     | ✔          |
| Gérer les comptes et les rôles, modérer, paramètres    |          |             |                        |                     | ✔          |
| Envoyer des e-mails groupés (lettre d'information)     |          |             |                        | (à décider)         | ✔          |

Règle par défaut : un référent modifie **les producteurs et commandes groupées qu'il a créés ou qui lui sont attribués**. L'administrateur peut attribuer plusieurs référents à un même producteur.

## 4. Partie publique (vitrine)

| Page                      | Contenu                                                                                                                  | Modifiable par |
|---------------------------|--------------------------------------------------------------------------------------------------------------------------|----------------|
| Accueil                   | Accroche, prochaine distribution, actualités et période d'adhésion, commande groupée ouverte (titre), bouton « Adhérer » | Admin          |
| L'AMAP                    | Fusion de « Qu'est-ce qu'une AMAP » et « Notre fonctionnement » + charte et code de bonne conduite en PDF                | Admin          |
| Nos producteurs           | Liste en vignettes, filtrable par type de produit → fiche producteur générée automatiquement                             | Référents      |
| Fiche producteur          | Présentation, photos, produits, offres de contrat (sans les prix si on le souhaite), liens, labels                       | Référents      |
| Calendrier                | Dates des distributions et des prochaines permanences d'adhésion (pour venir rencontrer l'AMAP)                          | Admin          |
| Adhérer                   | Étapes, cotisation, dates des permanences d'adhésion, paiement en ligne de la cotisation, création de compte             | Admin          |
| Contact / Nous rencontrer | Formulaire de contact, adresse postale, lieu de distribution (carte), prochaines permanences                             | Admin          |
| Pages légales             | Mentions légales, politique de confidentialité, gestion des cookies (si nécessaire)                                      | Admin          |

### 4.1 Formulaire de contact

-   Champs : Nom, Prénom, E-mail, « Je suis » (adhérent·e / producteur·rice / personne intéressée par l'AMAP — formulation à affiner), Sujet (liste déroulante), Message.
-   Les sujets proposés sont : Adhésion, Commandes et contrats, Permanences, Devenir producteur partenaire, Site internet, Autre. Chaque sujet est **envoyé à l'adresse ou à l'alias adapté** (§13).
-   Anti-spam : champ caché piège et limitation du nombre d'envois, sans captcha intrusif. La vérification de l'adresse e-mail est inutile pour un simple message. Un accusé de réception est envoyé à l'expéditeur.

## 5. Espace adhérent

### 5.1 Tableau de bord

Il affiche : la prochaine distribution (ce que je récupère, si je suis de permanence), les commandes groupées ouvertes, mes échéances à venir et une alerte si mon adhésion est à renouveler.

### 5.2 Compte et adhésion

-   Création du compte (e-mail et mot de passe), validation de l'adresse e-mail, mot de passe oublié.
-   Profil : nom, prénom, adresse, téléphones, **consentement au partage du téléphone** avec les autres adhérents (pour les remplacements de permanence).
-   Adhésion annuelle : montant paramétrable (20 € en 2026). Paiement en ligne (HelloAsso) ou déclaration « chèque » ou « virement », avec pointage par l'admin.
-   Les fonctions de commande ne sont disponibles **qu'une fois l'adhésion validée**.

### 5.3 Parcours « commande standard » (souscrire un contrat)

1.  Tableau de bord → « Mes contrats » → liste des **offres de contrat ouvertes**, par producteur.
2.  Pour chaque producteur : liste des produits et de leurs prix. Pour chaque produit, l'adhérent choisit :
    -   la **quantité** ;
    -   la **fréquence**, parmi les choix autorisés par le producteur : toutes les distributions, 1 sur 2 (série A ou série B), ou dates choisies à la main ;
    -   le **calendrier personnel** et le **panier** se mettent à jour en direct, avec une icône par produit sur chaque date.
3.  « Voir mon panier » : récapitulatif **par producteur**, avec le nombre de livraisons, le total, et le **choix de l'échéancier** (par exemple 1, 3 ou 6 chèques, selon ce que le producteur autorise) et du mode (chèque ou virement). L'écran affiche l'ordre du chèque ou le RIB, ainsi que les mois d'encaissement.
4.  Validation → **e-mail de confirmation** avec le récapitulatif, l'échéancier et le contrat du producteur en PDF.
5.  Après validation, l'adhérent peut modifier son contrat jusqu'à la date de clôture des souscriptions. Après cette date, seul le référent peut le modifier.

### 5.4 Parcours « commande groupée »

1.  Tableau de bord → « Commandes groupées » → commandes ouvertes (souvent une seule), avec la date de clôture et la date de livraison.
2.  L'adhérent sélectionne des produits et des quantités. Le panier et le calendrier se mettent à jour.
3.  « Voir mon panier » : récapitulatif par producteur, total, modalités de paiement indiquées par le référent.
4.  Validation → e-mail de confirmation. L'adhérent peut modifier sa commande jusqu'à la clôture.

### 5.5 Calendrier et permanences

-   Calendrier personnel : mes livraisons par date (contrats et commandes groupées) et mes permanences.
-   Calendrier général des distributions, avec les producteurs présents à chaque date.
-   **Inscription aux permanences** (règles du code de bonne conduite) :
    -   les adhérents avec contrats choisissent **3 vœux de dates** et l'AMAP en retient 1 ou 2 ;
    -   les adhérents qui ne font que des commandes groupées choisissent 1 date ;
    -   chaque distribution a une équipe de 5 à 6 personnes, plus un **responsable de distribution** ;
    -   en cas d'empêchement : échange avec un autre adhérent ou remplaçant extérieur (nom, téléphone, e-mail), saisi sur le site ;
    -   une absence non remplacée est consignée (liste des « adhérents sollicitables ») ;
    -   le référent ou l'admin peut inscrire ou déplacer un adhérent.
-   Rappels automatiques (§11).

### 5.6 Mes paiements

Il affiche la liste de mes échéances par producteur et leur statut de pointage (en lecture seule) : attendu, reçu ou encaissé.

## 6. Espace référent (back-office)

### 6.1 Ajouter ou modifier un producteur

Le bouton « Ajouter un producteur » ouvre un formulaire :

-   nom de la ferme, nom des producteurs, commune et adresse, téléphone, e-mail, site web et réseaux sociaux, SIRET (pour le contrat) ;
-   types de produits (cases à cocher), labels (Bio, Demeter, Nature & Progrès…) ;
-   présentation (éditeur de texte simple), photos (glisser-déposer, recadrage automatique), documents PDF (gamme, tarifs, conseils) ;
-   mode de participation : contrat, commandes groupées ou les deux ;
-   publication : brouillon ou publié.

À l'enregistrement, **la fiche publique est générée automatiquement** et le producteur apparaît dans la liste et dans le « magasin ». Si le producteur a une adresse e-mail, le référent peut l'inviter à créer son compte producteur.

### 6.2 Produits et offres de contrat (le « magasin » standard)

-   **Produits** : nom, description, photo, unité (panier, pièce, kg, litre…), prix unitaire, actif ou inactif. On peut **dupliquer** un produit.
-   **Offre de contrat** (par producteur et par saison) :
    -   période (par exemple du 6 juin au 27 novembre) et distributions concernées, cochées automatiquement selon la présence du producteur ;
    -   produits proposés et prix ;
    -   fréquences autorisées ;
    -   échéanciers autorisés (nombre de chèques et mois d'encaissement, par exemple 3 chèques en mars, juin et septembre, ou 6 chèques de juin à novembre) ;
    -   ordre des chèques et RIB pour les virements ;
    -   texte du contrat (articles), à partir d'un modèle ;
    -   **date et heure d'ouverture et de clôture des souscriptions**.
-   Le bouton « Dupliquer l'offre de l'an dernier » permet de préparer la saison suivante.

### 6.3 Créer une commande groupée

Formulaire : titre, producteur ou fournisseur, description, produits (nom, conditionnement, prix, quantité maximale par adhérent ou au total), **date et heure d'ouverture et de clôture**, distribution de livraison, modalités de paiement. Ouverture et fermeture automatiques. Option : **e-mail d'annonce aux adhérents** à l'ouverture. Le bouton « Dupliquer » permet de relancer une commande d'une année sur l'autre.

### 6.4 Suivi et pointage

-   Liste des contrats et des commandes de ses producteurs, avec filtres et export Excel.
-   **Outil de pointage** : pour chaque adhérent et chaque échéance, cases « reçu » et « encaissé » (avec date et n° de chèque facultatif). L'adhérent voit le statut.
-   Documents de distribution (§10) consultables et téléchargeables.

## 7. Espace producteur

-   Calendrier des distributions où il est attendu, avec les quantités à préparer.
-   **Récapitulatif des commandes** passées chez lui : par distribution, par produit et par adhérent. Il peut le consulter en ligne, le télécharger en PDF ou en Excel, et le reçoit par e-mail.
-   **Outil de pointage** de ses paiements (mêmes fonctions que pour le référent, limitées à ses adhérents).
-   (À décider) Modification de sa propre fiche et de ses produits, avec validation par le référent.

## 8. Administration

-   Accès complet à tous les contenus et données.
-   Pages d'information et page d'accueil : modification par blocs simples (texte, image, bouton, encadré « actualité »).
-   Comptes : création, rôles, attribution des référents aux producteurs, désactivation. Modération (désactiver un compte, masquer un contenu, journal des modifications).
-   **Distributions** : création du calendrier annuel (génération automatique un samedi sur deux en semaine impaire, puis ajustements), lieux, horaires, producteurs présents par date.
-   **Clôture d'une distribution** : l'admin valide les quantités, puis déclenche l'envoi des récapitulatifs aux producteurs.
-   Gestion des « magasins » : vue d'ensemble des offres et des commandes groupées, avec leurs dates d'ouverture et de clôture.
-   Adhésions : liste, pointage des cotisations, export.
-   Paramètres : nom de l'AMAP, logo, couleurs, adresses, montant de la cotisation, adresses e-mail de destination (pour la réutilisation par d'autres AMAP).
-   E-mails groupés : lettre d'information et annonces, envoyées à tous les adhérents ou à un groupe (par exemple « adhérents ayant un contrat avec X »), avec désinscription possible.

## 9. Règles de gestion des distributions

-   Une distribution a un n°, une date, un lieu, des horaires, la liste des producteurs présents et des besoins en permanents (5 à 6 personnes).
-   Les distributions de l'AMAP ont lieu les **samedis des semaines calendaires impaires**. Un producteur peut avoir des **livraisons hors distribution AMAP**, dans un autre lieu et à un autre horaire (exemple : le Corti des Aravis livre aussi le vendredi des semaines paires aux Villards). Il faut donc pouvoir gérer **plusieurs lieux et créneaux**.
-   Une livraison « 1 sur 2 » s'appuie sur une série A ou une série B de distributions.
-   En cas d'absence de l'adhérent : pas de remboursement ni de rattrapage (règle du contrat). Le site peut proposer « Je serai absent, prévenir le producteur avant vendredi 12h ».

## 10. Documents générés (PDF)

| Document                          | Contenu                                                                   | Pour qui                    | Comment                                     |
|-----------------------------------|---------------------------------------------------------------------------|-----------------------------|---------------------------------------------|
| Récapitulatif producteur          | Par distribution : total par produit, puis détail par adhérent            | Producteur, référent        | Espace en ligne + PDF + e-mail à la clôture |
| Feuille d'émargement              | Une page par produit : Nom de l'adhérent / Quantité / Signature           | Responsable de distribution | PDF à imprimer                              |
| Récapitulatif de commande groupée | Total par produit, détail par adhérent, montant                           | Producteur, référent        | Espace + PDF + e-mail                       |
| Émargement de commande groupée    | Comme ci-dessus                                                           | Référent                    | Espace + e-mail                             |
| Contrat d'engagement              | Contrat du producteur pré-rempli avec les données de l'adhérent           | Adhérent, producteur        | PDF joint à la confirmation                 |
| Planning annuel                   | Tableau des distributions × producteurs (équivalent du « Planning 2026 ») | Tous                        | Page web + PDF                              |
| Planning des permanences          | Par distribution : équipe, responsable, téléphones (si consentement)      | Adhérents                   | Page web + PDF                              |

→ *Les modèles PDF existants (émargement, récapitulatifs) seront fournis pour être reproduits.*

## 11. E-mails automatiques (V1)

| Déclencheur                                     | Destinataire                            | Contenu                                   |
|-------------------------------------------------|-----------------------------------------|-------------------------------------------|
| Création de compte                              | Adhérent                                | Validation de l'adresse e-mail            |
| Message via le formulaire de contact            | Alias concerné + expéditeur             | Message, accusé de réception              |
| Validation d'un contrat ou d'une commande       | Adhérent (copie au référent ?)          | Récapitulatif, échéancier, contrat PDF    |
| Ouverture d'une commande groupée                | Adhérents (option)                      | Annonce et lien                           |
| J-2 avant la clôture d'une commande groupée     | Adhérents n'ayant pas commandé (option) | Rappel                                    |
| Clôture d'une distribution, validée par l'admin | Producteurs concernés                   | Récapitulatif PDF                         |
| J-5 avant une permanence                        | Permanents + responsable                | Rappel (équipe, horaires 8h30, consignes) |
| J-3 avant une distribution (option)             | Adhérents concernés                     | « Samedi vous récupérez : … »             |
| Échéance de paiement à venir (option)           | Adhérent                                | Rappel                                    |
| Renouvellement de l'adhésion (novembre)         | Adhérents                               | Invitation à réadhérer                    |

## 12. Exigences non fonctionnelles

-   **Mobile d'abord** : la plupart des adhérents utiliseront un téléphone.
-   **Simplicité** : chaque rôle ne voit que ses menus. Libellés en français courant, sans jargon technique.
-   **Accessibilité** : contrastes, textes alternatifs, navigation au clavier (objectif : bonnes pratiques RGAA).
-   **RGPD** : l'AMAP est responsable du traitement et Infomaniak est sous-traitant. Les données sont hébergées en Suisse. Il faut une politique de confidentialité, un consentement pour le partage du téléphone, la possibilité de supprimer son compte, et ne collecter que les données nécessaires. Pas de traceur publicitaire.
-   **Sécurité** : mots de passe chiffrés, HTTPS, droits vérifiés côté serveur, mises à jour régulières, journal des actions sensibles.
-   **Sauvegardes** : sauvegarde automatique quotidienne de la base de données et des fichiers, avec un test de restauration.
-   **Qualité** : tests automatiques, environnement de test séparé, déploiement automatisé depuis GitHub.
-   **Portabilité** : aucun élément propre au Panier des Aravis dans le code. Tout passe par les paramètres et une installation documentée.
-   **Performance** : pages légères (pas de Wix), images optimisées.

## 13. Hébergement, e-mail et budget

-   Hébergement Web + Mail Infomaniak (environ 7,25 €/mois, 5 adresses e-mail incluses) : il contient le site, la base MySQL/MariaDB et les e-mails.
-   Nom de domaine `lepanierdesaravis.fr` (non inclus, environ 10 à 15 €/an), à transférer chez Infomaniak **avant son expiration le 13/02/2027**.
-   Adresses e-mail : quelques vraies boîtes et des **alias**. Proposition : `contact@` (boîte du bureau), `ne-pas-repondre@` (envois automatiques du site), plus les alias `adhesion@`, `permanences@`, `producteurs@`, `tresorerie@` et `webmaster@`, redirigés vers les bonnes personnes.
-   Budget visé : environ 100 €/an, contre environ 168 €/an aujourd'hui (337 € pour 2 ans).

## 14. Organisation GitHub

-   Une **organisation GitHub** « lepanierdesaravis » (gratuite), dont **au moins deux membres du bureau sont propriétaires**. Le dépôt appartient à l'association et non à une personne.
-   Le dépôt est public, sous licence libre (AGPL-3.0 proposée), avec les tickets, un tableau de projet, des modèles de tickets (bug, demande d'amélioration) et une documentation d'installation pour d'autres AMAP.
-   Les secrets (mots de passe, clés) ne sont **jamais** dans le dépôt.

## 15. Évolutions futures (après la V1)

-   Scénarios d'e-mail marketing (relances, lettre d'information automatisée).
-   Rappel au producteur ou au référent après une interruption (plus de 2 distributions sautées). Rappels pour les commandes groupées.
-   Aide à la prise en main : infobulles et tutoriel à la première connexion, selon le rôle. Vidéos pédagogiques.
-   Blog et recettes.
-   Paiement en ligne des contrats (si un jour souhaité).
-   Liste d'attente pour les contrats complets.

## 16. Points à préciser

1.  Le producteur modifie-t-il lui-même sa fiche et ses produits, ou passe-t-il toujours par son référent ?
2.  Plusieurs référents peuvent-ils gérer un même producteur ?
3.  Que recouvrent les produits « Engagement de 50 € / 100 € / 1000 € pour l'année » de la boutique actuelle : un crédit à utiliser, ou un contrat au montant ?
4.  Les contrats ont-ils tous les mêmes fréquences (toutes / 1 sur 2), ou faut-il des choix de dates libres ?
5.  Après la clôture des souscriptions, l'adhérent peut-il encore modifier son contrat ?
6.  Faut-il gérer les livraisons hors distribution AMAP (autre lieu ou autre jour) dès la V1 ?
7.  Qui envoie les e-mails groupés : uniquement l'admin, ou aussi les référents ?
8.  Permanences : confirmez-vous le fonctionnement « 3 vœux, puis l'AMAP en retient 1 ou 2 » ? Qui fait cette sélection ?
9.  Les prix doivent-ils être visibles publiquement sur les fiches producteurs ?
10. Formulation pour le « visiteur » dans le champ « Je suis ».
