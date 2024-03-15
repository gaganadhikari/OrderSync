<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//syncs all the orders from woocommerce since last 30 days
Schedule::command('app:sync-orders --force')->dailyAt('12:00');

//deleted orders that have not been updated since last 3 months
Artisan::command('delete:unused-order', function () {
    $dateThreeMonthsAgo = Carbon::now()->subMonths(3)->toDateString();
    DB::table('orders')->where('updated_at' < $dateThreeMonthsAgo)->delete();
})->purpose('Delete unused orders')->daily();
