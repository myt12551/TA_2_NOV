<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\Item;

// Pastikan direktori exists
Storage::disk('public')->makeDirectory('items');

// Ambil semua item yang memiliki gambar
$items = Item::whereNotNull('picture')->get();

foreach ($items as $item) {
    $filename = basename($item->picture);
    
    // Cek jika file ada di lokasi lama
    $oldPath = storage_path('app/public/' . $item->picture);
    $newPath = storage_path('app/public/items/' . $filename);
    
    if (file_exists($oldPath) && !file_exists($newPath)) {
        // Pindahkan file ke lokasi baru
        copy($oldPath, $newPath);
        echo "Moved: {$item->picture} to items/{$filename}\n";
        
        // Update database dengan nama file saja
        $item->update(['picture' => $filename]);
        echo "Updated database entry for item {$item->id}\n";
    }
}

echo "Done!\n";