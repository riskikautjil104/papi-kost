<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Payment reminder scheduler - runs daily at 07:00 WIT (Asia/Makassar)
Schedule::command('payment:send-reminders')
    ->dailyAt('07:00')
    ->timezone('Asia/Makassar')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/payment-reminders.log'));
