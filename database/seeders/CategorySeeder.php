<?php

namespace Database\Seeders;
use Faker\Generator as Faker;
use App\Models\Category;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $labels = ['FrontEnd','BackEnd','UI/UX','FullStack','Designer'];

        foreach($labels as $label){
            $category = new Category();
            
            $category->label = $label;
            $category->color = $faker->hexColor();
            
            $category->save();
        }
    }
}
