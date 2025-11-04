// SupplierProduct Routes
Route::prefix('supplier-products')->name('supplier-products.')->middleware('auth')->group(function () {
    Route::get('/', [SupplierProductController::class, 'index'])->name('index');
    Route::get('/create', [SupplierProductController::class, 'create'])->name('create');
    Route::post('/', [SupplierProductController::class, 'store'])->name('store');
    Route::get('/{supplierProduct}/edit', [SupplierProductController::class, 'edit'])->name('edit');
    Route::put('/{supplierProduct}', [SupplierProductController::class, 'update'])->name('update');
    Route::delete('/{supplierProduct}', [SupplierProductController::class, 'destroy'])->name('destroy');
    Route::get('/supplier/{supplier}', [SupplierProductController::class, 'getBySupplier'])->name('by-supplier');
});