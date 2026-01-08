<?php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Parfum Pria',
                'slug' => 'parfum-pria',
                'description' => 'Koleksi parfum khusus pria dengan berbagai aroma maskulin',
                'is_active' => true,
            ],
            [
                'name' => 'Parfum Wanita',
                'slug' => 'parfum-wanita',
                'description' => 'Parfum wanita dengan aroma elegan, lembut, dan feminin',
                'is_active' => true,
            ],
            [
                'name' => 'Unisex',
                'slug' => 'unisex',
                'description' => 'Parfum yang cocok digunakan untuk pria maupun wanita',
                'is_active' => true,
            ],
            [
                'name' => 'Eau de Parfum (EDP)',
                'slug' => 'eau-de-parfum',
                'description' => 'Parfum dengan ketahanan aroma tinggi dan konsentrasi lebih kuat',
                'is_active' => true,
            ],
            [
                'name' => 'Eau de Toilette (EDT)',
                'slug' => 'eau-de-toilette',
                'description' => 'Parfum dengan aroma lebih ringan, cocok untuk penggunaan sehari-hari',
                'is_active' => true,
            ],
            [
                'name' => 'Refill Parfum',
                'slug' => 'refill-parfum',
                'description' => 'Layanan isi ulang parfum dengan berbagai pilihan aroma',
                'is_active' => true,
            ],
            [
                'name' => 'Body Mist',
                'slug' => 'body-mist',
                'description' => 'Body mist dengan aroma segar yang ringan digunakan setiap hari',
                'is_active' => true,
            ],
            [
                'name' => 'Aksesoris Parfum',
                'slug' => 'aksesoris-parfum',
                'description' => 'Botol parfum, roll on, dan perlengkapan parfum lainnya',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ Categories seeded successfully!');
    }
}