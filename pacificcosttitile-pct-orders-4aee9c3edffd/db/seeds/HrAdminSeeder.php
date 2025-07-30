<?php


use Phinx\Seed\AbstractSeed;

class HrAdminSeeder extends AbstractSeed
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
                'first_name'     => 'Violet',
                'last_name'     => 'Gallegos',
                'password'      => password_hash('Pacific1#', PASSWORD_DEFAULT),
                'email' => 'vgallegos@pct.com',
                'user_type_id' => 1,
                'position_id' => 0,
                'department_id' => 0,
                'hire_Date' => date('Y-m-d'),
                'status'    => 1,
                'hash' => '',
                'is_tmp_password' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $posts = $this->table('pct_hr_users');
        $posts->insert($data)
              ->save();
    }
}
