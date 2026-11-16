# Import des produits de la boutique actuelle

*Relevé du 16/11/2026 sur https://www.lepanierdesaravis.fr/shop : 252 fiches produits. Ce document sert à vérifier, avec les référents et les producteurs, les produits repris dans le nouveau site.*

## Méthode

- Chaque fiche de la boutique Wix indique son producteur par une **catégorie**. Les 144 fiches classées sont reprises dans la **vitrine** du producteur (onglet « Produits proposés » de sa page), avec un tarif à l'unité ou au poids.
- Sur la boutique, le prix d'un produit sous contrat est le **total de la saison** (prix d'une livraison × nombre de distributions). Le nouveau site affiche le prix d'**une livraison** : il est obtenu en divisant par le nombre de distributions, indiqué dans la fiche ou déduit (voir le tableau).
- La **photo** de chaque fiche (version 400 × 400) remplace l'icône dans la vitrine et sur l'écran de souscription (`database/seeders/media/produits`). Le calendrier garde l'icône. Les produits venus des PDF (grille Bibollet, pains de la boulangerie) n'ont pas de photo.
- Les **engagements en montant** (GAEC Bibollet, Boulangerie Chez Geoffrey) deviennent des montants d'engagement (REF-22).
- **GAEC Bibollet** : sur la boutique, seuls les engagements existent. La grille « Prix au kg TTC » (PDF de sa page producteur, tarifs au 01/01/2026) est reprise dans la vitrine, au kilo, mais pas dans le contrat.
- **Boulangerie Chez Geoffrey** : la liste des pains (au kilo) et des viennoiseries (à la pièce) vient du PDF « Pains disponibles » de sa page producteur.
- Les **108 fiches sans producteur** (pas de catégorie, ou catégorie « ZZ_Commande de Noël ») ne sont pas reprises. Elles sont listées à la fin avec une proposition de rattachement.

## Résumé par producteur

| Producteur | Produits dans la vitrine | Calcul du prix d'une livraison | À vérifier |
|---|---|---|---|
| Boulangerie Chez Geoffrey | 20 | engagements de la boutique + PDF « Pains disponibles » |  |
| Château Farizeau | 9 | prix du carton de 6 bouteilles |  |
| Clos de l'Abbaye – Bourgueil | 9 | prix du carton de 6 bouteilles ou du vrac |  |
| Domaine du Rieu Frais | 15 | prix du carton de 6 bouteilles ou de la fontaine à vin |  |
| GAEC Bibollet | 111 | engagements de la boutique + grille au kilo du PDF |  |
| La Cabane Marjolaine | 3 | indiqué : 8 paniers impairs (« Panier à 7 € » = 56 €) |  |
| La Chèvrerie de Lily | 4 | supposé : « Contrat 3 mois », environ 3 distributions impaires | ⚠️ oui |
| La Ferme du Coteau | 20 | déduit : les pommes donnent 3,30 €/kg, le prix de l'ancienne page producteur | ⚠️ oui |
| La Ferme du Ponant | 9 | indiqué : « contrat de 9 distributions » |  |
| La Ferme du Péroset | 3 | indiqué dans chaque fiche (6 ou 8 distributions impaires) |  |
| Lait'able des Frangins | — aucun produit sur la boutique |  |  |
| Le Corti des Aravis | 1 | indiqué : 13 paniers pour 380 € | ⚠️ oui |
| Le Fournil des Paysans | 18 | déduit : tous les prix « Impaire » sont des multiples de 11 |  |
| Le Jardin des Solstices | 2 | le nom donne le prix du panier |  |
| Les Paysans Voyageurs | 28 | prix au pot, identique au PDF « Gamme de produits » |  |
| Les Œufs d'Alex | 5 | déduit du prix par œuf indiqué (0,416 €/œuf × 24 = 10 €) |  |
| Mas d'Intras | 9 | prix du carton de 6 bouteilles |  |
| Vale Douro | — aucun produit sur la boutique |  |  |

## Points à trancher avec l'association

1. **Nombre de distributions** pour La Ferme du Coteau (6, déduit des anciens tarifs), La Chèvrerie de Lily (3, supposé) et Le Corti des Aravis (380 € ÷ 13 = 29,23 €, alors que la fiche annonce un prix moyen de 30,29 €).
2. La Ferme du Coteau : « Jus de pommes-coings » (5,40 €) et « Compote de pommes/fraises » (14,90 € ÷ 6 = 2,48 €) ne suivent pas la même règle que les autres produits. Le jus est repris à 5,40 € la bouteille.
3. **Le Fournil des Paysans** est noté « commandes groupées » sur le nouveau site, mais ses produits sont vendus sur la boutique comme un contrat (distributions impaires, paires ou les deux). Faut-il en faire un producteur sous contrat ?
4. **Commandes groupées sans facturation**, comme la boulangerie (engagement en montant, puis commande tous les 15 jours) : est-ce aussi le cas du GAEC Bibollet (engagement de 105 € à 1 000 €, puis commande par e-mail) ?
5. **Vale Douro** et **Lait'able des Frangins** n'ont aucun produit classé sur la boutique. Leurs produits sont à saisir à la main.
6. Producteurs présents sur la boutique mais absents du nouveau site : **Brasserie Onoire**, **La Ferme du ChèvreFeuille**, **Ferme de Pezières**, un producteur de fromages de vache (tommes, reblochon, abondance, beurre) et un fournisseur d'agrumes. Faut-il les ajouter ?
7. La boutique contient des fiches anciennes (agrumes de 2019, millésimes 2014 à 2017, commande de Noël). Elles pourront être supprimées de Wix ou ignorées.

## Détail par producteur

Pour chaque fiche : le nom sur la boutique, le nom et le tarif repris dans le nouveau site, et le calcul.

<details><summary><strong>Boulangerie Chez Geoffrey</strong> (4 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Engagement de 200€](https://www.lepanierdesaravis.fr/product-page/engagement-de-200) | Engagement de 200 € | 200,00 € / engagement | montant pour la saison |
| [Engagement de 150€](https://www.lepanierdesaravis.fr/product-page/engagement-de-150) | Engagement de 150 € | 150,00 € / engagement | montant pour la saison |
| [Engagement de 100€](https://www.lepanierdesaravis.fr/product-page/engagement-de-100) | Engagement de 100 € | 100,00 € / engagement | montant pour la saison |
| [Engagement de 50€](https://www.lepanierdesaravis.fr/product-page/%C5%93ufs-par-24) | Engagement de 50 € | 50,00 € / engagement | montant pour la saison |

</details>

<details><summary><strong>Château Farizeau</strong> (9 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Cadillac Cotes de Bordeaux Rouge  2022 Cuvée Victoria](https://www.lepanierdesaravis.fr/product-page/cadillac-cotes-de-bordeaux-rouge-2022-cuv%C3%A9e-victoria) | Cadillac Côtes de Bordeaux Rouge 2022 Cuvée Victoria | 54,00 € / carton de 6 × 75 cl | 54,00 € |
| [Cadillac Cotes de Bordeaux Rouge  2022 Cuvée F](https://www.lepanierdesaravis.fr/product-page/cadillac-cotes-de-bordeaux-rouge-2022-cuv%C3%A9e-f) | Cadillac Côtes de Bordeaux Rouge 2022 Cuvée F | 39,60 € / carton de 6 × 75 cl | 39,60 € |
| [Cotes de Bordeaux Rouge  2022](https://www.lepanierdesaravis.fr/product-page/cotes-de-bordeaux-rouge-2022) | Côtes de Bordeaux Rouge 2022 | 28,80 € / carton de 6 × 75 cl | 28,80 € |
| [Bordeaux Rosé 2024](https://www.lepanierdesaravis.fr/product-page/bordeaux-ros%C3%A9-2023) | Bordeaux Rosé 2024 | 27,00 € / carton de 6 × 75 cl | 27,00 € |
| [Bordeaux Blanc Doux Cuvée Charlotte 2023](https://www.lepanierdesaravis.fr/product-page/bordeaux-blanc-doux-cuv%C3%A9e-charlotte-2023) | Bordeaux Blanc Doux Cuvée Charlotte 2023 | 28,80 € / carton de 6 × 75 cl | 28,80 € |
| [Cadillac Cotes de Bordeaux Rouge  2022 Cuvée Max](https://www.lepanierdesaravis.fr/product-page/cadillac-cotes-de-bordeaux-rouge-2022) | Cadillac Côtes de Bordeaux Rouge 2022 Cuvée Max | 46,80 € / carton de 6 × 75 cl | 46,80 € |
| [Bordeaux Rouge  2021](https://www.lepanierdesaravis.fr/product-page/bordeaux-rouge-2022) | Bordeaux Rouge 2021 | 27,00 € / carton de 6 × 75 cl | 27,00 € |
| [Cotes de Bordeaux Rouge  2021](https://www.lepanierdesaravis.fr/product-page/cotes-de-bordeaux-rouge-2020) | Côtes de Bordeaux Rouge 2021 | 28,80 € / carton de 6 × 75 cl | 28,80 € |
| [Entre-Deux-Mers 2024](https://www.lepanierdesaravis.fr/product-page/sainte-foy-bordeaux-2010-75-cl) | Entre-Deux-Mers 2024 | 28,08 € / carton de 6 × 75 cl | 28,08 € |

</details>

<details><summary><strong>Clos de l'Abbaye – Bourgueil</strong> (9 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Bourgueil Rouge 2023 75cl](https://www.lepanierdesaravis.fr/product-page/bourgueil-rouge-2023-75cl) | Bourgueil Rouge 2023 | 54,00 € / carton de 6 × 75 cl | 54,00 € |
| [Bourgueil - Cuvée Galice 2022 75cl](https://www.lepanierdesaravis.fr/product-page/bourgueil-ros%C3%A9-2022-75cl) | Bourgueil – Cuvée Galice 2022 | 119,40 € / carton de 6 × 75 cl | 119,40 € |
| [Bourgueil Rouge 2021 75cl](https://www.lepanierdesaravis.fr/product-page/bourgueil-rouge-2021-75cl) | Bourgueil Rouge 2021 | 63,00 € / carton de 6 × 75 cl | 63,00 € |
| [Bourgueil Rouge 2020 75cl](https://www.lepanierdesaravis.fr/product-page/bourgueil-rouge-2020-75cl) | Bourgueil Rouge 2020 | 66,00 € / carton de 6 × 75 cl | 66,00 € |
| [Bourgueil Rouge 2019 75cl](https://www.lepanierdesaravis.fr/product-page/bourgueil-rouge-2019-75cl) | Bourgueil Rouge 2019 | 69,00 € / carton de 6 × 75 cl | 69,00 € |
| [Bourgueil Rouge 2017 75cl](https://www.lepanierdesaravis.fr/product-page/bourgueil-rouge-2015-75cl) | Bourgueil Rouge 2017 | 72,00 € / carton de 6 × 75 cl | 72,00 € |
| [Bourgueil Rouge 2022 Vrac 10 litres](https://www.lepanierdesaravis.fr/product-page/bourgueil-rouge-2018-vrac-10-litres) | Bourgueil Rouge 2022 (vrac) | 53,00 € / fontaine de 10 l | 53,00 € |
| [Bourgueil Rouge 2023 Vrac 5 litres](https://www.lepanierdesaravis.fr/product-page/bordeaux-rouge-vrac-5-litres) | Bourgueil Rouge 2023 (vrac) | 29,00 € / fontaine de 5 l | 29,00 € |
| [Bourgueil Rouge 2022 75cl](https://www.lepanierdesaravis.fr/product-page/merlot-2014-cuv%C3%A9e-benjamin) | Bourgueil Rouge 2022 | 57,00 € / carton de 6 × 75 cl | 57,00 € |

</details>

<details><summary><strong>Domaine du Rieu Frais</strong> (15 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Merlot Rouge - Cuvée Benjamin 2021](https://www.lepanierdesaravis.fr/product-page/merlot-rouge-cuv%C3%A9e-benjamin-2021) | Merlot Rouge – Cuvée Benjamin 2021 | 36,36 € / carton de 6 × 75 cl | 36,36 € |
| [Rosé - 2023-5 litres](https://www.lepanierdesaravis.fr/product-page/ros%C3%A9-2021-5-litres) | Rosé 2023 | 15,90 € / fontaine de 5 l | 15,90 € |
| [Chardonnay Roussanne Marsanne - 2024- 5 litres](https://www.lepanierdesaravis.fr/product-page/chardonnay-roussanne-marsanne-2021-5-litres) | Chardonnay Roussanne Marsanne 2024 | 17,46 € / fontaine de 5 l | 17,46 € |
| [Alliance de trois grands cépages - 2022- 5 litres](https://www.lepanierdesaravis.fr/product-page/alliance-de-trois-grands-c%C3%A9pages-2021-5-litres) | Alliance de trois grands cépages 2022 | 15,30 € / fontaine de 5 l | 15,30 € |
| [PINOT NOIR « Essence Terrestre » 2022](https://www.lepanierdesaravis.fr/product-page/pinot-noir-essence-terrestre-2021) | Pinot noir Essence Terrestre 2022 | 63,00 € / carton de 6 × 75 cl | 63,00 € |
| [Chardonnay - 2023](https://www.lepanierdesaravis.fr/product-page/copie-de-chardonnay-2015) | Chardonnay 2023 | 35,64 € / carton de 6 × 75 cl | 35,64 € |
| [Chardonnay Roussanne Marsanne - 2024-10 litres](https://www.lepanierdesaravis.fr/product-page/vin-blanc-2016-13-vol-5-litres) | Chardonnay Roussanne Marsanne 2024 | 29,76 € / fontaine de 10 l | 29,76 € |
| [Rosé - 2023-10 litres](https://www.lepanierdesaravis.fr/product-page/vin-rose-2016-13-vol-5-litres) | Rosé 2023 | 27,06 € / fontaine de 10 l | 27,06 € |
| [Alliance de trois grands cépages - 2022 - 10 litres](https://www.lepanierdesaravis.fr/product-page/vin-rouge-2015-13-vol) | Alliance de trois grands cépages 2022 | 26,58 € / fontaine de 10 l | 26,58 € |
| [Cuvée vol libre sans soufre ajouté 2023](https://www.lepanierdesaravis.fr/product-page/cuvee-sans-soufre-ajoute-syrah-2015-75-cl) | Cuvée vol libre sans soufre ajouté 2023 | 41,04 € / carton de 6 × 75 cl | 41,04 € |
| [Révélation - 2022](https://www.lepanierdesaravis.fr/product-page/revelation-2013-75-cl) | Révélation 2022 | 55,80 € / carton de 6 × 75 cl | 55,80 € |
| [Cabernet Sauvignon - Cuvée Alexandre 2021](https://www.lepanierdesaravis.fr/product-page/cabernet-sauvignon-2013-cuv%C3%A9e-alexandre-75-cl) | Cabernet Sauvignon – Cuvée Alexandre 2021 | 39,96 € / carton de 6 × 75 cl | 39,96 € |
| [Syrah - Cuvée Julien 2023](https://www.lepanierdesaravis.fr/product-page/syrah-2015) | Syrah – Cuvée Julien 2023 | 29,16 € / carton de 6 × 75 cl | 29,16 € |
| [Rosé de Syrah - Cuvée Clémentine 2023](https://www.lepanierdesaravis.fr/product-page/ros%C3%A9-de-syrah-2015) | Rosé de Syrah – Cuvée Clémentine 2023 | 29,16 € / carton de 6 × 75 cl | 29,16 € |
| [Viognier - 2023](https://www.lepanierdesaravis.fr/product-page/vionnay-2015) | Viognier 2023 | 49,68 € / carton de 6 × 75 cl | 49,68 € |

</details>

<details><summary><strong>GAEC Bibollet</strong> (5 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Engagement de 600€ pour l'année](https://www.lepanierdesaravis.fr/product-page/engagement-de-500-pour-l-ann%C3%A9e) | Engagement de 600 € | 600,00 € / engagement | montant pour la saison |
| [Engagement de 105€ pour l'année](https://www.lepanierdesaravis.fr/product-page/engagement-de-5-par-distribution) | Engagement de 105 € | 105,00 € / engagement | montant pour la saison |
| [Engagement de 150€ pour l'année](https://www.lepanierdesaravis.fr/product-page/engagement-de-10-par-distribution) | Engagement de 150 € | 150,00 € / engagement | montant pour la saison |
| [Engagement de 300€ pour l'année](https://www.lepanierdesaravis.fr/product-page/engagement-de-20-par-distribution) | Engagement de 300 € | 300,00 € / engagement | montant pour la saison |
| [Engagement de 450€ pour l'année](https://www.lepanierdesaravis.fr/product-page/engagement-de-50-par-distribution) | Engagement de 450 € | 450,00 € / engagement | montant pour la saison |

</details>

<details><summary><strong>La Cabane Marjolaine</strong> (3 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Fromage Blanc de Chèvre](https://www.lepanierdesaravis.fr/product-page/s%C3%A9rac-de-ch%C3%A8vre) | Fromage Blanc de Chèvre | 4,00 € / pot de 500 g | 32,00 € ÷ 8 |
| [Petit Panier Surprise à 7€](https://www.lepanierdesaravis.fr/product-page/panier-%C3%A0-05-50) | Petit panier à 7 € | 7,00 € / panier | 56,00 € ÷ 8 |
| [Grand Panier Surprise à 14€](https://www.lepanierdesaravis.fr/product-page/grand-panier-surprise-%C3%A0-13) | Grand panier à 14 € | 14,00 € / panier | 112,00 € ÷ 8 |

</details>

<details><summary><strong>La Chèvrerie de Lily</strong> (4 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Persillé des Aravis](https://www.lepanierdesaravis.fr/product-page/persill%C3%A9-des-aravis) | Persillé des Aravis | 14,00 € / pièce | 42,00 € ÷ 3 |
| [Faisselles de Chèvre](https://www.lepanierdesaravis.fr/product-page/faisselles-de-ch%C3%A8vre) | Faisselle de chèvre | 4,00 € / pièce | 12,00 € ÷ 3 |
| [Chevrotin AOP](https://www.lepanierdesaravis.fr/product-page/chevrotin-aop) | Chevrotin AOP | 8,00 € / pièce | 24,00 € ÷ 3 |
| [Sérac de Chèvre](https://www.lepanierdesaravis.fr/product-page/s%C3%A9rac) | Sérac de Chèvre | 5,00 € / pot de 500 g | 15,00 € ÷ 3 |

</details>

<details><summary><strong>La Ferme du Coteau</strong> (20 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Pur Jus de Pommes (par bouteilles de 33cl))](https://www.lepanierdesaravis.fr/product-page/pur-jus-de-pommes-par-bouteilles-de-33cl) | Pur Jus de Pommes | 2,70 € / bouteille de 33 cl | 16,20 € ÷ 6 |
| [Poires barquette 2kg](https://www.lepanierdesaravis.fr/product-page/poires-barquette-2kg) | Poires | 8,60 € / barquette de 2 kg | 51,60 € ÷ 6 |
| [Pommes cagette 5kg](https://www.lepanierdesaravis.fr/product-page/pommes-cagette-5kg) | Pommes | 16,00 € / cagette de 5 kg | 96,00 € ÷ 6 |
| [Purée pommes/poires sans sucre ajouté (pot de 480 g)](https://www.lepanierdesaravis.fr/product-page/pur%C3%A9e-pommes-poires-sans-sucre-ajout%C3%A9-par-pots-de-480-g) | Purée pommes/poires sans sucre ajouté | 3,20 € / pot de 480 g | 19,20 € ÷ 6 |
| [Compote de pommes/fraises (par pots de 480 g)](https://www.lepanierdesaravis.fr/product-page/compote-pomme-fraise-480-g) | Compote de pommes/fraises | 2,48 € / pot de 480 g | 14,90 € ÷ 6 |
| [Purée de pommes sans sucre ajouté (pots de 830 g)](https://www.lepanierdesaravis.fr/product-page/pur%C3%A9e-de-pomme-sans-sucre-830g) | Purée de pommes sans sucre ajouté | 4,45 € / pot de 830 g | 26,70 € ÷ 6 |
| [Compote de pommes (pots de 830 g)](https://www.lepanierdesaravis.fr/product-page/compote-pomme-830g) | Compote de pommes | 4,45 € / pot de 830 g | 26,70 € ÷ 6 |
| [Jus de pommes-coings (par bouteilles de 1 L)](https://www.lepanierdesaravis.fr/product-page/jus-de-pomme-coing-1-l) | Jus de pommes-coings | 5,40 € / bouteille de 1 l | 5,40 € |
| [Purée de poires sans sucre ajouté (par pots de 480 g)](https://www.lepanierdesaravis.fr/product-page/puree-de-poire-sans-sucre-480-g) | Purée de poires sans sucre ajouté | 3,25 € / pot de 480 g | 19,50 € ÷ 6 |
| [Jus de pommes/poires (par bouteilles de 1 L)](https://www.lepanierdesaravis.fr/product-page/jus-de-pomme-poire) | Jus de pommes/poires | 3,25 € / bouteille de 1 l | 19,50 € ÷ 6 |
| [Pommes (par kg)](https://www.lepanierdesaravis.fr/product-page/pommes-par-kg) | Pommes | 3,30 € / kg | 19,80 € ÷ 6 |
| [Poires (par kg)](https://www.lepanierdesaravis.fr/product-page/poires-par-kg) | Poires | 4,30 € / kg | 25,80 € ÷ 6 |
| [Compote de pommes (par pots de 480 g)](https://www.lepanierdesaravis.fr/product-page/compote-de-pommes-par-pots-de-480-g) | Compote de pommes | 3,05 € / pot de 480 g | 18,30 € ÷ 6 |
| [Compote de pommes/poires (par pots de 480 g)](https://www.lepanierdesaravis.fr/product-page/compote-de-pommes-poires-par-pots-de-480-g) | Compote de pommes/poires | 3,20 € / pot de 480 g | 19,20 € ÷ 6 |
| [Purée de pommes sans sucre ajouté (par pots de 480 g)](https://www.lepanierdesaravis.fr/product-page/pur%C3%A9e-de-pommes-sans-sucre-ajout%C3%A9-par-pots-de-480-g) | Purée de pommes sans sucre ajouté | 3,05 € / pot de 480 g | 18,30 € ÷ 6 |
| [Pur Jus de Pommes (par bouteilles de 1 L)](https://www.lepanierdesaravis.fr/product-page/jus-de-pommes-par-bouteilles-de-1-l) | Pur Jus de Pommes | 3,15 € / bouteille de 1 l | 18,90 € ÷ 6 |
| [Jus de poires](https://www.lepanierdesaravis.fr/product-page/jus-de-poires-par-bouteilles-de-1-l) | Jus de poires | 3,95 € / bouteille de 1 l | 23,70 € ÷ 6 |
| [Cidre doux](https://www.lepanierdesaravis.fr/product-page/cidre-doux-par-bouteilles-de-0-75-l) | Cidre doux | 4,30 € / bouteille de 0,75 l | 25,80 € ÷ 6 |
| [Cidre brut](https://www.lepanierdesaravis.fr/product-page/cidre-brut-par-bouteilles-de-0-75-l) | Cidre brut | 4,30 € / bouteille de 0,75 l | 25,80 € ÷ 6 |
| [Jus de pommes pétillant](https://www.lepanierdesaravis.fr/product-page/p%C3%A9tillant-de-pommes-par-bouteilles-de-0-75-l) | Jus de pommes pétillant | 4,05 € / bouteille de 0,75 l | 24,30 € ÷ 6 |

</details>

<details><summary><strong>La Ferme du Ponant</strong> (9 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Demi Bleu de Laudon](https://www.lepanierdesaravis.fr/product-page/demi-bleu-de-laudon) | Demi Bleu de Laudon | 4,50 € / pièce | 40,50 € ÷ 9 |
| [Lait entier pasteurisé](https://www.lepanierdesaravis.fr/product-page/lait-entier-pasteuris%C3%A9) | Lait entier pasteurisé | 1,70 € / bouteille de 1 l | 15,30 € ÷ 9 |
| [Tomme Fermière du Ponant (Demie)](https://www.lepanierdesaravis.fr/product-page/tomme-fermi%C3%A8re-du-ponant-1) | Tomme Fermière du Ponant (Demie) | 12,00 € / pièce | 108,00 € ÷ 9 |
| [Tomme Fermière du Ponant (Quart)](https://www.lepanierdesaravis.fr/product-page/tomme-fermi%C3%A8re-du-ponant) | Tomme Fermière du Ponant (Quart) | 6,00 € / pièce | 54,00 € ÷ 9 |
| [Tomme Fermière Saint-Maurice (Demie)](https://www.lepanierdesaravis.fr/product-page/tomme-fermi%C3%A8re-saint-maurice-1) | Tomme Fermière Saint-Maurice (Demie) | 12,00 € / pièce | 108,00 € ÷ 9 |
| [Tomme Fermière Saint-Maurice (Quart)](https://www.lepanierdesaravis.fr/product-page/tomme-fermi%C3%A8re-saint-maurice) | Tomme Fermière Saint-Maurice (Quart) | 6,00 € / pièce | 54,00 € ÷ 9 |
| [Raclette Nature du Laudon Demi-Meule](https://www.lepanierdesaravis.fr/product-page/raclette-nature-du-laudon-demi-meule) | Raclette Nature du Laudon Demi-Meule | 45,00 € / pièce | 405,00 € ÷ 9 |
| [Raclette Nature du Laudon 500g](https://www.lepanierdesaravis.fr/product-page/raclette-nature-du-laudon-500g) | Raclette Nature du Laudon 500g | 9,00 € / pièce | 81,00 € ÷ 9 |
| [Bleu de Laudon](https://www.lepanierdesaravis.fr/product-page/bleu-de-laudon) | Bleu de Laudon | 9,00 € / pièce | 81,00 € ÷ 9 |

</details>

<details><summary><strong>La Ferme du Péroset</strong> (3 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [SERAC](https://www.lepanierdesaravis.fr/product-page/copie-de-serac-commande-limit%C3%A9e-%C3%A0-1-pi%C3%A8ce) | Sérac | 2,50 € / pot | 15,00 € ÷ 6 |
| [DEMI TOMMETTE](https://www.lepanierdesaravis.fr/product-page/demi-tomette-commande-limit%C3%A9e-%C3%A0-1-pi%C3%A8ce) | Demi-tommette de brebis | 4,50 € / pièce | 36,00 € ÷ 8 |
| [TOMMETTE](https://www.lepanierdesaravis.fr/product-page/tomette-commande-limit%C3%A9e-%C3%A0-1-pi%C3%A8ce) | Tommette de brebis | 9,00 € / pièce | 72,00 € ÷ 8 |

</details>

<details><summary><strong>Le Corti des Aravis</strong> (1 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Panier de Légumes et Fruits Bio](https://www.lepanierdesaravis.fr/product-page/panier-%C3%A0-14) | Panier de Légumes et Fruits Bio | 29,23 € / panier | 380,00 € ÷ 13 |

</details>

<details><summary><strong>Le Fournil des Paysans</strong> (18 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Tarte Briochée aux Pralines](https://www.lepanierdesaravis.fr/product-page/tarte-brioch%C3%A9e-aux-pralines) | Tarte Briochée aux Pralines | 12,30 € / pièce | 135,30 € ÷ 11 |
| [Lentilles de Mésigny](https://www.lepanierdesaravis.fr/product-page/lentilles-de-m%C3%A9signy) | Lentilles de Mésigny | 5,50 € / sachet de 1 kg | 60,50 € ÷ 11 |
| [Pâton à Pizza](https://www.lepanierdesaravis.fr/product-page/p%C3%A2ton-%C3%A0-pizza) | Pâton à Pizza | 3,30 € / pièce | 36,30 € ÷ 11 |
| [Brioche Chocolat](https://www.lepanierdesaravis.fr/product-page/brioche-chocolat) | Brioche Chocolat | 6,20 € / pièce | 68,20 € ÷ 11 |
| [Brioche Nature](https://www.lepanierdesaravis.fr/product-page/brioche-nature) | Brioche Nature | 6,00 € / pièce | 66,00 € ÷ 11 |
| [Biscuits Sablés au Petit Epeautre](https://www.lepanierdesaravis.fr/product-page/biscuits-sabl%C3%A9s-au-petit-epeautre) | Biscuits Sablés au Petit Epeautre | 5,30 € / sachet de 250 g | 58,30 € ÷ 11 |
| [Cookies au Chocolat](https://www.lepanierdesaravis.fr/product-page/cookies-chocolat) | Cookies au Chocolat | 6,00 € / sachet de 250 g | 66,00 € ÷ 11 |
| ["Apérigraines"](https://www.lepanierdesaravis.fr/product-page/ap%C3%A9rigraines) | Apérigraines | 5,20 € / sachet de 250 g | 57,20 € ÷ 11 |
| [Le "Noix"](https://www.lepanierdesaravis.fr/product-page/le-noix) | Le « Noix » | 4,20 € / pain de 500 g | 46,20 € ÷ 11 |
| [Le "Grand Multigraines"](https://www.lepanierdesaravis.fr/product-page/le-grand-multigraines) | Le « Grand Multigraines » | 7,30 € / pain de 1 kg | 80,30 € ÷ 11 |
| [Le "Multigraines"](https://www.lepanierdesaravis.fr/product-page/le-multigraines) | Le « Multigraines » | 3,80 € / pain de 500 g | 41,80 € ÷ 11 |
| [Le "Montagnard"](https://www.lepanierdesaravis.fr/product-page/le-montagnard) | Le « Montagnard » | 3,40 € / pain de 500 g | 37,40 € ÷ 11 |
| [Le "Petit Epeautre"](https://www.lepanierdesaravis.fr/product-page/le-petit-epeautre) | Le « Petit Epeautre » | 4,10 € / pain de 500 g | 45,10 € ÷ 11 |
| [Le "Complet"](https://www.lepanierdesaravis.fr/product-page/le-complet) | Le « Complet » | 3,10 € / pain de 500 g | 34,10 € ÷ 11 |
| [Le "Grand Campagnard"](https://www.lepanierdesaravis.fr/product-page/le-grand-campagnard) | Le « Grand Campagnard » | 5,80 € / pain de 1 kg | 63,80 € ÷ 11 |
| [Le "Campagnard"](https://www.lepanierdesaravis.fr/product-page/le-campagnard) | Le « Campagnard » | 3,10 € / pain de 500 g | 34,10 € ÷ 11 |
| [Le "Graine de la Semaine"](https://www.lepanierdesaravis.fr/product-page/le-graine-de-la-semaine) | Le « Graine de la Semaine » | 3,90 € / pain de 500 g | 42,90 € ÷ 11 |
| [Le "Nature de la Semaine"](https://www.lepanierdesaravis.fr/product-page/le-panier-du-c%C3%A9libat) | Le « Nature de la Semaine » | 3,50 € / pain de 500 g | 38,50 € ÷ 11 |

</details>

<details><summary><strong>Le Jardin des Solstices</strong> (2 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Panier à 15€](https://www.lepanierdesaravis.fr/product-page/panier-%C3%A0-14-2) | Panier à 15 € | 15,00 € / panier | 360,00 € pour la saison, panier à 15 € |
| [Panier à 22€](https://www.lepanierdesaravis.fr/product-page/panier-%C3%A0-20) | Panier à 22 € | 22,00 € / panier | 528,00 € pour la saison, panier à 22 € |

</details>

<details><summary><strong>Les Paysans Voyageurs</strong> (28 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Jus de Pommes Bio](https://www.lepanierdesaravis.fr/product-page/jus-de-pommes-bio) | Jus de Pommes Bio | 3,20 € / bouteille de 1 l | 3,20 € |
| [Pickles de courgette au curry bio Pot de 450g](https://www.lepanierdesaravis.fr/product-page/pickles-de-courgette-au-curry-bio-pot-de-450g) | Pickles de courgette au curry bio | 4,50 € / pot de 450 g | 4,50 € |
| [Pickles de courgette au curry bio  Pot de 250g](https://www.lepanierdesaravis.fr/product-page/pickles-de-courgette-au-curry-bio-pot-de-250g) | Pickles de courgette au curry bio | 3,50 € / pot de 250 g | 3,50 € |
| [Coulis de Mûre Bio 25cl](https://www.lepanierdesaravis.fr/product-page/coulis-de-m%C3%BBre-bio-25cl) | Coulis de Mûre Bio | 5,70 € / bouteille de 25 cl | 5,70 € |
| [Coulis de Fraise Bio 25cl](https://www.lepanierdesaravis.fr/product-page/coulis-de-fraise-bio-25cl) | Coulis de Fraise Bio | 5,70 € / bouteille de 25 cl | 5,70 € |
| [Sirop de Groseille Bio 25cl](https://www.lepanierdesaravis.fr/product-page/sirop-de-groseille-bio-25cl) | Sirop de Groseille Bio | 4,20 € / bouteille de 25 cl | 4,20 € |
| [Sirop de Coing Bio 25cl](https://www.lepanierdesaravis.fr/product-page/sirop-de-coing-bio-25cl) | Sirop de Coing Bio | 4,20 € / bouteille de 25 cl | 4,20 € |
| [Sirop de Fraise Bio 25cl](https://www.lepanierdesaravis.fr/product-page/sirop-de-fraise-bio-25cl) | Sirop de Fraise Bio | 4,20 € / bouteille de 25 cl | 4,20 € |
| [Sirop de Menthe Bio 25cl](https://www.lepanierdesaravis.fr/product-page/sirop-de-menthe-bio-25cl) | Sirop de Menthe Bio | 4,20 € / bouteille de 25 cl | 4,20 € |
| [Sirop de Citron 25cl](https://www.lepanierdesaravis.fr/product-page/sirop-de-citron-25cl) | Sirop de Citron | 4,20 € / bouteille de 25 cl | 4,20 € |
| [Confiture Coings Pot de 250g](https://www.lepanierdesaravis.fr/product-page/confiture-coings-pot-de-250g) | Confiture Coings | 4,20 € / pot de 250 g | 4,20 € |
| [Confiture Coings  Pot de 450g](https://www.lepanierdesaravis.fr/product-page/confiture-coing-pot-de-450g) | Confiture Coings | 6,20 € / pot de 450 g | 6,20 € |
| [Confiture Cynorhodon Pot de 250g](https://www.lepanierdesaravis.fr/product-page/confiture-cynorhodon-bio-pot-de-250g) | Confiture Cynorhodon | 4,20 € / pot de 250 g | 4,20 € |
| [Confiture Cynorhodon  Pot de 450g](https://www.lepanierdesaravis.fr/product-page/confiture-cynorhodon-bio-pot-de-450g) | Confiture Cynorhodon | 6,20 € / pot de 450 g | 6,20 € |
| [Confiture Framboises Bio  Pot de 250g](https://www.lepanierdesaravis.fr/product-page/confiture-framboises-bio-pot-de-250g) | Confiture Framboises Bio | 4,20 € / pot de 250 g | 4,20 € |
| [Confiture Framboises Bio  Pot de 450g](https://www.lepanierdesaravis.fr/product-page/confiture-framboises-bio-pot-de-450g) | Confiture Framboises Bio | 6,20 € / pot de 450 g | 6,20 € |
| [Confiture Fraise/Rhubarbe Bio  Pot de 250g](https://www.lepanierdesaravis.fr/product-page/confiture-fraise-rhubarbe-bio-pot-de-250g) | Confiture Fraise/Rhubarbe Bio | 4,20 € / pot de 250 g | 4,20 € |
| [Confiture Fraise/Rhubarbe Bio  Pot de 450g](https://www.lepanierdesaravis.fr/product-page/confiture-fraise-rhubarbe-bio-pot-de-450g) | Confiture Fraise/Rhubarbe Bio | 6,20 € / pot de 450 g | 6,20 € |
| [Confiture Groseilles Bio  Pot de 250g](https://www.lepanierdesaravis.fr/product-page/confiture-groseilles-bio-pot-de-250g) | Confiture Groseilles Bio | 4,20 € / pot de 250 g | 4,20 € |
| [Confiture Groseilles Bio  Pot de 450g](https://www.lepanierdesaravis.fr/product-page/confiture-groseilles-bio-pot-de-450g) | Confiture Groseilles Bio | 6,20 € / pot de 450 g | 6,20 € |
| [Confiture Cassis Bio  Pot de 250g](https://www.lepanierdesaravis.fr/product-page/confiture-cassis-bio-pot-de-250g) | Confiture Cassis Bio | 4,20 € / pot de 250 g | 4,20 € |
| [Confiture Cassis Bio  Pot de 450g](https://www.lepanierdesaravis.fr/product-page/confiture-cassis-bio-pot-de-450g) | Confiture Cassis Bio | 6,20 € / pot de 450 g | 6,20 € |
| [Confiture Prunes Bio  Pot de 450g](https://www.lepanierdesaravis.fr/product-page/confiture-prunes-bio-pot-de-450g) | Confiture Prunes Bio | 6,20 € / pot de 450 g | 6,20 € |
| [Confiture Prunes Bio  Pot de 250g](https://www.lepanierdesaravis.fr/product-page/confiture-prunes-bio-pot-de-250g) | Confiture Prunes Bio | 4,20 € / pot de 250 g | 4,20 € |
| [Confiture Mûres Bio  Pot de 450g](https://www.lepanierdesaravis.fr/product-page/confiture-m%C3%BBres-bio-pot-de-450g) | Confiture Mûres Bio | 6,20 € / pot de 450 g | 6,20 € |
| [Confiture Mûres Bio  Pot de 250g](https://www.lepanierdesaravis.fr/product-page/confiture-m%C3%BBres-bio-pot-de-250g) | Confiture Mûres Bio | 4,20 € / pot de 250 g | 4,20 € |
| [Confiture Fraises Bio  Pot de 250g](https://www.lepanierdesaravis.fr/product-page/confiture-fraises-bio-pot-de-250g) | Confiture Fraises Bio | 4,20 € / pot de 250 g | 4,20 € |
| [Confiture Fraises Bio  Pot de 450g](https://www.lepanierdesaravis.fr/product-page/panier-%C3%A0-14-1) | Confiture Fraises Bio | 6,20 € / pot de 450 g | 6,20 € |

</details>

<details><summary><strong>Les Œufs d'Alex</strong> (5 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Œufs par 24](https://www.lepanierdesaravis.fr/product-page/%C5%93ufs-par-24-1) | Œufs bio | 10,00 € / les 24 | 180,00 € ÷ 18 |
| [Œufs par 30](https://www.lepanierdesaravis.fr/product-page/%C5%93ufs-par-30) | Œufs bio | 12,00 € / les 30 | 216,00 € ÷ 18 |
| [Œufs par 18](https://www.lepanierdesaravis.fr/product-page/%C5%93ufs-par-18) | Œufs bio | 7,50 € / les 18 | 135,00 € ÷ 18 |
| [Œufs par 6](https://www.lepanierdesaravis.fr/product-page/%C5%93ufs-par-6) | Œufs bio | 2,90 € / les 6 | 52,20 € ÷ 18 |
| [Œufs par 12](https://www.lepanierdesaravis.fr/product-page/%C5%93ufs-par-12) | Œufs bio | 5,50 € / les 12 | 99,00 € ÷ 18 |

</details>

<details><summary><strong>Mas d'Intras</strong> (9 fiches)</summary>

| Fiche de la boutique | Nouveau site | Tarif | Calcul |
|---|---|---|---|
| [Cuvée Isidora 2024 "Demeter"](https://www.lepanierdesaravis.fr/product-page/cuv%C3%A9e-isidora-2023-demeter) | Cuvée Isidora 2024 (Demeter) | 45,60 € / carton de 6 × 75 cl | 45,60 € |
| [Carignan 2023 puis 2024 "Demeter"](https://www.lepanierdesaravis.fr/product-page/carignan-2023-demeter) | Carignan 2023 puis 2024 (Demeter) | 68,40 € / carton de 6 × 75 cl | 68,40 € |
| [Merlot L'Enchanteur 2022](https://www.lepanierdesaravis.fr/product-page/merlot-l-enchanteur-2022) | Merlot L'Enchanteur 2022 | 61,80 € / carton de 6 × 75 cl | 61,80 € |
| [Cuvée des Helviens 2022 puis 2023](https://www.lepanierdesaravis.fr/product-page/copie-de-grenache-2022) | Cuvée des Helviens 2022 puis 2023 | 63,60 € / carton de 6 × 75 cl | 63,60 € |
| [Grenache 2023 "Demeter"](https://www.lepanierdesaravis.fr/product-page/grenache-2022) | Grenache 2023 (Demeter) | 70,80 € / carton de 6 × 75 cl | 70,80 € |
| [Chardonnay 2024 "Demeter"](https://www.lepanierdesaravis.fr/product-page/cuv%C3%A9e-isidora-2023) | Chardonnay 2024 (Demeter) | 72,60 € / carton de 6 × 75 cl | 72,60 € |
| [Cuvée Ferdinand 2022 puis 2023](https://www.lepanierdesaravis.fr/product-page/cuv%C3%A9e-ferdinand-2022) | Cuvée Ferdinand 2022 puis 2023 | 78,60 € / carton de 6 × 75 cl | 78,60 € |
| [Cuvée d'Alphonse 2023](https://www.lepanierdesaravis.fr/product-page/cuv%C3%A9e-d-alphonse-2022) | Cuvée d'Alphonse 2023 | 55,20 € / carton de 6 × 75 cl | 55,20 € |
| [Merlot - Assemblage de 2019, 2020, 2023, 2024](https://www.lepanierdesaravis.fr/product-page/merlot-cuv%C3%A9e-benjamin-2018) | Merlot – Assemblage de 2019, 2020, 2023, 2024 | 45,00 € / carton de 6 × 75 cl | 45,00 € |

</details>

<details><summary><strong>GAEC Bibollet : grille au kilo</strong> (vitrine seulement)</summary>

| Catégorie | Produit | Tarif |
|---|---|---|
| Bœuf bio (génisse) | Filet de bœuf | 49,60 € / kg |
| Bœuf bio (génisse) | Côte de bœuf | 34,95 € / kg |
| Bœuf bio (génisse) | Entrecôtes | 34,95 € / kg |
| Bœuf bio (génisse) | Faux-filet | 34,95 € / kg |
| Bœuf bio (génisse) | Pavés de rumsteck | 34,95 € / kg |
| Bœuf bio (génisse) | Fondue | 34,95 € / kg |
| Bœuf bio (génisse) | Steak (bavette, surprise, mouvant…) | 34,95 € / kg |
| Bœuf bio (génisse) | Rosbif | 30,90 € / kg |
| Bœuf bio (génisse) | Steak (tranche…) | 30,90 € / kg |
| Bœuf bio (génisse) | Bœuf pour hacher | 23,20 € / kg |
| Bœuf bio (génisse) | Basse côte à griller | 23,20 € / kg |
| Bœuf bio (génisse) | Braisé | 18,95 € / kg |
| Bœuf bio (génisse) | Bourguignon | 18,95 € / kg |
| Bœuf bio (génisse) | Pot-au-feu | 14,50 € / kg |
| Bœuf bio (génisse) | Plat de côte | 12,95 € / kg |
| Bœuf bio (génisse) | Jarret | 17,95 € / kg |
| Bœuf bio (génisse) | Haché de bœuf | 21,30 € / kg |
| Bœuf bio (génisse) | Diots de bœuf | 21,30 € / kg |
| Bœuf bio (génisse) | Merguez de bœuf | 21,30 € / kg |
| Bœuf bio (génisse) | Chipolatas de bœuf | 21,30 € / kg |
| Bœuf bio (génisse) | Brochettes de bœuf | 39,10 € / kg |
| Bœuf bio (génisse) | Os à moelle | 9,75 € / kg |
| Bœuf bio (génisse) | Langue, joue de bœuf | 17,95 € / kg |
| Bœuf bio (génisse) | Foie de bœuf | 12,60 € / kg |
| Bœuf bio (génisse) | Tripes fraîches | 10,20 € / kg |
| Bœuf bio (génisse) | Queue, cœur, rognons, tripes | 10,20 € / kg |
| Veau bio | Filet mignon de veau | 49,60 € / kg |
| Veau bio | Escalope de veau | 34,95 € / kg |
| Veau bio | Grenadin de veau | 34,95 € / kg |
| Veau bio | Noix de veau | 34,95 € / kg |
| Veau bio | Longe de veau | 34,95 € / kg |
| Veau bio | Côte de veau | 31,85 € / kg |
| Veau bio | Rôti d'épaule de veau | 26,45 € / kg |
| Veau bio | Osso buco | 26,45 € / kg |
| Veau bio | Blanquette sans os | 26,45 € / kg |
| Veau bio | Tendrons de veau | 22,90 € / kg |
| Veau bio | Quenelles de veau | 30,20 € / kg |
| Veau bio | Brochettes de veau | 39,10 € / kg |
| Veau bio | Foie de veau | 36,50 € / kg |
| Veau bio | Tête de veau (demie) | 17,90 € / kg |
| Veau bio | Rognons, cervelle | 17,90 € / kg |
| Veau bio | Cœur de veau | 15,00 € / kg |
| Veau bio | Pieds de veau | 5,50 € / kg |
| Steak haché bio 5 % MG (congelé) | Steaks hachés, carton de 30 | 19,60 € / kg |
| Steak haché bio 5 % MG (congelé) | Steaks hachés, carton de 10 | 22,20 € / kg |
| Porc fermier bio | Filet mignon de porc | 29,70 € / kg |
| Porc fermier bio | Rôti de filet sans os | 20,15 € / kg |
| Porc fermier bio | Escalope de porc | 20,15 € / kg |
| Porc fermier bio | Côte filet de porc | 19,45 € / kg |
| Porc fermier bio | Côte échine de porc | 19,45 € / kg |
| Porc fermier bio | Rouelle | 18,95 € / kg |
| Porc fermier bio | Rôti de jambon | 18,95 € / kg |
| Porc fermier bio | Sauté de porc | 18,95 € / kg |
| Porc fermier bio | Poitrine fraîche tranchée | 16,05 € / kg |
| Porc fermier bio | Poitrine fraîche en bloc | 14,75 € / kg |
| Porc fermier bio | Joue, langue de porc | 16,55 € / kg |
| Porc fermier bio | Pâté de campagne | 22,85 € / kg |
| Porc fermier bio | Diots, saucisson à cuire | 20,60 € / kg |
| Porc fermier bio | Chair à saucisse | 20,60 € / kg |
| Porc fermier bio | Chipolatas de porc | 20,85 € / kg |
| Porc fermier bio | Chorizo à griller | 21,95 € / kg |
| Porc fermier bio | Merguez de porc | 21,30 € / kg |
| Porc fermier bio | Brochettes de porc | 26,75 € / kg |
| Produits occasionnels bio | Poitrine, jarret saumuré | 18,60 € / kg |
| Produits occasionnels bio | Rôti farci | 21,65 € / kg |
| Produits occasionnels bio | Travers de porc (ribs) | 14,55 € / kg |
| Produits occasionnels bio | Côtes sans os (ou échine) | 21,10 € / kg |
| Produits occasionnels bio | Côtes sans os marinées | 24,10 € / kg |
| Produits occasionnels bio | Boudin noir | 18,65 € / kg |
| Produits occasionnels bio | Saucisson cuit à l'ail | 23,20 € / kg |
| Produits occasionnels bio | Lardons nature | 24,45 € / kg |
| Produits occasionnels bio | Lardons fumés | 25,95 € / kg |
| Produits occasionnels bio | Saucisse aux choux | 21,50 € / kg |
| Produits occasionnels bio | Saucisson pistaché | 22,80 € / kg |
| Produits occasionnels bio | Poitrine roulée cuite | 21,10 € / kg |
| Produits occasionnels bio | Caillette | 24,90 € / kg |
| Produits occasionnels bio | Fromage de tête | 19,85 € / kg |
| Produits occasionnels bio | Jambon blanc | 35,95 € / kg |
| Produits occasionnels bio | Pâté en croûte | 28,10 € / kg |
| Produits occasionnels bio | Diots de porc fumés | 23,00 € / kg |
| Produits occasionnels bio | Saucisson à cuire fumé | 23,00 € / kg |
| Produits occasionnels bio | Saucisson à l'ail cuit | 23,00 € / kg |
| Produits occasionnels bio | Lard paysan fumé | 29,20 € / kg |
| Produits occasionnels bio | Poitrine fumée | 21,90 € / kg |
| Produits occasionnels bio | Coppa, bacon | 5,90 € / 100 g |
| Produits occasionnels bio | Saucisson, chorizo sec | 38,35 € / kg |
| Agneau bio | Gigot entier | 33,99 € / kg |
| Agneau bio | Souris, selle, tranche | 33,99 € / kg |
| Agneau bio | Gigot sans os | 35,99 € / kg |
| Agneau bio | Épaule entière | 28,85 € / kg |
| Agneau bio | Épaule sans os | 34,19 € / kg |
| Agneau bio | Côtes filet, baronne | 26,79 € / kg |
| Agneau bio | Côtes découvertes | 25,75 € / kg |
| Agneau bio | Carré de côtes | 25,75 € / kg |
| Agneau bio | Ragoût | 17,60 € / kg |
| Agneau bio | Palets de brebis | 31,99 € / kg |
| Agneau bio | Brochettes d'agneau | 42,94 € / kg |
| Colis sous vide bio | Colis de bœuf, 10 kg | 19,00 € / kg |
| Colis sous vide bio | Colis de veau, 5 kg | 25,00 € / kg |
| Colis sous vide bio | Agneau découpé | 22,00 € / kg |
| Colis sous vide bio | Colis de porc, 10 kg | 15,50 € / kg |
| Verrines et plats cuisinés bio | Verrine 200 g (mousse de foie, campagne nature, cèpes, piment d'Espelette, rillettes) | 6,95 € / pot |
| Verrines et plats cuisinés bio | Bolognaise 200 g | 5,80 € / pot |
| Verrines et plats cuisinés bio | Bolognaise 400 g | 17,00 € / pot |
| Verrines et plats cuisinés bio | Sauté de porc 240 g | 7,90 € / pot |
| Verrines et plats cuisinés bio | Sauté de porc 480 g | 10,90 € / pot |

*Verrines : le PDF n'indique que le prix de la « Campagne cèpes » (6,95 €) ; il est repris pour toutes les verrines, à vérifier.*

</details>

## Fiches non rattachées (108)

| Fiche de la boutique | Prix boutique | Catégorie Wix | Producteur proposé | Remarque |
|---|---|---|---|---|
| Engagement de 75€ jusqu'à fin mars | 75,00 € | aucune catégorie | Boulangerie Chez Geoffrey ? | Même texte que ses engagements (« liste adressée par Morgane »). |
| Engagement de 50€ jusqu'à fin mars | 50,00 € | aucune catégorie | Boulangerie Chez Geoffrey ? | Même texte que ses engagements (« liste adressée par Morgane »). |
| Demi Petite Tomme (env. 300g) | 45,00 € | aucune catégorie | Producteur de fromages de vache non identifié | Pas de catégorie. Prix par fréquence (impaire, paire, les deux). |
| Beurre Fermier Demi-Sel | 26,00 € | aucune catégorie | Producteur de fromages de vache non identifié | Pas de catégorie. Prix par fréquence (impaire, paire, les deux). |
| Engagement de 1000€ pour l'année | 1000,00 € | aucune catégorie | GAEC Bibollet ? | Même libellé que ses engagements de 105 € à 600 €. |
| Engagement de 750€ pour l'année | 750,00 € | aucune catégorie | GAEC Bibollet ? | Même libellé que ses engagements de 105 € à 600 €. |
| Beurre Fermier Doux | 26,00 € | aucune catégorie | Producteur de fromages de vache non identifié | Pas de catégorie. Prix par fréquence (impaire, paire, les deux). |
| Demie Grande Tomme (env. 750g) | 120,00 € | aucune catégorie | Producteur de fromages de vache non identifié | Pas de catégorie. Prix par fréquence (impaire, paire, les deux). |
| Abondance AOP - 500g | 96,00 € | aucune catégorie | Producteur de fromages de vache non identifié | Pas de catégorie. Prix par fréquence (impaire, paire, les deux). |
| Abondance AOP - 300g | 57,60 € | aucune catégorie | Producteur de fromages de vache non identifié | Pas de catégorie. Prix par fréquence (impaire, paire, les deux). |
| Tomme de Montagne | ? | fiche illisible (erreur Wix) | La Chèvrerie de Lily | Bandeau « Contrat 3 mois » comme ses autres fromages. Fiche illisible (Wix a refusé la page). |
| Crème Dessert Brebis Chocolat 300g | 23,80 € | aucune catégorie | La Ferme de Péroset ? | Les « yaourts par 4 » étaient sur son ancienne page. Fromages et yaourts de brebis. |
| Yaourt de Brebis Vanille 300g | 17,50 € | aucune catégorie | La Ferme de Péroset ? | Les « yaourts par 4 » étaient sur son ancienne page. Fromages et yaourts de brebis. |
| Yaourt de Brebis Vanille 800g | 42,00 € | aucune catégorie | La Ferme de Péroset ? | Les « yaourts par 4 » étaient sur son ancienne page. Fromages et yaourts de brebis. |
| Yaourt de Brebis Nature 300g | 16,10 € | aucune catégorie | La Ferme de Péroset ? | Les « yaourts par 4 » étaient sur son ancienne page. Fromages et yaourts de brebis. |
| Yaourt de Brebis Nature 800g | 35,00 € | aucune catégorie | La Ferme de Péroset ? | Les « yaourts par 4 » étaient sur son ancienne page. Fromages et yaourts de brebis. |
| Bière Onoire Brune 75cl | 6,20 € | ZZ_Commande de Noël | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Ambrée 75cl | 6,20 € | ZZ_Commande de Noël | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Blanche 75cl | 6,20 € | ZZ_Commande de Noël | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Blonde 75cl | 6,20 € | ZZ_Commande de Noël | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Brune 50 cl | 4,20 € | ZZ_Commande de Noël | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Ambrée 50 cl | 4,20 € | ZZ_Commande de Noël | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Blanche 50cl | 4,20 € | ZZ_Commande de Noël | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Blonde 50cl | 4,20 € | ZZ_Commande de Noël | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| La Cabane Marjolaine : Saucisson Fumé Chèvre/Porc | 7,00 € | ZZ_Commande de Noël | La Cabane Marjolaine | Commande de Noël ou anciens fromages à l'unité. |
| La Cabane Marjolaine : Chorizo Chèvre/Porc | 7,00 € | ZZ_Commande de Noël | La Cabane Marjolaine | Commande de Noël ou anciens fromages à l'unité. |
| La Cabane Marjolaine : Saucisson Chèvre/Porc | 7,00 € | ZZ_Commande de Noël | La Cabane Marjolaine | Commande de Noël ou anciens fromages à l'unité. |
| La Cabane Marjolaine : Tommette Cerronnée | 5,00 € | ZZ_Commande de Noël | La Cabane Marjolaine | Commande de Noël ou anciens fromages à l'unité. |
| La Cabane Marjolaine : Tomme de Chèvre Vieille | 7,00 € | ZZ_Commande de Noël | La Cabane Marjolaine | Commande de Noël ou anciens fromages à l'unité. |
| Pain d'épices Miel Figues | 5,00 € | ZZ_Commande de Noël | Non identifié | Commande de Noël. |
| Pain d'épices Miel Oranges | 5,00 € | ZZ_Commande de Noël | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Pain d'épices miel | 5,00 € | ZZ_Commande de Noël | Non identifié | Commande de Noël. |
| Orange à Jus (1kg) | 1,80 € | aucune catégorie | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Oranges (1kg) | 2,30 € | aucune catégorie | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Citrons (1 kg) | 3,05 € | aucune catégorie | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Clémentines (1 kg) | 3,70 € | aucune catégorie | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Pamplemousse (à l'unité) | 1,00 € | aucune catégorie | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Bière Onoire Blanche 50cl | 50,40 € | aucune catégorie | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Blanche 75cl | 74,40 € | aucune catégorie | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| EPHEMERE 2017 | 42,84 € | aucune catégorie | Ancien vin (Domaine du Rieu Frais ou Château Farizeau ?) | Millésimes anciens, pas de catégorie. |
| Bordeaux Rouge Vrac 20 litres | 83,00 € | aucune catégorie | Ancien vin (Domaine du Rieu Frais ou Château Farizeau ?) | Millésimes anciens, pas de catégorie. |
| Boule aux Blés Anciens | 66,00 € | aucune catégorie | Le Fournil des Paysans ? | Pain, prix par fréquence comme ses autres pains. |
| La Ferme du ChèvreFeuille : 1/2 Tomme de Chèvre au Fenugrec | 10,00 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : Tomme de Chèvre au Fenugrec | 20,00 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : 1/4 Tomme de Chèvre Nature | 8,00 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : 1/2 Tomme de Chèvre Nature | 15,00 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : Tomme de Chèvre Nature | 30,00 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme de Pezières : Faisselle | 2,30 € | ZZ_Commande de Noël | Ferme de Pezières (absente du nouveau site) | Commande de Noël. |
| Coffret 3 Bières Onoire 75cl | 20,00 € | aucune catégorie | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Brune 75cl | 74,40 € | aucune catégorie | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Ambrée 75cl | 74,40 € | aucune catégorie | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Blonde 75cl | 74,40 € | aucune catégorie | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Brune 50 cl | 50,40 € | aucune catégorie | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Ambrée 50 cl | 50,40 € | aucune catégorie | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Bière Onoire Blonde 50cl | 50,40 € | aucune catégorie | Brasserie Onoire (absente du nouveau site) | Bières, une partie en commande de Noël. |
| Avocats (1 kg) | 8,20 € | aucune catégorie | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Pamplemousses (1 kg) | 4,00 € | aucune catégorie | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Mandarines (1 kg) | 4,00 € | aucune catégorie | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Clémentines (1 kg) | 3,60 € | aucune catégorie | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Citrons (1 kg) | 28,80 € | aucune catégorie | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Oranges (1 kg) | 21,60 € | aucune catégorie | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Oranges à jus (1 kg) | 14,40 € | aucune catégorie | Agrumes (fournisseur non identifié) | Commande ponctuelle (certaines datent du 21/12/2019). |
| Yaourts aux Fruits - Pot de 500g | 15,20 € | aucune catégorie | Lait'able des Frangins ? | Yaourts au lait de vache, pots de 500 g. |
| Yaourt Nature - Pot de 500g | 12,40 € | aucune catégorie | Lait'able des Frangins ? | Yaourts au lait de vache, pots de 500 g. |
| Yaourts Crème de Marron | 4,00 € | aucune catégorie | Lait'able des Frangins ? | Yaourts au lait de vache, pots de 500 g. |
| Panier Surprise | 56,00 € | aucune catégorie | La Ferme de Péroset ? | Les « yaourts par 4 » étaient sur son ancienne page. Fromages et yaourts de brebis. |
| Le Gros coeur de chèvre | 3,00 € | aucune catégorie | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Bûche des Bois Cendrée | 6,00 € | aucune catégorie | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| Chèvrefeuille enrobé bruschetta | 2,50 € | aucune catégorie | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| Chèvrefeuille enrobé oign'or | 2,50 € | aucune catégorie | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| Le Cabécou au coeur de chèvre | 1,70 € | aucune catégorie | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| Chèvrefeuille enrobé Ail et fines herbes | 2,50 € | aucune catégorie | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| Chèvrefeuille enrobé ail des ours | 2,50 € | aucune catégorie | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| Chèvrefeuille enrobé arc en ciel | 2,50 € | aucune catégorie | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| Chèvrefeuille enrobé le jardinier | 2,50 € | aucune catégorie | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| Chèvrefeuille nature | 2,20 € | aucune catégorie | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : La Faisselle de 500g | 3,00 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : La Bûche des Bois Cendrée | 6,00 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : Le Gros coeur de chèvre | 3,00 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : Le Cabécou au coeur de chèvre | 1,70 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : Chèvrefeuille enrobé bruschetta | 2,50 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : Chèvrefeuille enrobé le jardinier | 2,50 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : Chèvrefeuille enrobé arc en ciel | 2,50 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : Chèvrefeuille enrobé oign'or | 2,50 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : Chèvrefeuille enrobé Ail et fines herbes | 2,50 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : Chèvrefeuille enrobé ail des ours | 2,50 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| La Ferme du ChèvreFeuille : Chèvrefeuille nature | 2,20 € | ZZ_Commande de Noël | La Ferme du ChèvreFeuille (absente du nouveau site) | Commande de Noël. |
| Ferme de Pezières - Reblochon | 6,80 € | ZZ_Commande de Noël | Ferme de Pezières (absente du nouveau site) | Commande de Noël. |
| Ferme de Pezières : Demi-Tomme (env. 350g) | 5,00 € | ZZ_Commande de Noël | Ferme de Pezières (absente du nouveau site) | Commande de Noël. |
| Ferme de Pezières : Tomme (env. 700g) | 10,00 € | ZZ_Commande de Noël | Ferme de Pezières (absente du nouveau site) | Commande de Noël. |
| La Cabane Marjolaine : Fromage de chèvre Bûche cendrée | 4,60 € | aucune catégorie | La Cabane Marjolaine | Commande de Noël ou anciens fromages à l'unité. |
| La Cabane Marjolaine : Fromage de chèvre Lactique Sec | 1,80 € | aucune catégorie | La Cabane Marjolaine | Commande de Noël ou anciens fromages à l'unité. |
| La Cabane Marjolaine : Fromage de chèvre Lactique Mi-Frais | 1,80 € | aucune catégorie | La Cabane Marjolaine | Commande de Noël ou anciens fromages à l'unité. |
| La Cabane Marjolaine : Tomme de Chèvre jeune | 7,50 € | ZZ_Commande de Noël | La Cabane Marjolaine | Commande de Noël ou anciens fromages à l'unité. |
| Ferme du Péroset : Tommette | 9,00 € | ZZ_Commande de Noël | La Ferme de Péroset | Commande de Noël. |
| Ferme du Péroset : Sérac | 2,50 € | ZZ_Commande de Noël | La Ferme de Péroset | Commande de Noël. |
| Ferme du Péroset : Demi Tommette | 4,50 € | ZZ_Commande de Noël | La Ferme de Péroset | Commande de Noël. |
| Rouge | 25,00 € | aucune catégorie | Ancien vin (Domaine du Rieu Frais ou Château Farizeau ?) | Millésimes anciens, pas de catégorie. |
| Bordeaux Rosé Vrac 3 litres | 16,31 € | aucune catégorie | Ancien vin (Domaine du Rieu Frais ou Château Farizeau ?) | Millésimes anciens, pas de catégorie. |
| Sainte Foy - 2008 | 53,43 € | aucune catégorie | Ancien vin (Domaine du Rieu Frais ou Château Farizeau ?) | Millésimes anciens, pas de catégorie. |
| Chardonnay Roussanne | 21,40 € | aucune catégorie | Ancien vin (Domaine du Rieu Frais ou Château Farizeau ?) | Millésimes anciens, pas de catégorie. |
| Cabernet Sauvignon - 2014 | 13,56 € | aucune catégorie | Ancien vin (Domaine du Rieu Frais ou Château Farizeau ?) | Millésimes anciens, pas de catégorie. |
| Chardonnay - 2015 | 28,70 € | aucune catégorie | Ancien vin (Domaine du Rieu Frais ou Château Farizeau ?) | Millésimes anciens, pas de catégorie. |
| FAISSELLE | 27,60 € | aucune catégorie | La Ferme de Péroset ? | Les « yaourts par 4 » étaient sur son ancienne page. Fromages et yaourts de brebis. |
| TOMME BLANCHE | 42,00 € | aucune catégorie | La Ferme de Péroset ? | Les « yaourts par 4 » étaient sur son ancienne page. Fromages et yaourts de brebis. |
| Petite Tomme (env. 600g) | 90,00 € | aucune catégorie | Producteur de fromages de vache non identifié | Pas de catégorie. Prix par fréquence (impaire, paire, les deux). |
| Reblochon AOP | 90,00 € | aucune catégorie | Producteur de fromages de vache non identifié | Pas de catégorie. Prix par fréquence (impaire, paire, les deux). |
| YAOURTS par 4 | 40,00 € | aucune catégorie | La Ferme de Péroset ? | Les « yaourts par 4 » étaient sur son ancienne page. Fromages et yaourts de brebis. |
