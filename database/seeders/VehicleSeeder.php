<?php

namespace Database\Seeders;

use App\Models\VehicleBrand;
use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Honda' => [
                ['name' => 'Beat', 'type' => 'matic', 'cc' => 110, 'year_start' => 2008],
                ['name' => 'Vario 125', 'type' => 'matic', 'cc' => 125, 'year_start' => 2012],
                ['name' => 'Vario 150', 'type' => 'matic', 'cc' => 150, 'year_start' => 2015],
                ['name' => 'PCX 150', 'type' => 'matic', 'cc' => 150, 'year_start' => 2012],
                ['name' => 'Scoopy', 'type' => 'matic', 'cc' => 110, 'year_start' => 2010],
                ['name' => 'Revo', 'type' => 'bebek', 'cc' => 110, 'year_start' => 2006],
                ['name' => 'Supra X 125', 'type' => 'bebek', 'cc' => 125, 'year_start' => 2005],
                ['name' => 'CB150R', 'type' => 'sport', 'cc' => 150, 'year_start' => 2013],
                ['name' => 'CRF150L', 'type' => 'trail', 'cc' => 150, 'year_start' => 2017],
            ],
            'Yamaha' => [
                ['name' => 'NMAX', 'type' => 'matic', 'cc' => 155, 'year_start' => 2015],
                ['name' => 'Aerox 155', 'type' => 'matic', 'cc' => 155, 'year_start' => 2016],
                ['name' => 'Mio M3', 'type' => 'matic', 'cc' => 125, 'year_start' => 2013],
                ['name' => 'Mio Soul GT', 'type' => 'matic', 'cc' => 125, 'year_start' => 2012],
                ['name' => 'Jupiter MX', 'type' => 'bebek', 'cc' => 150, 'year_start' => 2005],
                ['name' => 'R15', 'type' => 'sport', 'cc' => 155, 'year_start' => 2014],
                ['name' => 'MT-15', 'type' => 'sport', 'cc' => 155, 'year_start' => 2018],
                ['name' => 'WR155R', 'type' => 'trail', 'cc' => 155, 'year_start' => 2020],
            ],
            'Suzuki' => [
                ['name' => 'Address', 'type' => 'matic', 'cc' => 113, 'year_start' => 2015],
                ['name' => 'Nex II', 'type' => 'matic', 'cc' => 113, 'year_start' => 2017],
                ['name' => 'Satria F150', 'type' => 'sport', 'cc' => 150, 'year_start' => 2007],
                ['name' => 'GSX-R150', 'type' => 'sport', 'cc' => 150, 'year_start' => 2017],
            ],
            'Kawasaki' => [
                ['name' => 'Ninja 250', 'type' => 'sport', 'cc' => 250, 'year_start' => 2008],
                ['name' => 'Ninja ZX-25R', 'type' => 'sport', 'cc' => 250, 'year_start' => 2020],
                ['name' => 'Z250', 'type' => 'sport', 'cc' => 250, 'year_start' => 2013],
                ['name' => 'KLX 150', 'type' => 'trail', 'cc' => 150, 'year_start' => 2009],
                ['name' => 'Versys-X 250', 'type' => 'adventure', 'cc' => 250, 'year_start' => 2017],
            ],
            'TVS' => [
                ['name' => 'Apache RTR 200', 'type' => 'sport', 'cc' => 200, 'year_start' => 2016],
                ['name' => 'Dazz', 'type' => 'matic', 'cc' => 110, 'year_start' => 2012],
            ],
            'Bajaj' => [
                ['name' => 'Pulsar NS200', 'type' => 'sport', 'cc' => 200, 'year_start' => 2012],
                ['name' => 'Pulsar 220F', 'type' => 'sport', 'cc' => 220, 'year_start' => 2007],
            ],
            'Royal Enfield' => [
                ['name' => 'Meteor 350', 'type' => 'sport', 'cc' => 350, 'year_start' => 2020],
                ['name' => 'Himalayan', 'type' => 'adventure', 'cc' => 411, 'year_start' => 2016],
            ],
        ];

        foreach ($data as $brandName => $types) {
            $brand = VehicleBrand::create(['name' => $brandName, 'country' => in_array($brandName, ['Honda', 'Yamaha', 'Suzuki', 'Kawasaki']) ? 'Japan' : (in_array($brandName, ['TVS', 'Bajaj', 'Royal Enfield']) ? 'India' : 'Indonesia')]);
            foreach ($types as $type) {
                VehicleType::create(array_merge($type, ['brand_id' => $brand->id]));
            }
        }
    }
}
