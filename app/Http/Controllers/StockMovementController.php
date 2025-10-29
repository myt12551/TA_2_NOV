<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $startDate = Carbon::now()->subDays(30);
        
        // Get items with their sales data
        $query = Item::with('category')
            ->select([
                'items.id',
                'items.name',
                'items.code',
                'items.category_id',
                'items.cost_price',
                'items.selling_price',
                'items.stock',
                'items.picture',
                'items.created_at',
                'items.updated_at'
            ])
            ->selectRaw('COALESCE(SUM(td.qty), 0) as total_sold')
            ->selectRaw('COALESCE(SUM(td.qty) / 30.0, 0) as avg_daily_sales')
            ->selectRaw('MAX(t.created_at) as last_sale_date')
            ->leftJoin('transaction_details as td', 'items.id', '=', 'td.item_id')
            ->leftJoin('transactions as t', function($join) use ($startDate) {
                $join->on('td.transaction_id', '=', 't.id')
                     ->where('t.created_at', '>=', $startDate);
            })
            ->groupBy([
                'items.id',
                'items.name',
                'items.code',
                'items.category_id',
                'items.cost_price',
                'items.selling_price',
                'items.stock',
                'items.picture',
                'items.created_at',
                'items.updated_at'
            ]);

        // Calculate movement status
        $items = $query->get()->map(function($item) {
            $avgSales = $item->avg_daily_sales;
            $status = 'NORMAL';
            
            if ($avgSales >= 3.0) { // Fast moving threshold
                $status = 'FAST';
            } elseif ($avgSales <= 0.5) { // Slow moving threshold
                $status = 'SLOW';
            }
            
            $daysUntilEmpty = $avgSales > 0 ? ceil($item->stock / $avgSales) : null;
            $recommendation = match($status) {
                'FAST' => 'Sarankan Restock',
                'SLOW' => 'Sarankan Promo/Diskon',
                default => 'Aman'
            };
            
            $item->movement_status = $status;
            $item->days_until_empty = $daysUntilEmpty;
            $item->recommendation = $recommendation;
            
            return $item;
        });

        if ($status !== 'all') {
            $items = $items->filter(function($item) use ($status) {
                return $item->movement_status === strtoupper($status);
            })->values();
        }

        return view('inventory.stock-movement.index', [
            'analyses' => $items,
            'selectedStatus' => $status
        ]);
    }

    public function fastMoving()
    {
        $startDate = Carbon::now()->subDays(30);
        
        // Get items with fast-moving status
        $items = Item::with('category')
            ->select([
                'items.id',
                'items.name',
                'items.code',
                'items.category_id',
                'items.cost_price',
                'items.selling_price',
                'items.stock',
                'items.picture',
                'items.created_at',
                'items.updated_at'
            ])
            ->selectRaw('SUM(COALESCE(td.qty, 0)) as total_sold')
            ->selectRaw('COALESCE(SUM(td.qty) / 30.0, 0) as avg_daily_sales')
            ->leftJoin('transaction_details as td', 'items.id', '=', 'td.item_id')
            ->leftJoin('transactions as t', function($join) use ($startDate) {
                $join->on('td.transaction_id', '=', 't.id')
                     ->where('t.created_at', '>=', $startDate);
            })
            ->groupBy([
                'items.id',
                'items.name',
                'items.code',
                'items.category_id',
                'items.cost_price',
                'items.selling_price',
                'items.stock',
                'items.picture',
                'items.created_at',
                'items.updated_at'
            ])
            ->having('avg_daily_sales', '>=', 3.0)
            ->orderBy('avg_daily_sales', 'desc')
            ->get();

        return view('inventory.stock-movement.fast-moving', [
            'analyses' => $items
        ]);
    }

    public function slowMoving()
    {
        $startDate = Carbon::now()->subDays(30);
        
        // Get items with slow-moving status
        $items = Item::with('category')
            ->select([
                'items.id',
                'items.name',
                'items.code',
                'items.category_id',
                'items.cost_price',
                'items.selling_price',
                'items.stock',
                'items.picture',
                'items.created_at',
                'items.updated_at'
            ])
            ->selectRaw('SUM(COALESCE(td.qty, 0)) as total_sold')
            ->selectRaw('COALESCE(SUM(td.qty) / 30.0, 0) as avg_daily_sales')
            ->leftJoin('transaction_details as td', 'items.id', '=', 'td.item_id')
            ->leftJoin('transactions as t', function($join) use ($startDate) {
                $join->on('td.transaction_id', '=', 't.id')
                     ->where('t.created_at', '>=', $startDate);
            })
            ->groupBy([
                'items.id',
                'items.name',
                'items.code',
                'items.category_id',
                'items.cost_price',
                'items.selling_price',
                'items.stock',
                'items.picture',
                'items.created_at',
                'items.updated_at'
            ])
            ->having('avg_daily_sales', '<=', 0.5)
            ->orderBy('avg_daily_sales', 'asc')
            ->get();

        return view('inventory.stock-movement.slow-moving', [
            'analyses' => $items
        ]);
    }

    public function analyze()
    {
        // Analisis akan dilakukan real-time saat mengakses halaman index
        return back()->with('success', 'Analisis pergerakan stok diperbarui secara real-time.');
    }

    public function export(Request $request)
    {
        try {
            $startDate = Carbon::now()->subDays(30);
            $status = $request->input('status', 'all');
            
            // Get items with their sales data
            $query = Item::with('category')
                ->select([
                    'items.id',
                    'items.name',
                    'items.code',
                    'items.category_id',
                    'items.cost_price',
                    'items.selling_price',
                    'items.stock',
                    'items.picture',
                    'items.created_at',
                    'items.updated_at'
                ])
                ->selectRaw('SUM(COALESCE(td.qty, 0)) as total_sold')
                ->selectRaw('COALESCE(SUM(td.qty) / 30.0, 0) as avg_daily_sales')
                ->leftJoin('transaction_details as td', 'items.id', '=', 'td.item_id')
                ->leftJoin('transactions as t', function($join) use ($startDate) {
                    $join->on('td.transaction_id', '=', 't.id')
                         ->where('t.created_at', '>=', $startDate);
                })
                ->groupBy([
                    'items.id',
                    'items.name',
                    'items.code',
                    'items.category_id',
                    'items.cost_price',
                    'items.selling_price',
                    'items.stock',
                    'items.picture',
                    'items.created_at',
                    'items.updated_at'
                ]);

            // Map and filter items
            $analyses = $query->get()->map(function($item) {
                $avgSales = $item->avg_daily_sales;
                $status = 'NORMAL';
                
                if ($avgSales >= 3.0) {
                    $status = 'FAST';
                } elseif ($avgSales <= 0.5) {
                    $status = 'SLOW';
                }
                
                $item->movement_status = $status;
                return $item;
            });

            if ($status !== 'all') {
                $analyses = $analyses->filter(function($item) use ($status) {
                    return $item->movement_status === strtoupper($status);
                })->values();
            }

            // Create new Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Analisis Pergerakan Barang');

            // Set headers
            $headers = [
                'A1' => ['No', 8],
                'B1' => ['Kode Barang', 15],
                'C1' => ['Nama Barang', 30],
                'D1' => ['Kategori', 20],
                'E1' => ['Stok', 12],
                'F1' => ['Terjual (30 Hari)', 15],
                'G1' => ['Rata-rata/Hari', 15],
                'H1' => ['Status', 15],
                'I1' => ['Estimasi Habis', 15],
                'J1' => ['Rekomendasi', 20]
            ];

            foreach ($headers as $cell => list($value, $width)) {
                $sheet->setCellValue($cell, $value);
                $column = substr($cell, 0, 1);
                $sheet->getColumnDimension($column)->setWidth($width);
            }

            // Style header row
            $sheet->getStyle('A1:J1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2F8444']
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

            // Fill data
            $row = 2;
            foreach ($analyses as $index => $analysis) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $analysis->item->code);
                $sheet->setCellValue('C' . $row, $analysis->item->name);
                $sheet->setCellValue('D' . $row, $analysis->item->category->name);
                $sheet->setCellValue('E' . $row, $analysis->current_stock);
                $sheet->setCellValue('F' . $row, $analysis->total_sold_30_days);
                $sheet->setCellValue('G' . $row, number_format($analysis->avg_daily_sales, 2));
                $sheet->setCellValue('H' . $row, $analysis->movement_status);
                $sheet->setCellValue('I' . $row, $analysis->days_until_empty ?? 'Tidak bergerak');
                $sheet->setCellValue('J' . $row, $analysis->recommendation);

                // Status color coding
                $statusColor = match($analysis->movement_status) {
                    'FAST' => '00B050',   // Green
                    'SLOW' => 'FF0000',   // Red
                    default => 'FFB800'    // Yellow
                };

                $sheet->getStyle('H' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => $statusColor]]
                ]);

                // Row styling
                $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F8F9FA']
                        ]
                    ]);
                }

                $row++;
            }

            // Output file
            $writer = new Xlsx($spreadsheet);
            $filename = 'Analisis_Pergerakan_Barang_' . date('Y-m-d') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }
}