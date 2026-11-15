<?php

/*
 * Paramètres propres à l'AMAP. Une autre AMAP qui réutilise l'application
 * modifie ce fichier (ou les variables d'environnement), pas le code.
 */
return [
    'name' => env('AMAP_NAME', 'Le Panier des Aravis'),
    'tagline' => 'Association pour le Maintien d\'une Agriculture Paysanne',
    'city' => 'Thônes',

    'postal_address' => [
        'Maison des Associations',
        '3 rue du Chanoine Pochat-Baron',
        '74230 Thônes',
    ],

    'distribution' => [
        'place' => 'EHPAD « Le Chant du Fier »',
        'address' => '10 rue du 8 mai 1945, 74230 Thônes',
        'weekday' => 'samedi',
        'starts_at' => '09:30',
        'ends_at' => '10:30',
        'volunteers_arrive_at' => '08:30',
        'interval_weeks' => 2,
    ],

    'membership_fee_cents' => (int) env('AMAP_MEMBERSHIP_FEE_CENTS', 2000),

    'contact_email' => env('AMAP_CONTACT_EMAIL', 'contact@lepanierdesaravis.fr'),

    // Sujet du formulaire de contact => adresse (ou alias) de destination.
    'contact_subjects' => [
        'adhesion' => ['label' => 'Adhésion', 'email' => 'adhesion@lepanierdesaravis.fr'],
        'commandes' => ['label' => 'Commandes et contrats', 'email' => 'contact@lepanierdesaravis.fr'],
        'permanences' => ['label' => 'Permanences', 'email' => 'permanences@lepanierdesaravis.fr'],
        'producteur' => ['label' => 'Devenir producteur partenaire', 'email' => 'producteurs@lepanierdesaravis.fr'],
        'site' => ['label' => 'Site internet', 'email' => 'webmaster@lepanierdesaravis.fr'],
        'autre' => ['label' => 'Autre', 'email' => 'contact@lepanierdesaravis.fr'],
    ],

    'contact_sender_types' => [
        'adherent' => 'Adhérent·e',
        'producteur' => 'Producteur·rice',
        'curieux' => 'Je découvre l\'AMAP',
    ],

    'product_types' => [
        'legumes' => 'Légumes',
        'fruits' => 'Fruits',
        'fromages' => 'Fromages',
        'laitages' => 'Laitages',
        'viandes' => 'Viandes',
        'oeufs' => 'Œufs',
        'pain' => 'Pain',
        'farines' => 'Farines',
        'vins' => 'Vins',
        'boissons' => 'Jus et boissons',
        'epicerie' => 'Épicerie',
    ],

    'labels' => [
        'bio' => 'Agriculture biologique',
        'demeter' => 'Demeter',
        'nature-progres' => 'Nature & Progrès',
        'aop' => 'AOP',
    ],
];
