<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily sales report to run at 23:00 (11 PM) every day
Schedule::command('app:send-daily-report')
    ->dailyAt('23:00')
    ->timezone('UTC')
    ->description('Send daily sales report to admin');
