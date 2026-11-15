<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/** Pages d'information, modifiables ensuite par l'administrateur. Textes repris du site actuel. */
class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'accueil' => [
                'title' => 'Adhésions 2027',
                'intro' => null,
                'content' => '<p>Les adhésions pour 2027 sont ouvertes ! Venez nous rencontrer lors des distributions du <strong>5</strong> et du <strong>19 décembre</strong>, ou créez dès maintenant votre compte sur ce site.</p>',
            ],
            'l-amap' => [
                'title' => "L'AMAP, comment ça marche ?",
                'intro' => 'Une Association pour le Maintien d\'une Agriculture Paysanne établit un partenariat entre un groupe de consomm\'acteurs et des producteurs, selon la charte des AMAP.',
                'content' => <<<'HTML'
<h2>En rejoignant une AMAP</h2>
<ul>
<li>vous participez à la sauvegarde des fermes de proximité, dans une logique d'agriculture durable ;</li>
<li>vous contribuez à une économie solidaire entre villes et campagnes ;</li>
<li>vous favorisez une agriculture paysanne, socialement équitable et écologiquement saine ;</li>
<li>vous devenez consomm'acteur : vous achetez à un prix juste des produits de qualité, en sachant d'où ils viennent et comment ils ont été produits.</li>
</ul>
<h2>Notre fonctionnement</h2>
<p>Nous créons un lien direct entre des producteurs locaux et des consomm'acteurs de Thônes et des environs.</p>
<p>Chaque adhérent passe un <strong>contrat</strong> avec un ou plusieurs de nos producteurs, qui proposent leurs produits sous forme de paniers. Il s'engage pour l'année : les contrats débutent en janvier.</p>
<p>Pour assurer au producteur une trésorerie de production, les paniers sont <strong>payés à l'avance</strong>, en 1 à 3 règlements, directement au producteur.</p>
<p>La remise des paniers a lieu <strong>tous les 15 jours, le samedi de 9h30 à 10h30</strong>, dans les locaux de l'EHPAD « Le Chant du Fier » à Thônes. Vos dates apparaissent automatiquement dans votre calendrier, sur ce site.</p>
<p>Les distributions sont assurées par les producteurs et par les adhérents, qui s'inscrivent aux <strong>permanences</strong> selon leurs disponibilités et les besoins de l'AMAP.</p>
<blockquote>Des commandes groupées (vin, huile, farine, agrumes…) sont aussi proposées au fil de l'année.</blockquote>
HTML,
            ],
            'adherer' => [
                'title' => "Adhérer à l'AMAP",
                'intro' => 'Merci de l\'intérêt que vous portez à notre association. Les adhésions sont annuelles et ont lieu début décembre pour l\'année suivante.',
                'content' => '<p>En cours d\'année, vous pouvez venir nous rendre visite lors d\'une distribution. Vous y rencontrerez l\'un de nos responsables ainsi que les producteurs.</p><p>En adhérant, vous vous engagez à respecter le fonctionnement de l\'AMAP et son <a href="/documents/code-de-bonne-conduite.pdf">code de bonne conduite</a>, et à participer à au moins deux distributions dans l\'année.</p>',
            ],
            'mentions-legales' => [
                'title' => 'Mentions légales',
                'intro' => null,
                'content' => '<h2>Éditeur</h2><p>Association « Le Panier des Aravis », Maison des Associations, 3 rue du Chanoine Pochat-Baron, 74230 Thônes. Directeur ou directrice de la publication : <em>[à compléter]</em>.</p><h2>Hébergement</h2><p>Infomaniak Network SA, Rue Eugène-Marziano 25, 1227 Les Acacias (GE), Suisse.</p><p><em>Texte provisoire du prototype, à valider par le bureau.</em></p>',
            ],
            'confidentialite' => [
                'title' => 'Politique de confidentialité',
                'intro' => null,
                'content' => '<p>Le Panier des Aravis collecte uniquement les données nécessaires au fonctionnement de l\'AMAP : coordonnées, adhésion, contrats, commandes et permanences. Elles sont hébergées en Suisse, chez Infomaniak, et ne sont jamais vendues ni utilisées à des fins publicitaires.</p><p>Votre numéro de téléphone n\'est visible des autres adhérents que si vous l\'avez accepté. Vous pouvez à tout moment consulter, corriger ou demander la suppression de vos données en nous écrivant.</p><p><em>Texte provisoire du prototype, à compléter par le bureau.</em></p>',
            ],
        ];

        foreach ($pages as $slug => $data) {
            Page::updateOrCreate(['slug' => $slug], $data);
        }
    }
}
