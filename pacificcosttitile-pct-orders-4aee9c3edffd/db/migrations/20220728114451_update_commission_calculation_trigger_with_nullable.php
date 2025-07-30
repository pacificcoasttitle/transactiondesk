<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateCommissionCalculationTriggerWithNullable extends AbstractMigration
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
				SELECT transaction_details.sales_representative
				INTO sales_rep
				FROM transaction_details
				WHERE NEW.transaction_id = transaction_details.id
				LIMIT 1;
		
				IF sales_rep > 0 THEN
					CALL calculate_commission(sales_rep);
				END IF;
			END IF;
		END";
		$order_details_update = "DROP TRIGGER IF EXISTS `order_details_after_update`;

		CREATE TRIGGER `order_details_after_update` AFTER UPDATE ON `order_details` FOR EACH ROW BEGIN
			DECLARE done,sales_rep INT DEFAULT 0;
			DECLARE for_month,for_year INT;
			SET for_month = MONTH(CURRENT_DATE());
			SET for_year = YEAR(CURRENT_DATE());
			
			IF (NOT(OLD.premium <=> NEW.premium) OR NOT(OLD.sent_to_accounting_date <=> NEW.sent_to_accounting_date) OR NOT(OLD.prod_type <=> NEW.prod_type) OR NOT(OLD.underwriter <=> NEW.underwriter) OR NOT(OLD.escrow_amount <=> NEW.escrow_amount) OR NOT(OLD.loan_amount <=> NEW.loan_amount) OR NOT(OLD.sales_amount <=> NEW.sales_amount)) AND (MONTH(OLD.sent_to_accounting_date) =  for_month AND YEAR(OLD.sent_to_accounting_date) = for_year) THEN
				SELECT transaction_details.sales_representative
				INTO sales_rep
				FROM transaction_details
				WHERE NEW.transaction_id = transaction_details.id
				LIMIT 1;
		
				IF sales_rep > 0 THEN
					CALL calculate_commission(sales_rep);
				END IF;
		
			END IF;
		END";
		$order_details_delete = "DROP TRIGGER IF EXISTS `order_details_after_delete`;

		CREATE TRIGGER `order_details_after_delete` AFTER DELETE ON `order_details` FOR EACH ROW BEGIN
		
			DECLARE done,sales_rep INT DEFAULT 0;
			DECLARE for_month,for_year INT;
			SET for_month = MONTH(CURRENT_DATE());
			SET for_year = YEAR(CURRENT_DATE());
		
			IF OLD.sent_to_accounting_date IS NOT NULL AND (MONTH(OLD.sent_to_accounting_date) =  for_month AND YEAR(OLD.sent_to_accounting_date) = for_year) THEN
				SELECT transaction_details.sales_representative
				INTO sales_rep
				FROM transaction_details
				WHERE OLD.transaction_id = transaction_details.id
				LIMIT 1;
		
				IF sales_rep > 0 THEN
					CALL calculate_commission(sales_rep);
				END IF;
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
		
			IF NOT(OLD.sales_amount <=> NEW.sales_amount) OR NOT(OLD.loan_amount <=> NEW.loan_amount) OR NOT(OLD.sales_representative <=> NEW.sales_representative)  THEN
				IF NEW.sales_representative > 0 THEN
					CALL calculate_commission(NEW.sales_representative);
				END IF;
				IF OLD.sales_representative > 0 THEN
					CALL calculate_commission(OLD.sales_representative);
				END IF;
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
