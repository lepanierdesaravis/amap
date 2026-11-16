# Suivi de conformité au cahier des charges

*État au 16/11/2026, sur le prototype (commit `ad05ad9`, plus les retours du 16/11/2026 : CON-04, ADH-23 à ADH-26, REF-20 à REF-27, PRO-05, MEL-11 à MEL-13). Il est établi en relisant le code et les tests, pas encore par une recette complète.*

Ce tableau dit, pour chaque exigence du [cahier des charges](02-cahier-des-charges.md), si le site la remplit. On le met à jour à chaque modification du cahier des charges et à chaque fonctionnalité fusionnée.

| État | Signification |
|------|---------------|
| ✅ | Fait : la fonctionnalité existe et fait ce que demande l'exigence |
| 🟡 | Partiel : une partie existe, la colonne « Remarques » dit ce qui manque |
| ❌ | À faire |
| ⏸ | En attente d'une décision (§16) ou hors du site (organisation, hébergement) |

La colonne **Ticket** reçoit le numéro du ticket GitHub (`#12`) quand quelqu'un prend en charge le travail restant.

## Résumé

Sur 137 exigences : **75 faites**, **37 partielles**, **20 à faire**, **5 en attente**. Les évolutions futures (EVO) ne sont pas comptées.

Ce qui manque surtout :
- **Les permanences** : inscription, équipes, échanges, rappels, planning (ADH-17 à ADH-21, DOC-07, MEL-07). C'est le plus gros bloc, prévu en phase 2.
- **Les e-mails automatiques** : validation de l'adresse et rappels (MEL-01, MEL-05, MEL-07 à MEL-10). Les annonces d'ouverture sont faites ; elles demandent une tâche planifiée et une file d'attente chez l'hébergeur.
- **Le contrat d'engagement en PDF** joint à la confirmation (DOC-05).
- **Les exports Excel** (REF-17, PRO-02, ADM-08).
- **L'adhésion en ligne** avec HelloAsso (ADH-04, PUB-06).
- **L'administration** : e-mails groupés, modération et journal, paramètres à l'écran (ADM-04, ADM-09, ADM-10).
- **L'exploitation** : sauvegardes, déploiement automatique, licence (NF-06, NF-07, GIT-02).

## 3. Rôles et droits

| N° | Exigence | État | Remarques | Ticket |
|----|----------|------|-----------|--------|
| ROL-01 | Vitrine visible par tous | ✅ | Test `AccessTest::les_pages_publiques_s_affichent` | |
| ROL-02 | Contact et demande d'adhésion | ✅ | `/contact`, `/inscription` | |
| ROL-03 | Gérer son compte | ✅ | `/mon-espace/compte` | |
| ROL-04 | Souscrire, commander | ✅ | Test `OrderServiceTest` | |
| ROL-05 | Voir ses commandes, calendrier, échéances | ✅ | `/mon-espace` | |
| ROL-06 | S'inscrire aux permanences | ❌ | Les permanences n'existent pas encore (ADH-17 à ADH-21) | |
| ROL-07 | Voir le pointage de ses paiements | ✅ | `/mon-espace/paiements`, en lecture seule | |
| ROL-08 | Récapitulatifs (producteur, référent) | ✅ | Espace producteur et back-office | |
| ROL-09 | Pointer les paiements | ✅ | Producteur : ses échéances seulement (`InstallmentPolicy`) | |
| ROL-10 | Fiche producteur | ✅ | Référent et admin. Le producteur passe par son référent (choix du 15/11/2026, voir Q-01) | |
| ROL-11 | Commandes groupées | ✅ | Référent : les siennes (`GroupPurchasePolicy`) | |
| ROL-12 | Inscrire un adhérent à une permanence | ❌ | Dépend des permanences | |
| ROL-13 | Gérer les distributions et leur clôture | ✅ | Le référent consulte, seul l'admin modifie (`DistributionPolicy`) | |
| ROL-14 | Modifier les pages d'information | ✅ | Back-office « Pages », admin seulement | |
| ROL-15 | Comptes, rôles, modération, paramètres | 🟡 | Comptes et rôles : oui. Modération et paramètres : voir ADM-04 et ADM-09 | |
| ROL-16 | E-mails groupés | ❌ | Voir ADM-10 et Q-07 | |
| ROL-17 | Référent des producteurs qu'il a créés ou qui lui sont attribués | 🟡 | Le créateur devient référent. Pas de réattribution, et un seul référent par producteur (Q-02) | |

## 4. Partie publique

| N° | Exigence | État | Remarques | Ticket |
|----|----------|------|-----------|--------|
| PUB-01 | Accueil | 🟡 | Accroche, prochaine distribution, commandes ouvertes, bouton « Adhérer ». Pas de période d'adhésion ni d'encadré « actualité » gérés à part (texte de la page seulement) | |
| PUB-02 | L'AMAP + charte et code de bonne conduite | ✅ | Les deux PDF sont en ligne | |
| PUB-03 | Nos producteurs, filtrable | ✅ | Filtre par type de produit | |
| PUB-04 | Fiche producteur | 🟡 | Tout est affiché. Produits groupés par type ; un clic ouvre une fenêtre avec la photo et le texte complet. Produits et tarifs repris de la boutique Wix (`docs/05-import-boutique.md`). L'option « masquer les prix » n'existe pas (Q-09) | |
| PUB-05 | Calendrier | 🟡 | Distributions et producteurs présents. Pas les permanences d'adhésion | |
| PUB-06 | Adhérer | 🟡 | Étapes, cotisation, création de compte. Pas de dates de permanence ni de paiement en ligne | |
| PUB-07 | Contact / Nous rencontrer | 🟡 | Formulaire, adresses, prochaines distributions. Pas de carte | |
| PUB-08 | Pages légales | 🟡 | Mentions légales et confidentialité existent, avec un texte provisoire à faire valider | |
| CON-01 | Champs du formulaire | ✅ | Formulation « Je suis » à valider (Q-10) | |
| CON-02 | Routage par sujet | ✅ | Adresses dans `config/amap.php`. Aucun test automatique | |
| CON-03 | Anti-spam et accusé de réception | ✅ | Champ piège, 5 envois par 10 minutes, accusé de réception. Aucun test automatique | |
| CON-04 | Lien vers les échanges entre adhérents | ✅ | Encadré affiché quand on choisit « Adhérent·e » | |

## 5. Espace adhérent

| N° | Exigence | État | Remarques | Ticket |
|----|----------|------|-----------|--------|
| ADH-01 | Tableau de bord | 🟡 | Prochaine distribution, commandes, échéances, alerte si l'adhésion n'est pas réglée. Pas de permanence ni de rappel de renouvellement | |
| ADH-02 | Création de compte, validation e-mail, mot de passe oublié | 🟡 | La validation de l'adresse e-mail manque | |
| ADH-03 | Profil et consentement téléphone | ✅ | | |
| ADH-04 | Adhésion annuelle | 🟡 | Montant paramétrable et pointage par l'admin. Pas de HelloAsso ; l'adhérent ne peut pas déclarer son mode de paiement | |
| ADH-05 | Commandes réservées aux adhérents à jour | ✅ | Test `il_faut_une_adhesion_reglee_et_une_offre_ouverte` | |
| ADH-06 | Liste des offres de contrat ouvertes | ✅ | `/mon-espace/contrats` | |
| ADH-07 | Quantité, fréquence, calendrier en direct | ✅ | Toutes, 1 sur 2 (série A/B), dates au choix. Test `la_frequence_choisit_les_dates_de_livraison` | |
| ADH-08 | Panier, échéancier, mode de paiement | 🟡 | Échéancier et mois d'encaissement : oui. Ordre du chèque affiché ; le RIB n'est pas à l'écran (le texte renvoie à l'e-mail) | |
| ADH-09 | E-mail de confirmation avec contrat PDF | 🟡 | L'e-mail part, sans le contrat en PDF (DOC-05) | |
| ADH-10 | Modification jusqu'à la clôture | 🟡 | Modification par l'adhérent jusqu'à la clôture (test `modifier_un_contrat_remplace_les_livraisons`). Le référent ne peut pas encore modifier un contrat | |
| ADH-11 | Liste des commandes groupées ouvertes | ✅ | | |
| ADH-12 | Choix des produits et quantités | ✅ | Maximum par adhérent respecté (test) | |
| ADH-13 | Récapitulatif et modalités de paiement | ✅ | | |
| ADH-14 | Confirmation et modification jusqu'à la clôture | ✅ | Test `vider_une_commande_groupee_la_supprime` | |
| ADH-15 | Calendrier personnel | 🟡 | Livraisons : oui. Permanences : non | |
| ADH-16 | Calendrier général avec producteurs | ✅ | `/calendrier` | |
| ADH-17 | Vœux de permanence | ❌ | | |
| ADH-18 | Équipe et responsable de distribution | ❌ | | |
| ADH-19 | Échanges et remplaçants | ❌ | | |
| ADH-20 | Absences consignées | ❌ | | |
| ADH-21 | Inscription par un référent | ❌ | | |
| ADH-22 | Mes paiements | ✅ | Attendu, reçu, encaissé | |
| ADH-23 | Échanges entre adhérents | 🟡 | `/mon-espace/echanges` : à tous (3 par jour au plus), au référent d'un producteur, au bureau. Désinscription dans « Mon compte ». Pas encore d'écran de modération dans le back-office. Tests `FeedbackFeaturesTest` | |
| ADH-24 | Calendrier de souscription avec les autres commandes | ✅ | Icônes du contrat en grand, autres en petit, infobulle au survol ou au toucher | |
| ADH-25 | Vue d'ensemble dans « Mon calendrier » | ✅ | Un mois à la fois avec des flèches (ouvre sur le premier mois avec une livraison), bouton « Agrandir » : fenêtre avec tous les mois, jusqu'à la dernière livraison (un an au plus). Liste date par date en dessous | |
| ADH-26 | Commande liée à un engagement | ✅ | Engagés : déduit, solde estimé affiché. Non engagés : réglé sur place. Test `une_commande_liee_a_un_engagement…` | |

## 6. Espace référent

| N° | Exigence | État | Remarques | Ticket |
|----|----------|------|-----------|--------|
| REF-01 | Formulaire producteur | ✅ | Tous les champs, éditeur de photo intégré | |
| REF-02 | Fiche publique générée | ✅ | | |
| REF-03 | Inviter le producteur à créer son compte | 🟡 | On rattache un compte existant. Pas d'e-mail d'invitation | |
| REF-04 | Produits, duplication | ✅ | | |
| REF-20 | Banque d'icônes, couleur, type de produit | ✅ | 76 icônes (game-icons.net, CC BY 3.0) dans `config/icones.php`, 10 couleurs, le sélecteur suit la couleur choisie. Une icône dans une couleur par producteur. Test `le_referent_choisit_une_icone_libre…` | |
| REF-21 | Photo du produit | ✅ | Fiche producteur et souscription. Le calendrier garde l'icône | |
| REF-22 | Montants d'engagement | ✅ | Produit « montant » : le prix vaut pour la saison. Test `un_montant_d_engagement_vaut_pour_la_saison` | |
| REF-05 | Période et distributions de l'offre | ✅ | Calculées d'après la présence du producteur | |
| REF-06 | Produits et prix de l'offre | ✅ | | |
| REF-07 | Fréquences autorisées | ✅ | | |
| REF-08 | Échéanciers autorisés | ✅ | Nombre de règlements et mois. Test `l_echeancier_suit_les_mois_de_l_offre` | |
| REF-09 | Ordre des chèques et RIB | ✅ | Sur la fiche producteur | |
| REF-10 | Texte du contrat à partir d'un modèle | 🟡 | Texte libre, pas de modèle | |
| REF-11 | Ouverture et clôture des souscriptions | ✅ | | |
| REF-12 | Dupliquer l'offre de l'an dernier | ✅ | Décalage d'un an, six mois ou trois mois | |
| REF-23 | Liste des adhérents pour le producteur | ✅ | PDF et CSV, dans le back-office et, si la case est cochée, dans l'espace producteur | |
| REF-25 | Contrats trimestriels | ✅ | Durée annuelle, semestrielle ou trimestrielle dans le formulaire | |
| REF-26 | Nombre de contrats limité | ✅ | Verrou en base contre les dépassements. Test `le_nombre_de_contrats_peut_etre_limite` | |
| REF-27 | Annonce de l'ouverture d'un contrat | ✅ | À tous ou aux contrats en cours avec le producteur (Q-11). Demande la tâche planifiée | |
| REF-13 | Formulaire de commande groupée | 🟡 | Tout, sauf la quantité maximale au total | |
| REF-14 | Ouverture et fermeture automatiques | ✅ | Calculées à partir des dates | |
| REF-15 | Annonce par e-mail à l'ouverture | ✅ | Automatique (`amap:annonces`, toutes les 5 minutes), plus le bouton « Annoncer aux adhérents ». Demande la tâche planifiée | |
| REF-16 | Dupliquer une commande groupée | ✅ | | |
| REF-24 | Commande liée à un engagement, répétée | ✅ | Action « Répéter à chaque distribution », mêmes horaires. Test `une_commande_se_repete…` | |
| REF-17 | Listes, filtres, export Excel | 🟡 | Listes et filtres : oui. Export Excel : non | |
| REF-18 | Outil de pointage | ✅ | Reçu, encaissé, n° de chèque, pointage groupé | |
| REF-19 | Documents de distribution | ✅ | Récapitulatif et émargement en PDF | |

## 7. Espace producteur

| N° | Exigence | État | Remarques | Ticket |
|----|----------|------|-----------|--------|
| PRO-01 | Calendrier et quantités à préparer | ✅ | Les 6 prochaines distributions | |
| PRO-02 | Récapitulatif en ligne, PDF, Excel, e-mail | 🟡 | En ligne, PDF et e-mail : oui. Excel : non | |
| PRO-03 | Pointage de ses paiements | ✅ | | |
| PRO-04 | Modifier sa fiche (à décider) | ⏸ | Q-01 : pour l'instant, il passe par son référent | |
| PRO-05 | Liste de ses adhérents engagés | ✅ | Si le référent l'autorise (REF-23) | |

## 8. Administration

| N° | Exigence | État | Remarques | Ticket |
|----|----------|------|-----------|--------|
| ADM-01 | Accès complet | ✅ | Test `l_administrateur_a_tous_les_droits` | |
| ADM-02 | Pages modifiables par blocs | 🟡 | Éditeur de texte riche, pas de blocs (image, bouton, encadré) | |
| ADM-03 | Comptes, rôles, référents, désactivation | 🟡 | Création et rôles : oui. Ni réattribution d'un référent, ni désactivation | |
| ADM-04 | Modération et journal des modifications | ❌ | | |
| ADM-05 | Calendrier annuel des distributions | ✅ | Bouton « Générer le calendrier », puis ajustements | |
| ADM-06 | Clôture et envoi des récapitulatifs | ✅ | Action « Clôturer et envoyer les récapitulatifs » | |
| ADM-07 | Vue d'ensemble des « magasins » | ✅ | Deux listes (offres, commandes groupées) avec leur état | |
| ADM-08 | Adhésions : liste, pointage, export | 🟡 | Liste et pointage dans la fiche du compte. Pas d'export | |
| ADM-09 | Paramètres de l'AMAP | 🟡 | Dans `config/amap.php` (modifiable par un développeur), pas à l'écran. Logo et couleurs sont dans le thème | |
| ADM-10 | E-mails groupés | ❌ | Q-07 | |

## 9. Distributions

| N° | Exigence | État | Remarques | Ticket |
|----|----------|------|-----------|--------|
| DIS-01 | Contenu d'une distribution | 🟡 | Date, lieu, horaires, producteurs. Pas de besoins en permanents | |
| DIS-02 | Semaines impaires, plusieurs lieux et créneaux | 🟡 | Génération en semaines impaires : oui. Une seule distribution par date, pas de livraison hors AMAP (Q-06) | |
| DIS-03 | Séries A et B | ✅ | | |
| DIS-04 | « Je serai absent » | ❌ | Facultatif | |

## 10. Documents PDF

| N° | Exigence | État | Remarques | Ticket |
|----|----------|------|-----------|--------|
| DOC-01 | Récapitulatif producteur | ✅ | En ligne, PDF, e-mail à la clôture | |
| DOC-02 | Feuille d'émargement | ✅ | À comparer aux modèles de l'AMAP quand ils seront fournis | |
| DOC-03 | Récapitulatif de commande groupée | ✅ | | |
| DOC-04 | Émargement de commande groupée | ✅ | | |
| DOC-05 | Contrat d'engagement pré-rempli | ❌ | | |
| DOC-06 | Planning annuel | 🟡 | Page web : oui. PDF : non | |
| DOC-07 | Planning des permanences | ❌ | Dépend des permanences | |

## 11. E-mails automatiques

Les annonces (MEL-04, MEL-11, MEL-12) et les rappels (MEL-05, MEL-07 à MEL-10) demandent la tâche planifiée `* * * * * php artisan schedule:run`, et l'envoi des e-mails groupés une file d'attente (`queue:work`). À prévoir chez l'hébergeur.

| N° | Exigence | État | Remarques | Ticket |
|----|----------|------|-----------|--------|
| MEL-01 | Validation de l'adresse e-mail | ❌ | Voir ADH-02 | |
| MEL-02 | Formulaire de contact | ✅ | | |
| MEL-03 | Confirmation de contrat ou de commande | 🟡 | Envoyé à l'adhérent. Sans copie au référent, sans contrat PDF | |
| MEL-04 | Ouverture d'une commande groupée | ✅ | Automatique si l'option est cochée (REF-15) | |
| MEL-05 | J-2 avant clôture d'une commande groupée | ❌ | | |
| MEL-06 | Clôture d'une distribution | ✅ | PDF joint | |
| MEL-07 | J-5 avant une permanence | ❌ | | |
| MEL-08 | J-3 avant une distribution | ❌ | Facultatif | |
| MEL-09 | Échéance de paiement à venir | ❌ | Facultatif | |
| MEL-10 | Renouvellement de l'adhésion | ❌ | | |
| MEL-11 | Ouverture des souscriptions d'un contrat | ✅ | REF-27 | |
| MEL-12 | Ouverture d'une commande liée | ✅ | Aux adhérents engagés seulement (Q-12) | |
| MEL-13 | Message d'échange entre adhérents | ✅ | « Répondre » écrit à l'expéditeur | |

## 12. Exigences non fonctionnelles

| N° | Exigence | État | Remarques | Ticket |
|----|----------|------|-----------|--------|
| NF-01 | Mobile d'abord | 🟡 | Écrans conçus pour le téléphone. À vérifier pendant la recette | |
| NF-02 | Simplicité, menus par rôle | ✅ | | |
| NF-03 | Accessibilité | 🟡 | Pas encore audité | |
| NF-04 | RGPD | 🟡 | Politique de confidentialité, consentement téléphone, aucun traceur. Suppression de son compte par l'adhérent : non | |
| NF-05 | Sécurité | 🟡 | Mots de passe chiffrés, droits testés côté serveur, HTTPS sur la recette. Pas de journal des actions sensibles | |
| NF-06 | Sauvegardes | ❌ | À mettre en place chez l'hébergeur | |
| NF-07 | Tests, environnement de test, déploiement automatisé | 🟡 | 30 tests automatiques, site de recette. Déploiement manuel, pas d'intégration continue | |
| NF-08 | Portabilité | 🟡 | Nom, adresses, cotisation et sujets dans `config/amap.php`. Logo, couleurs et textes des pages restent propres à l'AMAP. Installation documentée pour le développement seulement | |
| NF-09 | Performance | ✅ | Pages légères. Optimisation automatique des images à faire | |

## 13 et 14. Hébergement et GitHub

| N° | Exigence | État | Remarques | Ticket |
|----|----------|------|-----------|--------|
| HEB-01 | Hébergement Infomaniak | ⏸ | Pas encore souscrit. Recette provisoire sur un Raspberry Pi | |
| HEB-02 | Transfert du domaine avant le 13/02/2027 | ⏸ | Prévu en janvier 2027 | |
| HEB-03 | Boîtes et alias e-mail | ⏸ | Les alias sont déjà dans `config/amap.php` | |
| HEB-04 | Budget | ⏸ | | |
| GIT-01 | Organisation GitHub de l'association | 🟡 | Organisation créée. Deux propriétaires du bureau : à vérifier | |
| GIT-02 | Dépôt public, licence, tickets, modèles | 🟡 | Dépôt public, modèles de tickets et étiquettes. Pas de fichier de licence ni de tableau de projet | |
| GIT-03 | Aucun secret dans le dépôt | ✅ | `.env` ignoré par git | |

## 15. Évolutions futures

EVO-01 à EVO-06 : ⏸ hors V1.

## 16. Points à préciser

| N° | Question | État | Ce que fait le prototype |
|----|----------|------|--------------------------|
| Q-01 | Le producteur modifie-t-il sa fiche ? | Tranché le 15/11/2026 : il passe par son référent | Pas de modification par le producteur |
| Q-02 | Plusieurs référents par producteur ? | Ouvert | Un seul référent |
| Q-03 | Produits « Engagement de 50 / 100 / 1000 € » | Tranché le 16/11/2026 : montant comme un produit, ou crédit pour des commandes liées | REF-22, ADH-26 |
| Q-04 | Fréquences des contrats | Ouvert | Toutes, 1 sur 2 et dates au choix, au choix du référent |
| Q-05 | Modifier son contrat après la clôture ? | Ouvert | Non |
| Q-06 | Livraisons hors distribution AMAP en V1 ? | Ouvert | Non gérées |
| Q-07 | Qui envoie les e-mails groupés ? | Ouvert | Fonction absente |
| Q-08 | Permanences « 3 vœux » : qui sélectionne ? | Ouvert | Fonction absente |
| Q-09 | Prix visibles publiquement ? | Ouvert | Visibles |
| Q-10 | Formulation « Je suis » | Ouvert | « Je découvre l'AMAP » |
| Q-11 | Annonce d'un contrat : à qui par défaut ? | Ouvert | Au choix du référent, pour chaque offre |
| Q-12 | Commandes liées : prévenir aussi les non-engagés ? | Ouvert | Seuls les engagés reçoivent l'e-mail |
