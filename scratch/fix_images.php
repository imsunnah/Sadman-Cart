<?php

use App\Models\Product;
use App\Models\ProductImage;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = 0;
Product::where('image', 'like', 'http://localhost%')->get()->each(function($p) use (&$count) {
    $p->image = str_replace('http://localhost', '', $p->image);
    $p->save();
    $count++;
});

ProductImage::where('image', 'like', 'http://localhost%')->get()->each(function($pi) use (&$count) {
    $pi->image = str_replace('http://localhost', '', $pi->image);
    $pi->save();
    $count++;
});

echo "Fixed $count images.";
