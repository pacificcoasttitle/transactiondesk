<?php


use Phinx\Seed\AbstractSeed;

class NotificationSeeder extends AbstractSeed
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
                'name'    => 'CPL Document Not Generated',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'name'    => 'Change Passsword',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'name'    => 'On Hold Order',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'name'    => 'Order Placed',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'name'    => 'Borrower Verification',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'name'    => 'Starter Need For Property Address',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'name'    => 'The Prelim Hot Sheet',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'name'    => 'No Hit On Property Search',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'name'    => 'Grant Deed Not Found',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'name'    => 'Tax Document Not Found',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'name'    => 'Legal Vesting Document Not Found',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $posts = $this->table('pct_notifications');
        $posts->insert($data)
              ->save();
    }
}
