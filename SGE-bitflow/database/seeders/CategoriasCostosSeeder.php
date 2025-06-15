<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaCostos;
use App\Models\SubCategoriaCostos;

class CategoriasCostosSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Transporte' => ['Combustible', 'Mantenimiento', 'Peajes'],
            'Alimentación' => ['Desayuno', 'Almuerzo', 'Cena'],
            'Oficina' => ['Papelería', 'Mobiliario', 'Tecnología'],
        ];

        foreach ($categorias as $nombreCategoria => $subcategorias) {
            $categoria = CategoriaCostos::create([
                'nombre' => $nombreCategoria,
            ]);

            echo "Creada categoría: {$categoria->nombre}\n";

            foreach ($subcategorias as $nombreSubcategoria) {
                $sub = SubCategoriaCostos::create([
                    'nombre' => $nombreSubcategoria,
                    'categoria_id' => $categoria->id,
                ]);
            }
        }
    }
}
