<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCommissionCalcualteForAllStoredProcedure extends AbstractMigration
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
		$procedure = "DROP PROCEDURE IF EXISTS `calculate_commission_for_all`;
		
		CREATE PROCEDURE `calculate_commission_for_all`()
		LANGUAGE SQL
		NOT DETERMINISTIC
		CONTAINS SQL
		SQL SECURITY DEFINER
		COMMENT ''
		BEGIN
			DECLARE sales_rep,sales_rep_done INT DEFAULT 0;
			DECLARE for_month INT DEFAULT MONTH(CURRENT_DATE());
			DECLARE for_year INT DEFAULT YEAR(CURRENT_DATE());
			DECLARE sales_rep_cur
				CURSOR FOR 
					SELECT transaction_details.sales_representative
					FROM transaction_details
					JOIN order_details ON order_details.transaction_id = transaction_details.id
					JOIN customer_basic_details ON transaction_details.sales_representative = customer_basic_details.id
					WHERE MONTH(order_details.sent_to_accounting_date) =  for_month AND YEAR(order_details.sent_to_accounting_date) =  for_year
					GROUP BY transaction_details.sales_representative;
				DECLARE CONTINUE HANDLER FOR NOT FOUND SET sales_rep_done = 1;

			OPEN sales_rep_cur;
				get_sales_rep_details : LOOP FETCH sales_rep_cur INTO sales_rep;

				IF sales_rep_done = 1 THEN
					LEAVE get_sales_rep_details;
				END IF;

				IF sales_rep > 0 THEN
					CALL calculate_commission(sales_rep);
				END IF;

			END LOOP get_sales_rep_details;
			CLOSE sales_rep_cur;
		END";
		$this->execute($procedure);

    }
}
