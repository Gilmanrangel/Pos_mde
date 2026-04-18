<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class StockUpdated implements ShouldBroadcastNow
{
    use SerializesModels;

    public $productId;
    public $stok;

    public function __construct($productId, $stok)
    {
        $this->productId = $productId;
        $this->stok = $stok;
    }

    public function broadcastOn()
    {
        return new Channel('stock-channel');
    }

    public function broadcastAs()
    {
        return 'StockUpdated';
    }
}