<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddConfigForSurveyEmailFlag extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('pct_configs');
        $rows = [
            [
                'title' => 'Enable Survey Email',
                'slug' => 'enable_survey_email',
                'is_enable' => 1,
            ],
        ];

        $table->insert($rows)->saveData();
    }
}
