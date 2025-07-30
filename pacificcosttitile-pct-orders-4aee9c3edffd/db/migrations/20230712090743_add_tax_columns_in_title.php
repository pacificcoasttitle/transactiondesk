<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTaxColumnsInTitle extends AbstractMigration
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
        $table = $this->table('pct_order_title_point_data');
        $table->addColumn('tax_rate_area', 'string', ['after' => 'email_sent_status', 'null' => true])
            ->addColumn('use_code', 'string', ['after' => 'tax_rate_area', 'null' => true])
            ->addColumn('region_code', 'string', ['after' => 'use_code', 'null' => true])
            ->addColumn('flood_zone', 'string', ['after' => 'region_code', 'null' => true])
            ->addColumn('zoning_code', 'string', ['after' => 'flood_zone', 'null' => true])
            ->addColumn('taxability_code', 'string', ['after' => 'zoning_code', 'null' => true])
            ->addColumn('tax_rate', 'string', ['after' => 'taxability_code', 'null' => true])
            ->addColumn('issue_date', 'string', ['after' => 'tax_rate', 'null' => true])
            ->addColumn('land', 'string', ['after' => 'issue_date', 'null' => true])
            ->addColumn('improvements', 'string', ['after' => 'land', 'null' => true])
            ->update();

    }
}
