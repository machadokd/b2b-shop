<?php

namespace App\Jobs;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class UpdateProductStock implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Order $order) {}

    public function handle(ProductRepositoryInterface $products): void
    {
        foreach ($this->order->items as $item) {
            /** @var OrderItem $item */
            $product = $products->findById($item->product_id);
            if ($product) {
                $product->decrement('stock', $item->quantity);
            }
        }
    }
}
