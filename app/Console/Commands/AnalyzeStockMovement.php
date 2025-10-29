<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\InventorySetting;
use App\Models\StockMovementAnalysis;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AnalyzeStockMovement extends Command
{
    protected $signature = 'stock:analyze';
    protected $description = 'Analyze stock movement patterns';

    public function handle()
    {
        $this->info('Starting stock movement analysis...');

        try {
            $settings = InventorySetting::first() ?? new InventorySetting();
            $period = $settings->analysis_period ?? 30;
            $startDate = Carbon::now()->subDays($period);

            // Get sales data for the period
            $sales = DB::table('transaction_details')
                ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
                ->where('transactions.created_at', '>=', $startDate)
                ->select(
                    'transaction_details.item_id',
                    DB::raw('SUM(transaction_details.qty) as total_sold'),
                    DB::raw('MAX(transactions.created_at) as last_sale_date')
                )
                ->groupBy('transaction_details.item_id')
                ->get();

            $salesMap = $sales->keyBy('item_id');

            // Process each item
            Item::chunk(100, function ($items) use ($settings, $period, $salesMap) {
                foreach ($items as $item) {
                    $itemSales = $salesMap->get($item->id);
                    
                    $totalSold = $itemSales->total_sold ?? 0;
                    $avgDailySales = $totalSold / $period;
                    $lastSaleDate = $itemSales ? Carbon::parse($itemSales->last_sale_date) : null;
                    
                    // Determine movement status
                    $status = 'NORMAL';
                    if ($avgDailySales >= $settings->fast_moving_threshold) {
                        $status = 'FAST';
                    } elseif ($avgDailySales <= $settings->slow_moving_threshold) {
                        $status = 'SLOW';
                    }

                    // Calculate days until empty
                    $daysUntilEmpty = $avgDailySales > 0 ? ceil($item->stock / $avgDailySales) : null;
                    
                    // Calculate non-moving days
                    $nonMovingDays = $lastSaleDate 
                        ? Carbon::now()->diffInDays($lastSaleDate) 
                        : $period;

                    // Calculate suggested reorder quantity for fast moving items
                    $suggestedReorderQty = null;
                    if ($status === 'FAST') {
                        $suggestedReorderQty = ceil(
                            ($avgDailySales * $settings->lead_time_days) + 
                            ($avgDailySales * $settings->safety_stock_days)
                        );
                    }

                    // Determine recommendation
                    $recommendation = match($status) {
                        'FAST' => 'Sarankan Restock',
                        'SLOW' => 'Sarankan Promo/Diskon',
                        default => 'Aman'
                    };

                    // Update or create analysis record
                    StockMovementAnalysis::updateOrCreate(
                        ['item_id' => $item->id],
                        [
                            'total_sold_30_days' => $totalSold,
                            'avg_daily_sales' => $avgDailySales,
                            'movement_status' => $status,
                            'current_stock' => $item->stock,
                            'days_until_empty' => $daysUntilEmpty,
                            'non_moving_days' => $nonMovingDays,
                            'stuck_stock_value' => $status === 'SLOW' ? ($item->stock * $item->cost_price) : 0,
                            'recommendation' => $recommendation,
                            'suggested_reorder_qty' => $suggestedReorderQty,
                            'last_sale_date' => $lastSaleDate,
                            'last_analysis_date' => Carbon::now()
                        ]
                    );
                }
            });

            $this->info('Stock movement analysis completed successfully.');
        } catch (\Exception $e) {
            $this->error('Error analyzing stock movement: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}