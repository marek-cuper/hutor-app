<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeederRegions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Region::insert([
            [
                'id' => '1',
                'name' => 'Bratislava',
            ],
            [
                'id' => '2',
                'name' => 'Záhorie',
            ],
            [
                'id' => '3',
                'name' => 'Podunajsko',
            ],
            [
                'id' => '4',
                'name' => 'Dolné Považie',
            ],
            [
                'id' => '5',
                'name' => 'Stredné Považie',
            ],
            [
                'id' => '6',
                'name' => 'Horné Považie',
            ],
            [
                'id' => '7',
                'name' => 'Dolná Nitra',
            ],
            [
                'id' => '8',
                'name' => 'Horná Nitra',
            ],
            [
                'id' => '9',
                'name' => 'Tekov/Dolné Pohronie',
            ],
            [
                'id' => '10',
                'name' => 'Hont',
            ],
            [
                'id' => '11',
                'name' => 'Kysuce',
            ],
            [
                'id' => '12',
                'name' => 'Orava',
            ],
            [
                'id' => '13',
                'name' => 'Turiec',
            ],
            [
                'id' => '14',
                'name' => 'Liptov',
            ],
            [
                'id' => '15',
                'name' => 'Horehronie',
            ],
            [
                'id' => '16',
                'name' => 'Podpoľanie/Pohronie',
            ],
            [
                'id' => '17',
                'name' => 'Novohrad',
            ],
            [
                'id' => '18',
                'name' => 'Gemer',
            ],
            [
                'id' => '19',
                'name' => 'Tatry',
            ],
            [
                'id' => '20',
                'name' => 'Zamagurie',
            ],
            [
                'id' => '21',
                'name' => 'Spiš',
            ],
            [
                'id' => '22',
                'name' => 'Košice',
            ],
            [
                'id' => '23',
                'name' => 'Šariš',
            ],
            [
                'id' => '24',
                'name' => 'Horný Zemplín',
            ],
            [
                'id' => '25',
                'name' => 'Dolný Zemplín',
            ],
        ]);
    }
}
