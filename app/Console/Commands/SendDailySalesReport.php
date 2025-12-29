<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use Carbon\Carbon;

class SendDailySalesReport extends Command
{
    protected $signature = 'report:daily-sales';
    protected $description = 'Send daily sales report to admin';

    public function handle(): int
    {
        $adminEmail = config('mail.admin_email', 'admin@example.com');
        $start = Carbon::yesterday()->startOfDay();
        $end   = Carbon::yesterday()->endOfDay();

        $orders = Order::with('items.product')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalRevenue = $orders->sum('total');
        $lines = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $lines[] = sprintf(
                    'Order #%d | %s x %d @ %.2f = %.2f',
                    $order->id,
                    $item->product->name,
                    $item->quantity,
                    $item->unit_price,
                    $item->line_total
                );
            }
        }

        $body = "Daily Sales Report for {$start->toDateString()}\n\n";
        $body .= implode("\n", $lines) . "\n\n";
        $body .= "Total Revenue: " . number_format($totalRevenue, 2);

        Mail::raw($body, fn($m) => $m->to($adminEmail)->subject('Daily Sales Report'));

        $this->info('Daily sales report sent.');
        return self::SUCCESS;
    }
}
