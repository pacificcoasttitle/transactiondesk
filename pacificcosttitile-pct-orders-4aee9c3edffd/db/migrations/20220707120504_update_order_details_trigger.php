<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateOrderDetailsTrigger extends AbstractMigration
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
		$order_details_insert = "DROP TRIGGER IF EXISTS `order_details_after_insert`;

			CREATE TRIGGER `order_details_after_insert` AFTER INSERT ON `order_details` FOR EACH ROW BEGIN
			
			DECLARE done,sales_rep INT DEFAULT 0;
			DECLARE for_month,for_year INT;
			SET for_month = MONTH(CURRENT_DATE());
			SET for_year = YEAR(CURRENT_DATE());
			
			IF NEW.sent_to_accounting_date IS NOT NULL AND (MONTH(NEW.sent_to_accounting_date) =  for_month AND YEAR(NEW.sent_to_accounting_date) = for_year) THEN
			BLOCK1: BEGIN
				DECLARE get_sales_rep_cur
					CURSOR FOR 
						SELECT transaction_details.sales_representative
						FROM transaction_details
						WHERE NEW.transaction_id = transaction_details.id
						LIMIT 1;
				DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
			
				OPEN get_sales_rep_cur;
					
					get_sales_rep : LOOP FETCH get_sales_rep_cur INTO sales_rep;
					
						IF done = 1 THEN
							LEAVE get_sales_rep;
						END IF;
			
			
						IF sales_rep > 0 THEN
							CALL calculate_commission(sales_rep);
							LEAVE get_sales_rep;
						END IF;
					
					END LOOP get_sales_rep;
				CLOSE get_sales_rep_cur;
			END BLOCK1;
			END IF;
			END";
		$order_details_update = "DROP TRIGGER IF EXISTS `order_details_after_update`;
			CREATE TRIGGER `order_details_after_update` AFTER UPDATE ON `order_details` FOR EACH ROW BEGIN

			DECLARE done,sales_rep INT DEFAULT 0;
			DECLARE for_month,for_year INT;
			SET for_month = MONTH(CURRENT_DATE());
			SET for_year = YEAR(CURRENT_DATE());

			IF (OLD.premium <> NEW.premium OR OLD.sent_to_accounting_date <> NEW.sent_to_accounting_date OR OLD.prod_type <> NEW.prod_type OR OLD.underwriter <> NEW.underwriter OR OLD.escrow_amount <> NEW.escrow_amount) AND (MONTH(NEW.sent_to_accounting_date) =  for_month AND YEAR(NEW.sent_to_accounting_date) = for_year) THEN
			BLOCK1: BEGIN
				DECLARE get_sales_rep_cur
					CURSOR FOR 
						SELECT transaction_details.sales_representative
						FROM transaction_details
						WHERE NEW.transaction_id = transaction_details.id
						LIMIT 1;
				DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

				OPEN get_sales_rep_cur;
					
					get_sales_rep : LOOP FETCH get_sales_rep_cur INTO sales_rep;
					
						IF done = 1 THEN
							LEAVE get_sales_rep;
						END IF;


						IF sales_rep > 0 THEN
							CALL calculate_commission(sales_rep);
							LEAVE get_sales_rep;
						END IF;
					
					END LOOP get_sales_rep;
				CLOSE get_sales_rep_cur;
			END BLOCK1;
			END IF;
			END";
		$order_details_delete = "DROP TRIGGER IF EXISTS `order_details_after_delete`;

			CREATE TRIGGER `order_details_after_delete` AFTER DELETE ON `order_details` FOR EACH ROW BEGIN
			
			DECLARE done,sales_rep INT DEFAULT 0;
			DECLARE for_month,for_year INT;
			SET for_month = MONTH(CURRENT_DATE());
			SET for_year = YEAR(CURRENT_DATE());
			
			IF OLD.sent_to_accounting_date IS NOT NULL AND (MONTH(OLD.sent_to_accounting_date) =  for_month AND YEAR(OLD.sent_to_accounting_date) = for_year) THEN
			BLOCK1: BEGIN
				DECLARE get_sales_rep_cur
					CURSOR FOR 
						SELECT transaction_details.sales_representative
						FROM transaction_details
						WHERE OLD.transaction_id = transaction_details.id
						LIMIT 1;
				DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
			
				OPEN get_sales_rep_cur;
					
					get_sales_rep : LOOP FETCH get_sales_rep_cur INTO sales_rep;
					
						IF done = 1 THEN
							LEAVE get_sales_rep;
						END IF;
			
			
						IF sales_rep > 0 THEN
							CALL calculate_commission(sales_rep);
							LEAVE get_sales_rep;
						END IF;
					
					END LOOP get_sales_rep;
				CLOSE get_sales_rep_cur;
			END BLOCK1;
			END IF;
			END";

		$transaction_details_insert = "DROP TRIGGER IF EXISTS `transaction_details_after_insert`;

			CREATE TRIGGER `transaction_details_after_insert` AFTER INSERT ON `transaction_details` FOR EACH ROW BEGIN
			
			IF NEW.sales_representative <> 0 AND NEW.sales_representative IS NOT NULL THEN
				CALL calculate_commission(NEW.sales_representative);
			END IF;
			
			END";

		$transaction_details_update = "DROP TRIGGER IF EXISTS `transaction_details_after_update`;
				CREATE TRIGGER `transaction_details_after_update` AFTER UPDATE ON `transaction_details` FOR EACH ROW BEGIN


				IF OLD.sales_amount <> NEW.sales_amount OR OLD.loan_amount <> NEW.loan_amount OR OLD.sales_representative <> NEW.sales_representative  THEN
					CALL calculate_commission(NEW.sales_representative);
				END IF;
				END";
		$transaction_details_delete = "DROP TRIGGER IF EXISTS `transaction_details_after_delete`;

			CREATE TRIGGER `transaction_details_after_delete` AFTER DELETE ON `transaction_details` FOR EACH ROW BEGIN
			
			IF OLD.sales_representative <> 0 AND OLD.sales_representative IS NOT NULL THEN
				CALL calculate_commission(OLD.sales_representative);
			END IF;
			
			END";

		$this->execute($order_details_insert);
		$this->execute($order_details_update);
		$this->execute($order_details_delete);
		$this->execute($transaction_details_insert);
		$this->execute($transaction_details_update);
		$this->execute($transaction_details_delete);

    }
}
