<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Payment reminder scheduler (Email) - runs daily at 07:00 WIT (Asia/Makassar)
Schedule::command('payment:send-reminders')
    ->dailyAt('07:00')
    ->timezone('Asia/Makassar')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/payment-reminders.log'));

// WhatsApp group reminder - runs daily at 08:00 WIT (H-2/H-3 sebelum jatuh tempo)
Schedule::command('payment:send-wa-reminders')
    ->dailyAt('08:00')
    ->timezone('Asia/Makassar')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/wa-reminders.log'));
