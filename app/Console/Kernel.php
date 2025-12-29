<?php

use Illuminate\Console\Scheduling\Schedule;

protected function schedule(Schedule $schedule): void
{
    $schedule->command('report:daily-sales')->dailyAt('20:00'); // 8 PM
}
