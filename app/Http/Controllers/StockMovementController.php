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

        return view('stock-movement.index', [
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

        return view('stock-movement.fast-moving', [
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

        return view('stock-movement.slow-moving', [
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

            // Add report header
            $sheet->mergeCells('A1:J1');
            $sheet->setCellValue('A1', 'LAPORAN ANALISIS PERGERAKAN BARANG');
            $sheet->getStyle('A1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

            // Add report metadata
            $sheet->mergeCells('A2:J2');
            $sheet->setCellValue('A2', 'Periode Analisis: ' . $startDate->format('d/m/Y') . ' - ' . now()->format('d/m/Y') . ' (30 Hari Terakhir)');
            $sheet->getStyle('A2')->applyFromArray([
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
            
            // Add data timestamp
            $sheet->mergeCells('A3:J3');
            $sheet->setCellValue('A3', 'Data Diambil pada: ' . now()->format('d/m/Y H:i:s'));
            $sheet->getStyle('A3')->applyFromArray([
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            // Add status summary
            $fastCount = $analyses->where('movement_status', 'FAST')->count();
            $normalCount = $analyses->where('movement_status', 'NORMAL')->count();
            $slowCount = $analyses->where('movement_status', 'SLOW')->count();

            $sheet->mergeCells('A4:J4');
            $sheet->setCellValue('A4', sprintf(
                'Ringkasan Status Pergerakan: %d Fast Moving | %d Normal | %d Slow Moving',
                $fastCount, $normalCount, $slowCount
            ));
            $sheet->getStyle('A3')->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            // Add empty row for spacing
            $sheet->mergeCells('A4:J4');

            // Set headers
            $headers = [
                'A5' => ['No', 8],
                'B5' => ['Kode Barang', 15],
                'C5' => ['Nama Barang', 30],
                'D5' => ['Kategori', 20],
                'E5' => ['Stok', 12],
                'F5' => ['Terjual (30 Hari)', 15],
                'G5' => ['Rata-rata/Hari', 15],
                'H5' => ['Status', 15],
                'I5' => ['Estimasi Habis', 15],
                'J5' => ['Rekomendasi', 20]
            ];

            foreach ($headers as $cell => list($value, $width)) {
                $sheet->setCellValue($cell, $value);
                $column = substr($cell, 0, 1);
                $sheet->getColumnDimension($column)->setWidth($width);
            }

            // Style header row
            $sheet->getStyle('A5:J5')->applyFromArray([
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
            $row = 6;
            foreach ($analyses as $index => $analysis) {
                $daysUntilEmpty = $analysis->avg_daily_sales > 0 ? ceil($analysis->stock / $analysis->avg_daily_sales) : null;
                $recommendation = match($analysis->movement_status) {
                    'FAST' => 'Sarankan Restock',
                    'SLOW' => 'Sarankan Evaluasi',
                    default => 'Pantau Pergerakan'
                };

                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $analysis->code);
                $sheet->setCellValue('C' . $row, $analysis->name);
                $sheet->setCellValue('D' . $row, $analysis->category->name);
                $sheet->setCellValue('E' . $row, $analysis->stock);
                $sheet->setCellValue('F' . $row, $analysis->total_sold);
                $sheet->setCellValue('G' . $row, number_format($analysis->avg_daily_sales, 2));
                $sheet->setCellValue('H' . $row, $analysis->movement_status);
                $sheet->setCellValue('I' . $row, $daysUntilEmpty ? "$daysUntilEmpty hari" : 'Tidak bergerak');
                $sheet->setCellValue('J' . $row, $recommendation);

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

            // Add legend
            $row += 2;
            $sheet->mergeCells("A{$row}:J{$row}");
            $sheet->setCellValue("A{$row}", 'KETERANGAN:');
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => ['bold' => true]
            ]);

            $row++;
            $legends = [
                'Fast Moving (> 3 unit/hari)' => '00B050',
                'Normal (0.5 - 3 unit/hari)' => 'FFB800',
                'Slow Moving (< 0.5 unit/hari)' => 'FF0000'
            ];

            foreach ($legends as $text => $color) {
                $sheet->mergeCells("A{$row}:J{$row}");
                $sheet->setCellValue("A{$row}", $text);
                $sheet->getStyle("A{$row}")->applyFromArray([
                    'font' => ['color' => ['rgb' => $color]]
                ]);
                $row++;
            }

            // Add footer
            $row += 2;
            $sheet->mergeCells("A{$row}:J{$row}");
            $sheet->setCellValue("A{$row}", 'Laporan dibuat pada: ' . now()->format('d/m/Y H:i:s'));
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
            ]);

            // Protect worksheet
            $sheet->getProtection()->setSheet(true);
            $sheet->getProtection()->setSort(true);
            $sheet->getProtection()->setInsertRows(true);
            $sheet->getProtection()->setDeleteRows(true);

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