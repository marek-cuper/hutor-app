<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeederTags extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Tag::insert([
            [
                'id' => '1',
                'name' => 'Sport',
            ],
            [
                'id' => '2',
                'name' => 'Politika',
            ],
            [
                'id' => '3',
                'name' => 'Zdravie',
            ],
            [
                'id' => '4',
                'name' => 'Novinky',
            ],
            [
                'id' => '5',
                'name' => 'Konicky',
            ],
            [
                'id' => '6',
                'name' => 'Podnikanie',
            ],
            [
                'id' => '7',
                'name' => 'Vojna',
            ],
            [
                'id' => '8',
                'name' => 'Zvieratka',
            ],
            [
                'id' => '9',
                'name' => 'Humor',
            ],
            [
                'id' => '10',
                'name' => 'Anketa',
            ],
            [
                'id' => '11',
                'name' => 'Film/Serial',
            ],
            [
                'id' => '12',
                'name' => 'Vozidla',
            ],
            [
                'id' => '13',
                'name' => 'Urob si sam',
            ],
            [
                'id' => '14',
                'name' => 'Varenie/Pecenie',
            ],
            [
                'id' => '15',
                'name' => 'Rastliny',
            ],
            [
                'id' => '16',
                'name' => 'Hry',
            ],
            [
                'id' => '17',
                'name' => 'Zamestnanie',
            ],
            [
                'id' => '18',
                'name' => 'Skola',
            ],
            [
                'id' => '19',
                'name' => 'Deti',
            ],
            [
                'id' => '20',
                'name' => 'Umenie',
            ],

        ]);
    }
}
