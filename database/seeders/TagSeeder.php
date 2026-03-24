<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Tag::create(['name' => 'Work', 'color' => '#0d6efd']);
        \App\Models\Tag::create(['name' => 'Personal', 'color' => '#198754']);
        \App\Models\Tag::create(['name' => 'Urgent', 'color' => '#dc3545']);
        \App\Models\Tag::create(['name' => 'Meeting', 'color' => '#ffc107']);
    }
}
