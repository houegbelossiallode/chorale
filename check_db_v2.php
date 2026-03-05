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
    try {
        $columns = DB::select("
            SELECT column_name, data_type, column_default
            FROM information_schema.columns 
            WHERE table_schema = 'public' AND table_name = ?
            ORDER BY column_name
        ", [$tableName]);

        if (empty($columns)) {
            echo "No columns found (table might not exist in public schema).\n";
            return;
        }

        foreach ($columns as $column) {
            echo sprintf(
                "  %-20s | %-15s | %s\n",
                $column->column_name,
                $column->data_type,
                $column->column_default
            );
        }
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

checkTable('users');
checkTable('menus');
checkTable('sous_menus');
checkTable('role_permissions');
