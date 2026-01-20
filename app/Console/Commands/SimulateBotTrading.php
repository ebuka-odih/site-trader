<?php

namespace App\Console\Commands;

use App\Models\BotTrading;
use App\Services\SimpleBotTradingEngine;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SimulateBotTrading extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot-trading:simulate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate bot trading for active bots';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting bot trading simulation...');
        
        $activeBots = BotTrading::where('status', 'active')->get();
        
        if ($activeBots->isEmpty()) {
            $this->info('No active bots found.');
            return 0;
        }
        
        $this->info("Found {$activeBots->count()} active bots.");
        
        $engine = new SimpleBotTradingEngine();
        $tradesExecuted = 0;
        
        foreach ($activeBots as $bot) {
            try {
                $this->info("Processing bot: {$bot->name} ({$bot->strategy})");
                
                $trade = $engine->executeBot($bot);
                
                if ($trade) {
                    $tradesExecuted++;
                    $this->info("✓ Trade executed: {$trade->type} {$trade->base_amount} {$trade->base_asset} at \${$trade->price}");
                } else {
                    $this->info("  No trade conditions met");
                }
                
            } catch (\Exception $e) {
                $this->error("Error processing bot {$bot->id}: " . $e->getMessage());
                Log::error("Bot simulation failed for bot {$bot->id}: " . $e->getMessage());
            }
        }
        
        $this->info("Simulation completed. {$tradesExecuted} trades executed.");
        
        return 0;
    }
}
