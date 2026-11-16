<?php

use App\Services\Announcements;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Nécessite la tâche cron du serveur : * * * * * php artisan schedule:run
Artisan::command('amap:annonces', function (Announcements $announcements) {
    $this->info($announcements->sendDue().' e-mail(s) d\'annonce mis en file d\'attente.');
})->purpose('Annonce par e-mail les contrats et commandes groupées qui viennent d\'ouvrir');

Schedule::command('amap:annonces')->everyFiveMinutes()->withoutOverlapping();
