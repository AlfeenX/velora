<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Produk Terbaru',
            'Terlaris',
            'Kualitas Premium',
            'Produk Lokal',
            'Edisi Terbatas',
            'Nyaman Dipakai',
            'Anti Slip',
            'Gaya Kasual',
            'Formal & Elegan',
            'Diskon Spesial',
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => Str::slug($tag),
            ]);
        }
    }
}
