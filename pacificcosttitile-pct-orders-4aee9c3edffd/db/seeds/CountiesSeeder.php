<?php


use Phinx\Seed\AbstractSeed;

class CountiesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'county'    => 'Alameda',
                'fips'    => '06001',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'county'    => 'Alpine',
                'fips'    => '06003',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Amador',
                'fips'    => '06005',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Butte',
                'fips'    => '06007',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Calaveras',
                'fips'    => '06009',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Colusa',
                'fips'    => '06011',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Contra Costa',
                'fips'    => '06013',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Del Norte',
                'fips'    => '06015',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'El Dorado',
                'fips'    => '06017',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Fresno',
                'fips'    => '06019',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Glenn',
                'fips'    => '06021',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Humboldt',
                'fips'    => '06023',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Imperial',
                'fips'    => '06025',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Inyo',
                'fips'    => '06027',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Kern',
                'fips'    => '06029',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Kings',
                'fips'    => '06031',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Lake',
                'fips'    => '06033',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Lassen',
                'fips'    => '06035',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Los Angeles',
                'fips'    => '06037',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Madera',
                'fips'    => '06039',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Marin',
                'fips'    => '06041',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Mariposa',
                'fips'    => '06043',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Mendocino',
                'fips'    => '06045',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Merced',
                'fips'    => '06047',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Modoc',
                'fips'    => '06049',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Mono',
                'fips'    => '06051',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Monterey',
                'fips'    => '06053',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Napa',
                'fips'    => '06055',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Nevada',
                'fips'    => '06057',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Orange',
                'fips'    => '06059',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Placer',
                'fips'    => '06061',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Plumas',
                'fips'    => '06063',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Riverside',
                'fips'    => '06065',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Sacramento',
                'fips'    => '06067',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'San Benito',
                'fips'    => '06069',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'San Bernardino',
                'fips'    => '06071',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'San Diego',
                'fips'    => '06073',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'San Francisco',
                'fips'    => '06075',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'San Joaquin',
                'fips'    => '06077',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'San Luis',
                'fips'    => '06079',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'San Mateo',
                'fips'    => '06081',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Santa Barbara',
                'fips'    => '06083',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Santa Clara',
                'fips'    => '06085',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Santa Cruz',
                'fips'    => '06087',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Shasta',
                'fips'    => '06089',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Sierra',
                'fips'    => '06091',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Siskiyou',
                'fips'    => '06093',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Solano',
                'fips'    => '06095',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Sonoma',
                'fips'    => '06097',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Stanislaus',
                'fips'    => '06099',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Sutter',
                'fips'    => '06101',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Tehama',
                'fips'    => '06103',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Trinity',
                'fips'    => '06105',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Tulare',
                'fips'    => '06107',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Tuolumne',
                'fips'    => '06109',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Ventura',
                'fips'    => '06111',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Yolo',
                'fips'    => '06113',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'county'    => 'Yuba',
                'fips'    => '06115',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
            
        ];

        $posts = $this->table('pct_order_counties');
        $posts->insert($data)
              ->save();
    }
}
