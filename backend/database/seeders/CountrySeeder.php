<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'name' => 'India',
                'code' => 'IN',
                'currency' => 'INR',
            ],
            [
                'name' => 'United States',
                'code' => 'US',
                'currency' => 'USD',
            ],
            [
                'name' => 'United Kingdom',
                'code' => 'UK',
                'currency' => 'GBP',
            ],
            [
                'name' => 'Germany',
                'code' => 'DE',
                'currency' => 'EUR',
            ],
            [
                'name' => 'Canada',
                'code' => 'CA',
                'currency' => 'CAD',
            ],
        ];

        foreach ($countries as &$country) {

            $country['uuid'] = Str::uuid();

            $country['created_at'] = now();

            $country['updated_at'] = now();
        }

        Country::insert($countries);
    }
}
