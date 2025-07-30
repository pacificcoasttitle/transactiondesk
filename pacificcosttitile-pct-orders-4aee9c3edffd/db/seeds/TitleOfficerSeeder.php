<?php


use Phinx\Seed\AbstractSeed;

class TitleOfficerSeeder extends AbstractSeed
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
                'name'    => 'Albert Wassif',
                'email_address'    => 'unit88@pct.com',
                'phone'    => '(818) 662-6704',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Clive Virata',
                'email_address'=> 'unit66@pct.com',
                'phone'    => '(714) 516-6788',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Eddie LasMarias',
                'email_address'    => 'unit32@pct.com',
                'phone'    => '(818) 662-6703',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Jim Jean',
                'email_address'    => 'unit33@pct.com',
                'phone'    => '(714) 516-6795',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $posts = $this->table('pct_order_title_officer');
        $posts->insert($data)
              ->save();
    }
}
