<?php

namespace Database\Seeders;

use App\Models\InvitationCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InvitationCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Cetak', 'slug' => 'cetak', 'description' => 'Undangan cetak berkualitas tinggi'],
            ['name' => 'Video', 'slug' => 'video', 'description' => 'Undangan digital berbasis video'],
            ['name' => 'Website', 'slug' => 'website', 'description' => 'Undangan digital berbasis website'],
        ];

        foreach ($categories as $category) {
            InvitationCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
