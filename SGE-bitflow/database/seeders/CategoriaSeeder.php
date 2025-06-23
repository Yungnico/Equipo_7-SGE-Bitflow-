<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'categoria1'],
            ['nombre' => 'categoria2'],
            ['nombre' => 'categoria3'],
            ['nombre' => 'categoria4'],
        ];

        $now = Carbon::now();

        foreach ($categorias as &$categoria) {
            $categoria['created_at'] = $now;
            $categoria['updated_at'] = $now;
        }

        DB::table('categorias')->insert($categorias);
    }
}
