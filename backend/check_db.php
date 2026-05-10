<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$app->boot();

use App\Models\Language;
use App\Models\LegalProcedure;

echo "Languages: " . Language::count() . "\n";
echo "Procedures: " . LegalProcedure::count() . "\n";
