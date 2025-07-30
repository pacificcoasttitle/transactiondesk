<?php


use Phinx\Seed\AbstractSeed;

class DocumentTypesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
    
        $data = [
            [
                'name'    => 'E1 - Accounting',
                'api_id'    => 1022,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'E2 - State and Federal Tax Documents',
                'api_id'    => 1023,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'E3 - Closing Documents',
                'api_id'    => 1024,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'E4 - Demands/Bills/Inspections',
                'api_id'    => 1041,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'E5 - Escrow Instructions (Signed Only and Info)',
                'api_id'    => 1042,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'E6 - Loan Documents (Signed Only and Info)',
                'api_id'    => 1043,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'E7 - Communication for Escrow',
                'api_id'    => 1044,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Search Package',
                'api_id'    => 27,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'T1 - Policies',
                'api_id'    => 1013,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'T2 - Recording Documents',
                'api_id'    => 1031,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'T3 - Prelims and Updates',
                'api_id'    => 1032,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'T4 - Curative',
                'api_id'    => 1033,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'T5 - Production Documents',
                'api_id'    => 1021,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'T6 - Supporting Docs',
                'api_id'    => 1036,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'T7 - Payoffs',
                'api_id'    => 1035,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'T8 - Correspondence',
                'api_id'    => 1037,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Templates - Title - Other (G37)',
                'api_id'    => 1045,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Templates - Title - Other (R6)',
                'api_id'    => 1046,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Templates: Escrow Forms',
                'api_id'    => 1011,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Templates: Escrow Instructions',
                'api_id'    => 1015,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Templates: Escrow Letters',
                'api_id'    => 1020,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Templates: Recording Documents',
                'api_id'    => 1007,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Templates: Title Letters and Documents',
                'api_id'    => 1008,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'V1 - Vantage Deeds',
                'api_id'    => 1047,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'V2 - Vantage Open Money',
                'api_id'    => 1048,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'V3 - Vantage Judgements',
                'api_id'    => 1049,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'V4 - Vantage Tax Cert',
                'api_id'    => 1050,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'V5 - Vantage CPL',
                'api_id'    => 1051,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'X1 - Corporate Accounting',
                'api_id'    => 1040,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Z Do Not Use',
                'api_id'    => 1039,
                'created' => date('Y-m-d H:i:s'),
            ],
        ];

        $posts = $this->table('pct_order_documents_types');
        $posts->insert($data)
              ->save();
    }
}
