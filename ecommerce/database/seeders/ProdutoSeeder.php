<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;
use App\Models\Marca;
use App\Models\Tipo;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar algumas marcas e tipos se não existirem
        $marcaA = Marca::firstOrCreate(['Nome' => 'Marca A']);
        $marcaB = Marca::firstOrCreate(['Nome' => 'Marca B']);
        $marcaC = Marca::firstOrCreate(['Nome' => 'Marca C']);

        $tipoA = Tipo::firstOrCreate(['Nome' => 'Camiseta', 'Descricao' => 'Parte superior']);
        $tipoB = Tipo::firstOrCreate(['Nome' => 'Calça', 'Descricao' => 'Parte inferior']);
        $tipoC = Tipo::firstOrCreate(['Nome' => 'Acessório', 'Descricao' => 'Acessório variado']);

        $samples = [
            ['Tamanho' => 'P', 'Cor' => 'Preto', 'Genero' => 'Masculino', 'id_marca' => $marcaA->id, 'id_tipo' => $tipoA->id, 'Valor' => 49.90],
            ['Tamanho' => 'M', 'Cor' => 'Branco', 'Genero' => 'Feminino', 'id_marca' => $marcaB->id, 'id_tipo' => $tipoA->id, 'Valor' => 59.90],
            ['Tamanho' => 'G', 'Cor' => 'Azul', 'Genero' => 'Unisex', 'id_marca' => $marcaC->id, 'id_tipo' => $tipoB->id, 'Valor' => 89.90],
            ['Tamanho' => 'M', 'Cor' => 'Vermelho', 'Genero' => 'Masculino', 'id_marca' => $marcaA->id, 'id_tipo' => $tipoB->id, 'Valor' => 99.90],
            ['Tamanho' => 'G', 'Cor' => 'Cinza', 'Genero' => 'Feminino', 'id_marca' => $marcaB->id, 'id_tipo' => $tipoA->id, 'Valor' => 39.90],
            ['Tamanho' => 'P', 'Cor' => 'Verde', 'Genero' => 'Unisex', 'id_marca' => $marcaC->id, 'id_tipo' => $tipoC->id, 'Valor' => 19.90],
            ['Tamanho' => 'PP', 'Cor' => 'Roxo', 'Genero' => 'Masculino', 'id_marca' => $marcaA->id, 'id_tipo' => $tipoC->id, 'Valor' => 14.90],
            ['Tamanho' => 'M', 'Cor' => 'Amarelo', 'Genero' => 'Feminino', 'id_marca' => $marcaB->id, 'id_tipo' => $tipoA->id, 'Valor' => 69.90],
            ['Tamanho' => 'G', 'Cor' => 'Marrom', 'Genero' => 'Unisex', 'id_marca' => $marcaC->id, 'id_tipo' => $tipoB->id, 'Valor' => 79.90],
            ['Tamanho' => 'P', 'Cor' => 'Laranja', 'Genero' => 'Masculino', 'id_marca' => $marcaA->id, 'id_tipo' => $tipoB->id, 'Valor' => 29.90],
            ['Tamanho' => 'M', 'Cor' => 'Rosa', 'Genero' => 'Feminino', 'id_marca' => $marcaB->id, 'id_tipo' => $tipoC->id, 'Valor' => 24.90],
            ['Tamanho' => 'G', 'Cor' => 'Preto', 'Genero' => 'Unisex', 'id_marca' => $marcaC->id, 'id_tipo' => $tipoA->id, 'Valor' => 129.90],
        ];

        foreach ($samples as $i => $data) {
            $prod = Produto::create(array_merge($data, [
                'imagem' => [
                    // usamos imagens placeholder públicas — substitua por arquivos locais se preferir
                    "https://via.placeholder.com/600x400?text=Produto+".($i+1)
                ]
            ]));
        }
    }
}
