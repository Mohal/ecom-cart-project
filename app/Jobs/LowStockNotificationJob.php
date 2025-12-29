<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class LowStockNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Product $product) {}

    public function handle(): void
    {
        $adminEmail = config('mail.admin_email', 'admin@example.com');

        Mail::raw(
            "Low stock alert: {$this->product->name} has {$this->product->stock_quantity} units left.",
            fn($m) => $m->to($adminEmail)->subject('Low Stock Notification')
        );
    }
}
