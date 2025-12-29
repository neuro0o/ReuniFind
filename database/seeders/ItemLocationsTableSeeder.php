<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemLocationsTableSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['locationName' => 'ASTIF', 'latitude' => 6.032187, 'longitude' => 116.115199],
            ['locationName' => 'DKP Baru', 'latitude' => 6.046531, 'longitude' => 116.130670],
            ['locationName' => 'DKP Lama', 'latitude' => 6.033872, 'longitude' => 116.114845],
            ['locationName' => 'FIS', 'latitude' => 6.035358, 'longitude' => 116.121285],
            ['locationName' => 'FKI', 'latitude' => 6.036381, 'longitude' => 116.122181],
            ['locationName' => 'FKJ', 'latitude' => 6.034957, 'longitude' => 116.121258],
            ['locationName' => 'FPEP', 'latitude' => 6.032565, 'longitude' => 116.112957],
            ['locationName' => 'FPKS', 'latitude' => 6.029632, 'longitude' => 116.118976],
            ['locationName' => 'FPP', 'latitude' => 6.030341, 'longitude' => 116.117726],
            ['locationName' => 'FPT', 'latitude' => 6.036528, 'longitude' => 116.126604],
            ['locationName' => 'FSMP', 'latitude' => 6.037940, 'longitude' => 116.127562],
            ['locationName' => 'FST@FSSA', 'latitude' => 6.032904, 'longitude' => 116.120644],
            ['locationName' => 'FSSK', 'latitude' => 6.031401, 'longitude' => 116.116396],
            ['locationName' => 'KKTF', 'latitude' => 6.043293, 'longitude' => 116.124303],
            ['locationName' => 'KKTM', 'latitude' => 6.041709, 'longitude' => 116.123442],
            ['locationName' => 'KKTPAR', 'latitude' => 6.046520, 'longitude' => 116.125274],
            ['locationName' => 'LIBRARY', 'latitude' => 6.034464, 'longitude' => 116.118067],
            ['locationName' => 'PPIB', 'latitude' => 6.032187, 'longitude' => 116.117908],
            ['locationName' => 'Other', 'latitude' => 6.033178, 'longitude' => 116.122771],
        ];

        DB::table('item_locations')->insert($locations);

        $this->command->info('Item Location seeder completed successfully! ' . count($locations) . ' Item Locations inserted.');
    }
}
