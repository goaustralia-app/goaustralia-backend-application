<?php

use App\Jobs\GenerateDailyReminders;
use App\Jobs\SendEmailNotifications;
use App\Jobs\SendPushNotifications;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new GenerateDailyReminders)->dailyAt('00:00');
Schedule::job(new SendPushNotifications)->dailyAt('08:00');
Schedule::job(new SendEmailNotifications)->dailyAt('08:00');
