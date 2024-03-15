<?php

namespace App\Console\Commands;

use App\Actions\SyncOrder;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync orders from woocommerce to laravel app';

    protected $syncOrder;

    public function __construct(SyncOrder $syncOrder)
    {
        parent::__construct();
        $this->syncOrder = $syncOrder;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            $result = $this->syncOrder->handle(30);

            if ($result) {
                $this->info('Orders synced successfully.');
            } else {
                $this->error('Failed to fetch or sync orders.');
                Log::error('Failed to fetch or sync orders.');
            }
        }catch (Exception $e){
            $this->error('Error syncing orders: ' . $e->getMessage());
            Log::error('Error syncing orders: ' . $e->getMessage());
        }

    }
}
