<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('report.transaction.index', [
            'payment_methods' => PaymentMethod::orderBy('name', 'asc')->get(),
            'transactions' => Transaction::orderBy('updated_at', 'desc')->get(),
            'type' => 'show'
        ]);
    }

    public function show(Transaction $transaction): View
    {
        $transaction_details = $transaction->transactionDetails()->get();
        return view('report.transaction.show', [
            'transaction_details' => $transaction_details
        ]);
    }

    public function filter(Request $request): View
    {
        $transactions = Transaction::whereDate('updated_at', '>=', $request->start_date)
            ->whereDate('updated_at', '<=', $request->end_date);

        if ($request->payment_method != 'all') {
            $transactions = $transactions->where('payment_method_id', $request->payment_method);
        }

        if ($request->status != 'all') {
            $transactions = $transactions->where('status', $request->status);
        }

        $transactions = $transactions->orderBy('updated_at', 'desc')->get();

        return view('report.transaction.table', [
            'transactions' => $transactions,
            'type' => 'filter'
        ]);
    }

    /**
     * Export sales report to Excel
     */
    public function exportSale(Request $request)
    {
        try {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $items = Item::with(['category', 'transactionDetails' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                });
            }])
            ->withCount(['transactionDetails as total_sold' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                });
            }])
            ->selectRaw('items.*, COALESCE((
                SELECT SUM(td.qty * td.item_price)
                FROM transaction_details td
                JOIN transactions t ON td.transaction_id = t.id
                WHERE td.item_id = items.id
                AND t.created_at BETWEEN ? AND ?
            ), 0) as total_revenue', [$startDate, $endDate])
            ->get();

            // Create Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Laporan Penjualan');

            // Document properties
            $spreadsheet->getProperties()
                ->setCreator('Teaching Factory')
                ->setLastModifiedBy('Teaching Factory')
                ->setTitle('Laporan Penjualan Teaching Factory')
                ->setSubject('Laporan Penjualan ' . $startDate->format('F Y'));

            // Header
            $sheet->setCellValue('A1', 'LAPORAN PENJUALAN TEACHING FACTORY');
            $sheet->mergeCells('A1:J1');
            $sheet->getStyle('A1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'name' => 'Calibri',
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]);
            $sheet->getRowDimension(1)->setRowHeight(30);

            // Period
            $sheet->setCellValue('A2', 'Periode: ' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'));
            $sheet->mergeCells('A2:J2');
            $sheet->getStyle('A2')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ]);
            $sheet->getRowDimension(2)->setRowHeight(25);

            // Spacing
            $sheet->getRowDimension(3)->setRowHeight(10);

            // Column headers at row 4
            $headers = [
                'A4' => ['No.', 8],
                'B4' => ['Kode', 15],
                'C4' => ['Kategori', 20],
                'D4' => ['Nama Barang', 35],
                'E4' => ['Harga Beli', 15],
                'F4' => ['Harga Jual', 15],
                'G4' => ['Terjual', 12],
                'H4' => ['Stok', 12],
                'I4' => ['Total Penjualan', 20],
                'J4' => ['Profit', 20]
            ];

            // Apply headers and column widths
            foreach ($headers as $cell => list($value, $width)) {
                $sheet->setCellValue($cell, $value);
                $column = substr($cell, 0, 1);
                $sheet->getColumnDimension($column)->setWidth($width);
            }

            // Style header row
            $sheet->getStyle('A4:J4')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2F8444'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]);
            $sheet->getRowDimension(4)->setRowHeight(25);

            // Data rows
            $row = 5;
            $no = 1;
            $totalRevenue = 0;
            $totalProfit = 0;
            $totalSold = 0;

            foreach ($items as $item) {
                $currentSold = $item->total_sold ?? 0;
                $currentRevenue = (float)($item->total_revenue ?? 0);
                $profit = $currentRevenue - ($currentSold * (float)$item->cost_price);
                
                $totalSold += $currentSold;
                $totalRevenue += $currentRevenue;
                $totalProfit += $profit;

                // Fill row data
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $item->code);
                $sheet->setCellValue('C' . $row, $item->category->name ?? '-');
                $sheet->setCellValue('D' . $row, $item->name);
                $sheet->setCellValue('E' . $row, 'Rp ' . number_format((float)$item->cost_price, 0, ',', '.'));
                $sheet->setCellValue('F' . $row, 'Rp ' . number_format((float)$item->selling_price, 0, ',', '.'));
                $sheet->setCellValue('G' . $row, $currentSold);
                $sheet->setCellValue('H' . $row, $item->stock);
                $sheet->setCellValue('I' . $row, 'Rp ' . number_format($currentRevenue, 0, ',', '.'));
                $sheet->setCellValue('J' . $row, 'Rp ' . number_format($profit, 0, ',', '.'));

                // Stock level color coding
                $stockStyle = [
                    'font' => [
                        'bold' => true
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ];

                if ($item->stock <= 9) {
                    $stockStyle['font']['color'] = ['rgb' => 'FF0000']; // Red
                } elseif ($item->stock <= 29) {
                    $stockStyle['font']['color'] = ['rgb' => 'FFB800']; // Yellow
                } else {
                    $stockStyle['font']['color'] = ['rgb' => '00B050']; // Green
                }

                $sheet->getStyle('H' . $row)->applyFromArray($stockStyle);

                // Row styling
                $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ]
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ]
                ]);

                // Zebra striping
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F8F9FA'],
                        ],
                    ]);
                }

                // Alignment
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('E' . $row . ':F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('G' . $row . ':H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I' . $row . ':J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $sheet->getRowDimension($row)->setRowHeight(22);
                $row++;
            }

            // Total row
            $totalRow = $row + 1;
            $sheet->setCellValue('A' . $totalRow, 'TOTAL');
            $sheet->mergeCells('A' . $totalRow . ':H' . $totalRow);
            $sheet->setCellValue('I' . $totalRow, 'Rp ' . number_format($totalRevenue, 0, ',', '.'));
            $sheet->setCellValue('J' . $totalRow, 'Rp ' . number_format($totalProfit, 0, ',', '.'));

            // Style total row
            $sheet->getStyle('A' . $totalRow . ':J' . $totalRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    'top' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                    ]
                ]
            ]);

            $sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $totalRow . ':J' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getRowDimension($totalRow)->setRowHeight(25);

            // Page setup
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);
            
            $sheet->getPageMargins()
                ->setTop(0.4)
                ->setRight(0.4)
                ->setLeft(0.4)
                ->setBottom(0.4);

            $sheet->getSheetView()->setZoomScale(85);

            // Footer
            $sheet->getHeaderFooter()
                ->setOddFooter('&L&B' . date('d/m/Y H:i') . '&R&P of &N');

            // Output Excel file
            $writer = new Xlsx($spreadsheet);
            $filename = 'Laporan_Penjualan_' . $startDate->format('d_m_Y') . '_' . $endDate->format('d_m_Y') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return back()->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }
}