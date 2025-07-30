<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class OptimizeOrderTwilioMessageRecords extends AbstractMigration
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
        $table = $this->table('pct_order_twilio_message_records');
        $table->changeColumn('sent_from', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('sent_to', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('status', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('message_sid', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('error_code', 'string', ['limit' => 50, 'null' => true])
            ->update();
    }
}
