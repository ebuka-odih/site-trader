<?php

namespace App\Console\Commands;

use App\Http\Controllers\TradeController;
use Illuminate\Console\Command;

class CheckTradeDuration extends Command
{
    protected $signature = 'app:check-trade-duration';
    protected $description = 'Check and update trade durations';

    public function handle()
    {
        app(TradeController::class)->checkTradeDuration();
    }

}
