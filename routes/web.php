<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MarketplaceCartController;
use App\Http\Controllers\MarketplaceOrderController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsSupervisor;

use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\StockMovementController;


Route::middleware('auth')->group(function () {
    // Stock Movement Analysis Routes under inventory
    Route::prefix('inventory')->group(function () {
        Route::prefix('stock-movement')->name('inventory.stock-movement.')->group(function () {
            Route::get('/', [StockMovementController::class, 'index'])->name('index');
            Route::get('/fast-moving', [StockMovementController::class, 'fastMoving'])->name('fast-moving');
            Route::get('/slow-moving', [StockMovementController::class, 'slowMoving'])->name('slow-moving');
            Route::get('/settings', [StockMovementController::class, 'settings'])->name('settings');
            Route::put('/settings', [StockMovementController::class, 'updateSettings'])->name('settings.update');
            Route::get('/analyze', [StockMovementController::class, 'analyze'])->name('analyze');
            Route::get('/export', [StockMovementController::class, 'export'])->name('export');
        });
    });
    
    // Procurement dashboard
    // Displays a consolidated table for the entire PR–PO–GR–Invoice workflow.
    Route::get('/procurement', [ProcurementController::class, 'index'])
        ->name('procurement.index');
    // Purchase Requests
    Route::resource('purchase-requests', PurchaseRequestController::class);
    Route::post('purchase-requests/{purchase_request}/approve', [PurchaseRequestController::class, 'approve'])
        ->name('purchase-requests.approve');
    Route::post('purchase-requests/{purchase_request}/reject', [PurchaseRequestController::class, 'reject'])
        ->name('purchase-requests.reject');

    // Purchase Orders
    // Use resource with limited actions for create, store, and show
    Route::resource('purchase-orders', PurchaseOrderController::class)->only(['create', 'store', 'show']);
    // Validate PO (supplier confirmation)
    Route::post('purchase-orders/{purchase_order}/validate', [PurchaseOrderController::class, 'validatePO'])
        ->name('purchase-orders.validate');

        // API: Get products provided by a supplier (used to prefill PO items when supplier is selected)
        Route::get('purchase-orders/get-items/{supplier}', [PurchaseOrderController::class, 'getItemsBySupplier'])
            ->name('purchase-orders.get-items');

    // Goods Receipts
    // Provide custom routes for creating and storing goods receipts tied to a purchase order.
    Route::get('goods-receipts/{purchaseOrder}/create', [GoodsReceiptController::class, 'create'])
        ->name('goods-receipts.create');
    Route::post('goods-receipts/{purchaseOrder}', [GoodsReceiptController::class, 'store'])
        ->name('goods-receipts.store');
    Route::resource('goods-receipts', GoodsReceiptController::class)->except(['create', 'store']);

    // Invoices
    // Provide custom routes for creating and storing invoices tied to a purchase order.
    Route::get('invoices/{purchaseOrder}/create', [InvoiceController::class, 'create'])
        ->name('invoices.create');
    Route::post('invoices/{purchaseOrder}', [InvoiceController::class, 'store'])
        ->name('invoices.store');
    Route::resource('invoices', InvoiceController::class)->except(['create', 'store']);
    Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])
        ->name('invoices.mark-paid');

    // Inventory Records (Pemeriksaan & Pencatatan)
    Route::get('inventory-records/{goods_receipt}/create', [\App\Http\Controllers\InventoryRecordController::class, 'create'])
        ->name('inventory-records.create');
    Route::post('inventory-records/{goods_receipt}', [\App\Http\Controllers\InventoryRecordController::class, 'store'])
        ->name('inventory-records.store');
    Route::resource('inventory-records', \App\Http\Controllers\InventoryRecordController::class)->except(['create', 'store']);
});

Route::delete('purchase-orders/delete-all', [PurchaseOrderController::class, 'deleteAll'])->name('purchase-orders.delete-all');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

/* ============================
   MARKETPLACE (Publik)
   ============================ */

// Etalase marketplace (publik)
Route::get('/customer/dashboard', [MarketplaceController::class, 'index'])->name('customer.dashboard');
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');

// Detail produk
Route::get('/marketplace/items/{item}', [MarketplaceController::class, 'show'])->name('marketplace.show');

// Keranjang (session-based)
Route::get('/marketplace/cart', [MarketplaceCartController::class, 'index'])->name('marketplace.cart');
Route::post('/marketplace/cart/add', [MarketplaceCartController::class, 'add'])->name('marketplace.cart.add');
Route::post('/marketplace/cart/update', [MarketplaceCartController::class, 'update'])->name('marketplace.cart.update');
Route::post('/marketplace/cart/remove', [MarketplaceCartController::class, 'remove'])->name('marketplace.cart.remove');
Route::post('/marketplace/cart/clear', [MarketplaceCartController::class, 'clear'])->name('marketplace.cart.clear');

// Checkout (hanya customer terautentikasi)
Route::middleware(['auth', \App\Http\Middleware\IsCustomer::class])->group(function () {
    Route::get('/marketplace/checkout', [MarketplaceOrderController::class, 'create'])->name('marketplace.checkout');
    Route::post('/marketplace/checkout', [MarketplaceOrderController::class, 'store'])->name('marketplace.checkout.store');
    Route::get('/marketplace/orders', [MarketplaceOrderController::class, 'index'])->name('marketplace.order.index');
    Route::get('/marketplace/order/{code}', [MarketplaceOrderController::class, 'show'])->name('marketplace.order.show');

    // Logout khusus customer
    Route::post('/customer/logout', [AuthenticatedSessionController::class, 'destroyCustomer'])->name('customer.logout');
});

/* ============================
   GUEST (Register & Login customer)
   ============================ */
Route::middleware('guest')->group(function () {
    Route::get('/customer/register', [RegisteredUserController::class, 'createCustomer'])->name('customer.register');
    Route::post('/customer/register', [RegisteredUserController::class, 'storeCustomer'])->name('customer.register.post');
    Route::get('/customer/login', [AuthenticatedSessionController::class, 'createCustomer'])->name('customer.login');
    Route::post('/customer/login', [AuthenticatedSessionController::class, 'storeCustomer'])->name('customer.login.post');
});

Route::post('transaction/marketplace-online-orders/{order}/process', [TransactionController::class, 'processMarketplaceOrder']);


/* ============================
   AUTH (internal apps)
   ============================ */
Route::middleware('auth')->group(function () {
    // Halaman utama: customer diarahkan ke dashboard marketplace, lainnya ke dashboard internal
    Route::get('/', function () {
        $user = Auth::user();
        return ($user && $user->role === 'customer')
            ? redirect()->route('customer.dashboard')
            : app(DashboardController::class)->index();
    })->name('dashboard');

    /*
     * ONLINE ORDERS
     * Kasir melihat daftar pesanan online (status 'debt') dan memprosesnya menjadi 'paid'.
     */
    Route::get('/transaction/online-orders', [TransactionController::class, 'onlineOrders'])
        ->name('transaction.online');
    Route::post('/transaction/online-orders/{order}/process', [TransactionController::class, 'processOnline'])
    ->name('transaction.online.process');
                        


    /*
     * TRANSACTIONS
     * Resource transaksi dengan pengecualian create/edit/update, serta rute tambahan
     * untuk get-invoice, get-items dan save transaksi yang dikelompokkan dalam prefix 'transaction'.
     */
    Route::prefix('transaction')->group(function () {
        Route::get('/get-invoice', [TransactionController::class, 'get_invoice'])->name('transaction.get_invoice');
        Route::get('/get-items', [TransactionController::class, 'get_items'])->name('transaction.get_items');
        Route::post('/save', [TransactionController::class, 'save_transaction'])->name('transaction.save');
    });
    Route::resource('transaction', TransactionController::class)->except(['create','edit','update']);

    /*
     * INVENTORY
     */
    Route::prefix('inventory')->group(function () {
        Route::resource('category', CategoryController::class)->except('show');
        Route::resource('supplier', SupplierController::class);
        Route::resource('item', ItemController::class);
    });

    /*
     * USER MANAGEMENT (SUPERVISOR ONLY)
     */
    Route::resource('user', UserController::class)
         ->except('show')
         ->middleware(IsSupervisor::class);

    /*
     * CUSTOMER CRUD (internal)
     */
    Route::resource('customer', CustomerController::class);

    /*
     * REPORT
     */
    Route::prefix('report')->group(function () {
        Route::get('/transaction/filter', [ReportController::class, 'filter'])->name('report.transaction.filter');
        Route::get('/export-sale', [ReportController::class, 'exportSale'])->name('report.export-sale');
        Route::resource('transaction', ReportController::class)
             ->names('report.transaction')
             ->only(['index','show']);
    });

    /*
     * ABSENCE & PAYMENT METHOD
     */
    Route::resource('absence', AbsenceController::class)->except('show');
    Route::resource('payment-method', PaymentMethodController::class)->except('show');

    /*
     * PROFILE
     */
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    /*
     * CART internal POS
     */
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/', [CartController::class, 'store'])->name('cart.store');
        Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');
        Route::get('/count-stock/{item}', [CartController::class, 'count_stock']);
        Route::delete('/{cart}', [CartController::class, 'destroy']);
        Route::get('/{item:code}', [ItemController::class, 'show']);
        Route::put('/{cart}', [CartController::class, 'update']);
    });

    /*
     * API & PURCHASE ORDERS
     */
    Route::get('/api/suppliers/{supplier}/items', [PurchaseOrderController::class, 'getItemsBySupplier'])
        ->name('po.supplier.items');

    // PURCHASE ORDER – Admin gudang
    Route::middleware(IsAdmin::class)->group(function () {
        Route::resource('purchase-orders', PurchaseOrderController::class)->except('destroy');
        Route::post('purchase-orders/{id}/upload-invoice', [PurchaseOrderController::class, 'uploadInvoice'])->name('purchase-orders.upload-invoice');
        Route::get('purchase-orders/{id}/pdf', [PurchaseOrderController::class, 'exportPDF'])->name('purchase-orders.pdf');
    });

    // PURCHASE ORDER – Supervisor
    Route::middleware(IsSupervisor::class)->group(function () {
        Route::resource('purchase-orders', PurchaseOrderController::class)->only(['index','show']);
        Route::post('purchase-orders/{id}/upload-invoice', [PurchaseOrderController::class, 'uploadInvoice'])->name('purchase-orders.upload-invoice');
        Route::post('purchase-orders/{id}/validate', [PurchaseOrderController::class, 'validatePO'])->name('purchase-orders.validate');
        Route::get('purchase-orders/{id}/pdf', [PurchaseOrderController::class, 'exportPDF'])->name('purchase-orders.pdf');
    });

    // Extra route for export PDF (hindari duplikasi dengan resource)
    Route::get('purchase-orders/{id}/export-pdf', [PurchaseOrderController::class, 'exportPDF'])->name('purchase-orders.export-pdf');
});

// Auth scaffolding
require __DIR__.'/auth.php';
