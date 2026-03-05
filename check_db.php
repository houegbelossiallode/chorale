<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function checkTable($tableName)
{
    echo "--- Table: $tableName ---\n";
    if (!Schema::hasTable($tableName)) {
        echo "Table does not exist!\n";
        return;
    }

    $columns = DB::select("
        SELECT column_name, data_type 
        FROM information_schema.columns 
        WHERE table_name = ?
    ", [$tableName]);

    foreach ($columns as $column) {
        echo "{$column->column_name}: {$column->data_type}\n";
    }
    echo "\n";
}

checkTable('menus');
checkTable('sous_menus');
checkTable('role_permissions');
checkTable('users');
