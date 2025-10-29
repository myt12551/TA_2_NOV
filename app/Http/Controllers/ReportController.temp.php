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

    public function exportSale(Request $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $query = Item::with(['category', 'transactionDetails' => function ($query) use ($startDate, $endDate) {
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
        ), 0) as total_revenue', [$startDate, $endDate]);

        // Get items with their transaction details
        $items = $query->get();

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penjualan');

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Teaching Factory')
            ->setLastModifiedBy('Teaching Factory')
            ->setTitle('Laporan Penjualan Teaching Factory')
            ->setSubject('Laporan Penjualan ' . $startDate->format('F Y'));

        // Header style
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 14,
                'name' => 'Calibri',
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        // Title
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN TEACHING FACTORY');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Period information
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

        // Add spacing
        $sheet->getRowDimension(3)->setRowHeight(10);

        // Set column headers
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

        // Header row styling
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

        // Fill data
        $row = 5;
        $no = 1;
        $totalRevenue = 0;
        $totalProfit = 0;

        foreach ($items as $item) {
            $totalSold = $item->total_sold ?? 0;
            $itemRevenue = (float)($item->total_revenue ?? 0);
            $profit = $itemRevenue - ($totalSold * $item->cost_price);
            
            $totalRevenue += $itemRevenue;
            $totalProfit += $profit;

            // Fill row data
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->code);
            $sheet->setCellValue('C' . $row, $item->category->name ?? '-');
            $sheet->setCellValue('D' . $row, $item->name);
            $sheet->setCellValue('E' . $row, 'Rp ' . number_format($item->cost_price, 0, ',', '.'));
            $sheet->setCellValue('F' . $row, 'Rp ' . number_format($item->selling_price, 0, ',', '.'));
            $sheet->setCellValue('G' . $row, $totalSold);
            $sheet->setCellValue('H' . $row, $item->stock);
            $sheet->setCellValue('I' . $row, 'Rp ' . number_format($itemRevenue, 0, ',', '.'));
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
                ]
            ]);

            // Alignment
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('E' . $row . ':F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('G' . $row . ':H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $row . ':J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $row++;
        }

        // Add totals row
        $totalRow = $row + 1;
        $sheet->setCellValue('A' . $totalRow, 'TOTAL');
        $sheet->mergeCells('A' . $totalRow . ':H' . $totalRow);
        $sheet->setCellValue('I' . $totalRow, 'Rp ' . number_format($totalRevenue, 0, ',', '.'));
        $sheet->setCellValue('J' . $totalRow, 'Rp ' . number_format($totalProfit, 0, ',', '.'));

        // Style totals row
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

        // Create Excel file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Penjualan_' . $startDate->format('d_m_Y') . '_' . $endDate->format('d_m_Y') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}