<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class StoredProcedureAndTriggersForCommissionCalculationOfSalesRep extends AbstractMigration
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

		$procedure = "DROP PROCEDURE IF EXISTS `calculate_commission`;
		
		CREATE PROCEDURE `calculate_commission`(
			IN `user_id` INT
		)
		LANGUAGE SQL
		NOT DETERMINISTIC
		CONTAINS SQL
		SQL SECURITY DEFINER
		COMMENT ''
		BEGIN
		
		DECLARE done,tier_done,override_done,threshold_done,commission_calculated,is_first_record INT DEFAULT 0;
		DECLARE prod_type,underwriter,sales_amount,loan_amount,check_amount,prod_type_temp,underwriter_temp  CHAR(255);
		DECLARE premium,total_premium,tier_commission,range_premium,range_min,range_max,commission_temp,fix_commsision,remaining_premium,threshold_amount_min,threshold_amount_max,threshold_commission FLOAT;
		DECLARE total_tiers,tier_id,tier_id_temp,underwriter_users_id,for_month,for_year INT;
		DECLARE allow_threshold TINYINT(1);
		DECLARE total_commisssion,temp_commission FLOAT DEFAULT 0;
		DECLARE json_obj,json_arr TEXT;

		SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
		
		SET json_arr = '[]';
		SET for_month = MONTH(CURRENT_DATE());
		SET for_year = YEAR(CURRENT_DATE());
		BLOCK1: BEGIN
		DECLARE order_details_cur
				CURSOR FOR 
					SELECT order_details.prod_type,order_details.underwriter,order_details.premium,REPLACE(transaction_details.loan_amount, ',', '') as loan_amount,REPLACE(transaction_details.sales_amount, ',', '') as sales_amount
					FROM order_details
					JOIN transaction_details ON order_details.transaction_id = transaction_details.id
					WHERE transaction_details.sales_representative = user_id AND MONTH(sent_to_accounting_date) =  for_month AND YEAR(sent_to_accounting_date) =  for_year;
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
		
		DROP TEMPORARY TABLE IF EXISTS my_temp_table;
		CREATE TEMPORARY TABLE my_temp_table (tier_id_temp INT,premium_temp FLOAT,commission_temp FLOAT,prod_type_temp VARCHAR(10),underwriter_temp VARCHAR(20),check_amount_temp FLOAT,added_from SMALLINT);
		
				SET total_premium = 0;
				OPEN order_details_cur;
				
				get_details : LOOP FETCH order_details_cur INTO prod_type, underwriter,premium,loan_amount,sales_amount;
				 
					IF done = 1 THEN
					LEAVE get_details;
				END IF;
				
				SET check_amount = sales_amount;
				IF prod_type = 'loan' THEN
						SET check_amount =  loan_amount;
				END IF;
				
				IF underwriter = 'north_american' THEN
					SET underwriter = 'natic';
			   END IF;
				
			  BLOCK2: BEGIN
			  
			  DECLARE tier_count_cur
				CURSOR FOR 
					SELECT COUNT(id) AS total_type,id,commission
					FROM pct_underwriter_tiers WHERE `product_type` = prod_type AND `underwriter`=underwriter GROUP BY `product_type`,`underwriter`;
			  DECLARE CONTINUE HANDLER FOR NOT FOUND SET tier_done = 1;
				
				OPEN tier_count_cur;
				get_tier_count : LOOP
				FETCH tier_count_cur INTO total_tiers,tier_id,tier_commission;
				
					IF tier_done = 1 THEN
					SET tier_done = 0;
					LEAVE get_tier_count;
				 END IF;
					
				END LOOP get_tier_count;
				CLOSE tier_count_cur;	
			  END BLOCK2;
			  
			  IF total_tiers = 1 THEN
					   INSERT INTO my_temp_table VALUES(tier_id,premium,tier_commission,prod_type,underwriter,check_amount,0);
			  ELSE
				  BLOCK3: BEGIN
					  DECLARE tier_count_cur
							CURSOR FOR 
								SELECT pct_underwriter_tiers.id,commission,pct_commission_range.premium,pct_commission_range.min_revenue,pct_commission_range.max_revenue
								FROM pct_underwriter_tiers 
								JOIN pct_commission_range ON pct_commission_range.underwriter_tier = pct_underwriter_tiers.id
								WHERE pct_underwriter_tiers.`product_type` = prod_type AND `underwriter`=underwriter;
								
					 DECLARE CONTINUE HANDLER FOR NOT FOUND SET tier_done = 1;
						
						OPEN tier_count_cur;
							get_tier_count : LOOP
							FETCH tier_count_cur INTO tier_id,tier_commission,range_premium,range_min,range_max;
							
								IF tier_done = 1 THEN
								SET tier_done = 0;
								LEAVE get_tier_count;
							 END IF;
							 
							  IF range_premium = premium AND check_amount >= range_min AND check_amount <= range_max THEN
								 INSERT INTO my_temp_table VALUES(tier_id,premium,tier_commission,prod_type,underwriter,check_amount,1);
								 SET tier_done = 0;
								LEAVE get_tier_count;
							  END IF;
								
							END LOOP get_tier_count;
							CLOSE tier_count_cur;
							
							
				  END BLOCK3;
				  
			  END IF;
				END LOOP get_details;
				
				CLOSE order_details_cur;
				
				
			END BLOCK1;	
			
			BLOCK4: BEGIN
					  DECLARE temp_tbl_cur
							CURSOR FOR 
								SELECT SUM(my_temp_table.premium_temp) AS total_premium,my_temp_table.tier_id_temp,my_temp_table.commission_temp,my_temp_table.prod_type_temp,my_temp_table.underwriter_temp  FROM my_temp_table GROUP BY my_temp_table.tier_id_temp;
						DECLARE CONTINUE HANDLER FOR NOT FOUND SET tier_done = 1;
						
						OPEN temp_tbl_cur;
							tmp_tbl_data : LOOP
							FETCH temp_tbl_cur INTO total_premium,tier_id_temp,commission_temp,prod_type_temp,underwriter_temp;
							
								IF tier_done = 1 THEN
								SET tier_done = 0;
								LEAVE tmp_tbl_data;
							 END IF;
		
							 SET commission_calculated = 0;
							 
							 
							 BLOCK5 : BEGIN
								 DECLARE user_override_cur
									CURSOR FOR 
										SELECT pct_underwriter_users.id,pct_underwriter_users.allow_threshold,pct_underwriter_users.fix_commission FROM pct_underwriter_users
								WHERE pct_underwriter_users.user_id = user_id AND pct_underwriter_users.underwriter_tier_id = tier_id_temp;
									DECLARE CONTINUE HANDLER FOR NOT FOUND SET override_done = 1;
									
									OPEN user_override_cur;
									tmp_user_data : LOOP
									FETCH user_override_cur INTO underwriter_users_id,allow_threshold,fix_commsision;
									
										IF override_done = 1 THEN
										SET override_done = 0;
										LEAVE tmp_user_data;
									 END IF;
									 IF fix_commsision > 0 AND allow_threshold = 0 THEN
										SET commission_calculated = 1;
										SET temp_commission = ((fix_commsision * total_premium) / 100);
										SET total_commisssion = total_commisssion + temp_commission;
										SET json_obj = JSON_OBJECT('prod_type',prod_type_temp,'underwriter',underwriter_temp,'tier',tier_id_temp,'commisison',temp_commission);
										SET json_arr =  JSON_ARRAY_APPEND(json_arr, '$', json_obj);
									 ELSE 
										IF allow_threshold = 1 THEN
											SET remaining_premium = total_premium;
											SET is_first_record = 0;
											BLOCK6 : BEGIN
		
												DECLARE threshold_cur
													CURSOR FOR 
														SELECT pct_underwriter_users_threshold.threshold_amount_min,pct_underwriter_users_threshold.threshold_amount_max,pct_underwriter_users_threshold.threshold_commission FROM pct_underwriter_users_threshold
														WHERE pct_underwriter_users_threshold.underwriter_users_id = underwriter_users_id ORDER BY pct_underwriter_users_threshold.threshold_amount_min ASC;
													DECLARE CONTINUE HANDLER FOR NOT FOUND SET threshold_done = 1;
													
													OPEN threshold_cur;
														tmp_threshold_data : LOOP
														FETCH threshold_cur INTO threshold_amount_min,threshold_amount_max,threshold_commission;
														
															IF threshold_done = 1 THEN
																SET threshold_done = 0;
																LEAVE tmp_threshold_data;
															END IF;
		
															IF is_first_record = 0 THEN
																SET is_first_record = 1;
																IF threshold_amount_min > 1 AND remaining_premium >= threshold_amount_min THEN
																	SET remaining_premium = remaining_premium - threshold_amount_min;
																	SET temp_commission = ((commission_temp * threshold_amount_min) / 100);
																	SET total_commisssion = total_commisssion + temp_commission;
																	SET json_obj = JSON_OBJECT('prod_type',prod_type_temp,'underwriter',underwriter_temp,'tier',tier_id_temp,'commisison',temp_commission);
																	SET json_arr =  JSON_ARRAY_APPEND(json_arr, '$', json_obj);
																	SET commission_calculated = 1;
																END IF;
															END IF;
		
															IF remaining_premium >= threshold_amount_min THEN
																IF remaining_premium <= threshold_amount_max THEN
																	SET temp_commission = ((threshold_commission * remaining_premium) / 100);
																	SET total_commisssion = total_commisssion + temp_commission;
																	SET json_obj = JSON_OBJECT('prod_type',prod_type_temp,'underwriter',underwriter_temp,'tier',tier_id_temp,'commisison',temp_commission);
																	SET json_arr =  JSON_ARRAY_APPEND(json_arr, '$', json_obj);
																	SET threshold_done = 0;
																	SET commission_calculated = 1;
																	LEAVE tmp_threshold_data;
																ELSE
																	
																	SET temp_commission =   ((threshold_commission * threshold_amount_max) / 100);
																	SET total_commisssion = total_commisssion + temp_commission;
																	SET json_obj = JSON_OBJECT('prod_type',prod_type_temp,'underwriter',underwriter_temp,'tier',tier_id_temp,'commisison',temp_commission);
																	SET json_arr =  JSON_ARRAY_APPEND(json_arr, '$', json_obj);
																END IF;
																SET remaining_premium = remaining_premium - threshold_amount_max;
																SET commission_calculated = 1;
															END IF;
		
														END LOOP tmp_threshold_data;
													CLOSE threshold_cur;
		
											END BLOCK6;
										END IF;
									 END IF;
		
								  END LOOP tmp_user_data;
								  CLOSE user_override_cur;
									 
							 END BLOCK5;
		
							 IF commission_calculated = 0 THEN
								SET temp_commission = ((commission_temp * total_premium) / 100);
								SET total_commisssion = total_commisssion + temp_commission;
								SET json_obj = JSON_OBJECT('prod_type',prod_type_temp,'underwriter',underwriter_temp,'tier',tier_id_temp,'commisison',temp_commission);
								SET json_arr =  JSON_ARRAY_APPEND(json_arr, '$', json_obj);
							 END IF;
							 
						  END LOOP tmp_tbl_data;
						  
						  CLOSE temp_tbl_cur;
				END BLOCK4;
				 INSERT INTO pct_user_monthly_commission (`user_id`,`commission`,`commission_details`,`commission_month`,`commission_year`) VALUES (user_id,total_commisssion,json_arr,for_month,for_year)
				 ON DUPLICATE KEY UPDATE pct_user_monthly_commission.commission=total_commisssion,commission_details=json_arr;
		END";
		$this->execute($procedure);
    }
}
