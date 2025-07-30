<?php


use Phinx\Seed\AbstractSeed;

class HrUserTypes extends AbstractSeed
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
        $posts = $this->table('pct_hr_user_types');
        $posts->truncate();
        $data = [
            [
                'name'    => 'Superadmin',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Admin',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Employee',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Manager',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Onboarding Laison',
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $posts->insert($data)
              ->save();
    }
}
