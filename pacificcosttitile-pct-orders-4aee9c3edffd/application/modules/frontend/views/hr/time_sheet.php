<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/hr/css/time_sheet_style.css');?>">
</head>
<body>
    <div class="page landscape">
        <h3 class="text-center mb-10 mt-10">
            <b>Pacific Coast Title Company <br> TIMESHEET</b>																
        </h3>
        <div class="d-float">
            <div class="float-left w-half">
               <div><span class="min-w-130"><b>EMPLOYEE NAME:</b> </span> <input readonly type="text" class="w35" value="<?php echo $user['name'];?>"></div>
               <div class="mt-5"><span class="min-w-130"><small>DATE FORM COMPLETED:</small></span><input readonly type="text" class="w35"></div>
               <div class="mt-5"><span class="min-w-130"><small>MANAGER NAME: </small></span><input readonly type="text" class="w35"></div>
            </div>
            <div class="float-right w-half text-right">
                PAY PERIOD: <input readonly type="text" class="min-w-65" value="<?php echo date('m/d/Y', strtotime($first_date)); ?>"> to <input readonly type="text" class="min-w-65" value="<?php echo date('m/d/Y', strtotime($last_date)); ?>">
            </div>
        </div>

        <table class="mt-10 big_table">
            <thead>
                <tr>
                    <th>WORK DAY</th>
                    <th>DATE OF EXCEPTION</th>
                    <th>START TIME </th>
                    <th>LUNCH START </th>
                    <th>LUNCH END </th>
                    <th>END   TIME </th>
                    <th>REG. HOURS</th>
                    <th>OT HOURS</th>
                    <th>DOUBLE OT HOURS</th>
                    <th>VAC HOURS</th>
                    <th>SICK HOURS</th>
                    <!-- <th>OTHER HOURS</th>
                    <th>HOLIDAY HOURS</th>
                    <th>PCH HOURS  <small>(Personal Choice Holiday)</small></th> -->
                    <th>UNPAID HOURS</th>
                    <!-- <th>EMPLOYEE COMMENTS</th> -->
                </tr>
            </thead>
            <tbody>
                
				<?php
				$int_i = 0;
				$reg_hours_sum = $unpaid_hours_sum = $ot_hours_sum = $double_ot_sum = 0;
				$lunch_hours=0;
				foreach($time_sheet_array as $timesheet_date=>$time_sheet_record):
					$ot_seconds = $double_ot_seconds = 0;
					$lunch_hours = $time_sheet_record['lunch_hours'];
					$reg_hours = $time_sheet_record['reg_hours']  + $lunch_hours;
					
					
					if($lunch_hours > 3600) {
						$lunch_hours = 3600;
					}
					if((($lunch_hours + $time_sheet_record['reg_hours']) > (9*60*60))) {
						if(in_array(date('Y-m-d', strtotime($timesheet_date)),$ot_approved_dates)) {
							$ot_seconds =  ($lunch_hours + $time_sheet_record['reg_hours'] ) - (9*60*60);
						}
						else {
							$unpaid_hours = ($lunch_hours + $time_sheet_record['reg_hours'] ) - (9*60*60);
							$time_sheet_record['unpaid_hours'] += $unpaid_hours;
						}	

						$reg_hours = (9*60*60);
						
					}
					$unpaid_hours = ($time_sheet_record['lunch_hours'] + $time_sheet_record['reg_hours']) - ($lunch_hours + $time_sheet_record['reg_hours']);
					$time_sheet_record['reg_hours'] = $reg_hours;
					$time_sheet_record['unpaid_hours'] += $unpaid_hours;
					/* TIme Card Exception logic Start*/
					$search_key_exception = array_search(date('Y-m-d', strtotime($timesheet_date)), array_column($timecard_exception_data, 'exception_date'));
					if($search_key_exception !== false) {
						$timecard_exception_record = $timecard_exception_data[$search_key_exception];
						$reg_hours = ($timecard_exception_record->reg_hours*60*60);
						$ot_seconds = ($timecard_exception_record->ot_hours*60*60);
						$double_ot_seconds = ($timecard_exception_record->double_ot*60*60);
					}
					/* TIme Card Exception logic End*/
					$reg_hours_sum += $reg_hours;
					$unpaid_hours_sum += $time_sheet_record['unpaid_hours'];
					$ot_hours_sum += $ot_seconds;
					$double_ot_sum += $double_ot_seconds;
					$ot_hours = sprintf('%02d:%02d', ($ot_seconds/3600),($ot_seconds/60%60));
					$double_ot_hours = sprintf('%02d:%02d', ($double_ot_seconds/3600),($double_ot_seconds/60%60));

				?>
				<?php if(($int_i%7) == 0) : ?>
				<tr class="week_title">
                    <td colspan="6"><b>WEEK <?php echo ($int_i/7)+1 ?></b></td>
                    <td colspan="3"><em><?php if($int_i == 0): ?><small>Formula (do not override)</small><?php endif; ?></em></td>
                    <td colspan="7"></td>
                </tr>
				<?php endif; ?>
				<tr>
					<?php
					?>
					<td><b><?php echo date('l', strtotime($timesheet_date));?></b></td>
					<td><?php echo date('m/d/Y', strtotime($timesheet_date));?></td>
                    <td><?php echo (is_int($time_sheet_record['start_time']))?date("h:i a", $time_sheet_record['start_time']):'-';?></td>
                    <td><?php echo (is_int($time_sheet_record['lunch_start']))?date("h:i a", $time_sheet_record['lunch_start']):'-';?></td>
                    <td><?php echo (is_int($time_sheet_record['lunch_end']))?date("h:i a", $time_sheet_record['lunch_end']):'-';?></td>
                    <td><?php echo (is_int($time_sheet_record['end_time']))?date("h:i a", $time_sheet_record['end_time']):'-';?></td>
                    <td><?php echo ($reg_hours > 0)?sprintf('%02d:%02d', ($reg_hours/3600),($reg_hours/60%60)):'00:00';?></td>
                    <td><?php echo $ot_hours; ?></td>
                    <td><?php echo $double_ot_hours; ?></td>
                    <td></td>
                    <td></td>
                    <!-- <td></td>
                    <td></td>
                    <td></td> -->
                    <td><?php echo ($time_sheet_record['unpaid_hours'] > 0)?sprintf('%02d:%02d', ($time_sheet_record['unpaid_hours']/3600),($time_sheet_record['unpaid_hours']/60%60)):'00:00';?></td>
                    <!-- <td></td> -->
				</tr>
				<?php if(((($int_i+1)%7) == 0) || count($time_sheet_array) == $int_i+1) : ?>
					<tr class="total">
                    <td colspan="6"><b>TOTAL WEEK <?php echo ceil(($int_i+1)/7) ?>	 </b></td>
                    <td><?php echo ($reg_hours_sum > 0)?sprintf('%02d:%02d', ($reg_hours_sum/3600),($reg_hours_sum/60%60)):'00:00';?></td>
                    <td><?php echo ($ot_hours_sum > 0)?sprintf('%02d:%02d', ($ot_hours_sum/3600),($ot_hours_sum/60%60)):'00:00';?></td>
                    <td><?php echo ($double_ot_sum > 0)?sprintf('%02d:%02d', ($double_ot_sum/3600),($double_ot_sum/60%60)):'00:00';?></td>
                    <td>0</td>
                    <td>0</td>
                    <!-- <td>0</td>
                    <td>0</td>
                    <td>0</td> -->
                    <td><?php echo ($unpaid_hours_sum > 0)?sprintf('%02d:%02d', ($unpaid_hours_sum/3600),($unpaid_hours_sum/60%60)):'00:00';?></td>
                    <!-- <td>0</td> -->
                </tr>
				<?php 
				$reg_hours_sum = $unpaid_hours_sum = $ot_hours_sum = $double_ot_sum = 0;
				endif; 
				?>
				<?php
				$int_i++;
				endforeach;
				?>
                          
                
            </tbody>
        </table>
        <div class="mt-10">
            Additional Comments to Manager:
            <textarea name="comment" class="w-full" rows="3"></textarea>
        </div>
        <div class="mt-10 text-right"><b>I certify that the hours reported on this form are accurate</b></div>

        
        <div class="d-float mt-20">
            <div class="float-left w-half">
                <input type="text" class="w-half black_sign"><br>
                Manager Signature
            </div>
            <div class="float-right w-half text-right">
                <input type="text" class="w-half black_sign"><br>
                Employee Signature
            </div>
        </div>
    </div>
</body>
</html>
