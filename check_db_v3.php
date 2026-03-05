<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$output = "";

function checkTable($tableName)
{
    global $output;
    $output .= "--- Table: $tableName ---\n";
    try {
        $columns = DB::select("
            SELECT column_name, data_type, column_default
            FROM information_schema.columns 
            WHERE table_schema = 'public' AND table_name = ?
            ORDER BY column_name
        ", [$tableName]);

        if (empty($columns)) {
            $output .= "No columns found.\n";
            return;
        }

        foreach ($columns as $column) {
            $output .= sprintf(
                "  %-25s | %-20s | %s\n",
                $column->column_name,
                $column->data_type,
                $column->column_default
            );
        }
    } catch (\Exception $e) {
        $output .= "Error: " . $e->getMessage() . "\n";
    }
    $output .= "\n";
}

checkTable('users');
checkTable('menus');
checkTable('sous_menus');
checkTable('role_permissions');

file_put_contents('db_structure.txt', $output);
echo "Done. Check db_structure.txt\n";
