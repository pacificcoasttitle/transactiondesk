<style>
.l-main-contenta {
	padding-top: 0px !important;
}
.prelim_title {
	padding-left: 7px;
    padding-top: 5px;
	font-size: 18px;
}
</style>
<div class="typography-section__inner">
	<h3 class="ui-title-block_light">Prelim Info</h3>
	<div class="ui-decor-1a bg-accent"></div>
</div>
<div class="l-main-contenta">
	<article class="b-post b-post-full clearfix">
		<div class="row">
			<div class="col-md-8">
				<div class="typography__highlights">
					&nbsp;
					
						<span class="bg-border">Borrower Vesting</span>
						<br> 
						<div class="prelim_title <?php echo !isset($prelim_details['vesting']) || empty($prelim_details['vesting']) ? 'ml-50' : ''; ?>" >
							<?php echo isset($prelim_details['vesting']) && !empty($prelim_details['vesting']) ? $prelim_details['vesting'] : '-'; ?>
						</div>
					
				</div>
				<div class="typography__highlights">
					&nbsp;
					
						<span class="bg-border">Property Address</span>
						<br>
						<div class="prelim_title <?php echo !isset($prelim_details['address']) || empty($prelim_details['address']) ? 'ml-50' : ''; ?>">
							<?php echo isset($prelim_details['address']) && !empty($prelim_details['address']) ? $prelim_details['address'] : '-'; ?>
						</div>
					
				</div>
				<div class="typography__highlights">
					&nbsp;
					
						<span class="bg-border">Type of Policy</span>
						<br> 
						<div class="prelim_title <?php echo !isset($prelim_details['policy_type']) || empty($prelim_details['policy_type']) ? 'ml-50' : ''; ?>" >
							<?php echo isset($prelim_details['policy_type']) && !empty($prelim_details['policy_type']) ? $prelim_details['policy_type'] : '-'; ?>
						</div>
					
				</div>
			</div>
			<div class="col-md-4">
				<div class="typography__highlights">
					&nbsp;
					<span class="bg-border">Order Number</span>
					<br>
					<div class="prelim_title <?php echo !isset($prelim_details['file_number']) || empty($prelim_details['file_number']) ? 'ml-50' : ''; ?>">
						<?php echo isset($prelim_details['file_number']) && !empty($prelim_details['file_number']) ? $prelim_details['file_number'] : '-'; ?>
					</div>
				</div>
				
				<div class="typography__highlights">
					&nbsp;
					<span class="bg-border">Commitment Date</span>
					<br>
					<div class="prelim_title <?php echo !isset($prelim_details['generated_date']) || empty($prelim_details['generated_date']) ? 'ml-50' : ''; ?>">
						<?php if($prelim_details['is_updated'] == 1) { 
							echo isset($prelim_details['generated_date']) && !empty($prelim_details['generated_date']) ? "<b style='color:red'>".date('M d,Y',strtotime($prelim_details['generated_date'])).' at '.date('h:i a',strtotime($prelim_details['generated_date']))."</b>" : '-'; 
						 } else { 
							echo isset($prelim_details['generated_date']) && !empty($prelim_details['generated_date']) ? date('M d,Y',strtotime($prelim_details['generated_date'])).' at '.date('h:i a',strtotime($prelim_details['generated_date'])) : '-'; 
						 } ?>
					</div>
				</div>
				
				<div class="typography__highlights">
					&nbsp;
					<span class="bg-border">Property Type</span>
					<br>
					<div class="prelim_title <?php echo !isset($prelim_details['property_type']) || empty($prelim_details['property_type']) ? 'ml-50' : ''; ?>">
						<?php echo isset($prelim_details['property_type']) && !empty($prelim_details['property_type']) ? $prelim_details['property_type'] : '-'; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="typography-section__inner">
			<h3 class="ui-title-block_light">Prelim Hot Items</h3>
			<div style="border-bottom: 4px solid #D35411;"></div>
		</div>
		<?php
			$count = 1;
			if(isset($prelim_details['tax']) && !empty($prelim_details['tax']))
			{				
		?>
				<div class="entry-main">
					<div class="entry-header">
						<div class="alert alert-1">
							<div class="alert__inner">
								<h3 class="alert-titlesmall2">Property Taxes</h3>
								<!-- <div class="alert-text">If there are any urgent Items they
									will appear below.</div> -->
							</div>
						</div>
					</div>
					<div class="entry-content">
						<?php
							$tax = json_decode($prelim_details['tax'],TRUE);

							if(isset($tax) && !empty($tax))
							{

						?>
								<ol start="<?php echo $count; ?>">
						<?php
								foreach ($tax as $key => $tax_val) 
								{
									$count++;
							?>
									<li><p><?php echo nl2br($tax_val); ?>
									</p></li></br>
							<?php
								}
							?>
								</ol>
							<?php
							}
							else 
							{
						?>
								<p><?php echo "No data found"; ?></p>
						<?php
							}
						?>
						
					</div>
				</div>
		<?php
			}
		?>
		<?php
			if(isset($prelim_details['easement']) && !empty($prelim_details['easement']))
			{
		?>
				<div class="entry-main">
					<div class="entry-header">
						<div class="alert alert-6">
							<div class="alert__inner">
								<h3 class="alert-titlesmall3">Easements</h3>
								<!-- <div class="alert-text">If there are any urgent Items they
									will appear below.</div> -->
							</div>
						</div>
					</div>
					<div class="entry-content">
						<?php
							$easements = json_decode($prelim_details['easement'],TRUE);
							if(isset($easements) && !empty($easements))
							{
						?>
								<ol start="<?php echo $count; ?>">
						<?php
								foreach ($easements as $key => $easement) 
								{
									$count++;
						?>									
									<li><p><?php echo nl2br($easement); ?></p></li></br>
						<?php
								}
						?>
								</ol>
						<?php
							}
							else
							{
						?>
								<p><?php echo "No data found"; ?></p>
						<?php
							}							
						?>				
					</div>
				</div>
		<?php
			}
		?>
		<?php 
			if(isset($prelim_details['lien']) && !empty($prelim_details['lien']))
			{				
		?>
				<div class="entry-main">
					<div class="entry-header">
						<div class="alert alert-4">
							<div class="alert__inner">
								<h3 class="alert-titlesmall1">Liens & Judgements</h3>
								<!-- <div class="alert-text">If there are any urgent Items they
									will appear below.</div> -->
							</div>
						</div>
					</div>
					<div class="entry-content">
						<?php
							$liens = json_decode($prelim_details['lien'],TRUE);
							if(isset($liens) && !empty($liens))
							{
								$tax_count = count($tax)+1;

						?>
								<ol start="<?php echo $count; ?>">
						<?php
								foreach ($liens as $key => $lien) 
								{
									$count++;
							?>
									<li><p><?php echo nl2br($lien); ?></p></li></br>
							<?php
								}
							?>
								</ol>
							<?php
							}
							else 
							{
						?>
								<p><?php echo "No data found"; ?></p>
						<?php
							}
						?>
						
					</div>
				</div>
		<?php
			}
		?>
		<?php 
			if(isset($prelim_details['requirements']) && !empty($prelim_details['requirements']))
			{
		?>
				<div class="entry-main">
					<div class="entry-header">
						<div class="alert alert-7">
							<div class="alert__inner">
								<h3 class="alert-titlesmall4">Requirements</h3>
								<!-- <div class="alert-text">If there are any urgent Items they
									will appear below.</div> -->
							</div>
						</div>
					</div>
					<div class="entry-content">
						<?php
							$requirements = json_decode($prelim_details['requirements'],TRUE);
							if(isset($requirements) && !empty($requirements))
							{
						?>
								<ol start="<?php echo $count; ?>">
						<?php
								foreach ($requirements as $key => $requirement) 
								{
							?>
									<li><p><?php echo nl2br($requirement); ?></p></li></br>
							<?php
								}
							?>
								</ol>
							<?php
							}
							else
							{
						?>
								<p><?php echo "No data found"; ?></p>
						<?php
							}
						?>			
					</div>
				</div>
		<?php
			}
		?>
		<?php
			if(isset($prelim_details['easement']) && !empty($prelim_details['easement']))
			{
		?>
				<div class="entry-main" style="display: none;">
					<div class="entry-header">
						<div class="alert alert-6">
							<div class="alert__inner">
								<h3 class="alert-titlesmall3">Easements</h3>
								<!-- <div class="alert-text">If there are any urgent Items they
									will appear below.</div> -->
							</div>
						</div>
					</div>
					<div class="entry-content">
						<?php
							$easements = json_decode($prelim_details['easement'],TRUE);
							if(isset($easements) && !empty($easements))
							{
						?>
								<ol>
						<?php
								foreach ($easements as $key => $easement) 
								{
						?>									
									<li><p><?php echo nl2br($easement); ?></p></li></br>
						<?php
								}
						?>
								</ol>
						<?php
							}
							else
							{
						?>
								<p><?php echo "No data found"; ?></p>
						<?php
							}							
						?>				
					</div>
				</div>
		<?php
			}
		?>
		
		<?php 
			if(isset($prelim_details['restrictions']) && !empty($prelim_details['restrictions']))
			{
		?>
				<div class="entry-main" style="display: none;">
					<div class="entry-header">
						<div class="alert alert-8">
							<div class="alert__inner">
								<h3 class="alert-titlesmall5">Restrictions</h3>
								<!-- <div class="alert-text">If there are any urgent Items they
									will appear below.</div> -->
							</div>
						</div>
					</div>
					<div class="entry-content">
						<?php
							$restrictions = json_decode($prelim_details['restrictions'],TRUE);
							if(isset($restrictions) && !empty($restrictions))
							{
						?>
								<ol>
						<?php
								foreach ($restrictions as $key => $restriction) 
								{
						?>
									<li><p><?php echo nl2br($restriction); ?></p></li></br>
						<?php
								}
						?>
								</ol>
						<?php
							}
							else
							{
						?>
								<p><?php echo "No data found"; ?></p>
						<?php
							}
						?>						
					</div>
				</div>
		<?php
			}
		?>
	</article>
</div>