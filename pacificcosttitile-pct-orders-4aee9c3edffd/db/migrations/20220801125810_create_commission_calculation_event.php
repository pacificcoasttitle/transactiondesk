<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCommissionCalculationEvent extends AbstractMigration
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
		$event_to_calculate_commission = "DROP EVENT IF EXISTS `call_commission_calculation_sp`;

		CREATE EVENT `call_commission_calculation_sp`
		ON SCHEDULE
		EVERY 1 DAY STARTS concat(CURDATE(),' 23:55:00')
			ON COMPLETION NOT PRESERVE
			ENABLE
			COMMENT ''
			DO 
			BEGIN
				CALL `calculate_commission_for_all`();
			END";
		$this->execute($event_to_calculate_commission);

    }
}
