<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Backup automatique quotidien à 23h
Schedule::command('backup:run')->dailyAt('23:00');

// Cleanup automatique des vieux backups chaque jour à minuit
Schedule::command('backup:clean')->daily();
