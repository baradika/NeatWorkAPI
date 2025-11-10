<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InspectTable extends Command
{
    protected $signature = 'inspect:table {table} {--show-indexes}';
    protected $description = 'Inspect table structure';

    public function handle()
    {
        $table = $this->argument('table');
        
        // Show table structure
        $this->info("Structure of table: $table");
        $columns = DB::select("SHOW COLUMNS FROM `$table`");
        $this->table(
            ['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'],
            collect($columns)->map(function($col) {
                return [
                    'Field' => $col->Field,
                    'Type' => $col->Type,
                    'Null' => $col->Null,
                    'Key' => $col->Key,
                    'Default' => $col->Default ?? 'NULL',
                    'Extra' => $col->Extra,
                ];
            })
        );

        if ($this->option('show-indexes')) {
            $this->info("\nIndexes for table: $table");
            $indexes = DB::select("SHOW INDEX FROM `$table`");
            if (count($indexes) > 0) {
                $this->table(
                    ['Table', 'Non_unique', 'Key_name', 'Seq_in_index', 'Column_name', 'Collation', 'Cardinality', 'Sub_part', 'Packed', 'Null', 'Index_type'],
                    collect($indexes)->map(function($idx) {
                        return [
                            'Table' => $idx->Table,
                            'Non_unique' => $idx->Non_unique,
                            'Key_name' => $idx->Key_name,
                            'Seq_in_index' => $idx->Seq_in_index,
                            'Column_name' => $idx->Column_name,
                            'Collation' => $idx->Collation,
                            'Cardinality' => $idx->Cardinality,
                            'Sub_part' => $idx->Sub_part ?? 'NULL',
                            'Packed' => $idx->Packed ?? 'NULL',
                            'Null' => $idx->Null,
                            'Index_type' => $idx->Index_type,
                        ];
                    })
                );
            } else {
                $this->warn("No indexes found for table: $table");
            }
        }
    }
}
