<!DOCTYPE html>
<html lang="en">
    <head>
        <title> Pacific Coast Title Company - Farm Package Form </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="stylesheet" type="text/css"  href="http://www.pct.com/order/css/smart-forms.css">
        <link rel="stylesheet" type="text/css"  href="http://www.pct.com/order/css/font-awesome.min.css">
        
        <script type="text/javascript" src="http://www.pct.com/order/js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="http://www.pct.com/order/js/jquery.form.min.js"></script>
        <script type="text/javascript" src="http://www.pct.com/order/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="http://www.pct.com/order/js/additional-methods.min.js"></script>
        <script type="text/javascript" src="http://www.pct.com/order/js/smart-form.js"></script>   
		<script type="text/javascript" src="http://www.pct.com/order/js/en/jquery-cloneya.min.js"></script>
        
        <!--[if lte IE 9]>
            <script type="text/javascript" src="http://www.pct.com/order/js/jquery.placeholder.min.js"></script>
        <![endif]-->    
        
        <!--[if lte IE 8]>
            <link type="text/css" rel="stylesheet" href="http://wwwwpct.com/order/css/smart-forms-ie8.css">
        <![endif]-->
		
		<script type="text/javascript">
		jQuery(document).ready(function($){
		
			/* Simple Cloning
			------------------------------------------------- */		
			$('#clone-fields').cloneya();
			
			/* Group Cloning
			------------------------------------------------- */			
			$('#clone-group-fields').cloneya({
				maximum: 5
			});	
			
			$('#clone2-group-fields').cloneya({
				maximum: 5
			});	
			
			
			/* MIN MAX Cloning 
			------------------------------------------------- */			
			$('#clone-min-max').cloneya({
				maximum: 3,
				minimum: 1
			});	
			
			/* Animated Cloning with custom events 
			------------------------------------------------- */
			$('#clone-animate').cloneya()
			.on('before_clone.cloneya', function (event, toclone) {
				// do something
			})
			.on('after_clone.cloneya', function (event, toclone, newclone) {
				// do something   
			})
			.on('before_append.cloneya', function (event, toclone, newclone) {
				$(newclone).css('display', 'none');
				$(toclone).fadeOut('fast', function () {
					$(this).fadeIn('fast');
				});
			})
			.on('after_append.cloneya', function (event, toclone, newclone) {
				$(newclone).slideToggle();
			})
			.off('remove.cloneya')
			.on('remove.cloneya', function (event, clone) {
				$(clone).slideToggle('slow', function () {
					$(clone).remove();
				});
			})
			.on('after_delete.cloneya', function () {
				  
			});
					
		});   
    </script>
		
    </head>
	
	
	
	
	
    
    <body class="intro2">
        <div class="smart-wrap">
            <div class="smart-forms smart-container wrap-2">
            
                <div class="form-header header-primary">
                    <h4><i class="fa fa-commentsz"></i>Order Your Farm Package</h4>
              </div><!-- end .form-header section -->
                
                <form method="post" action="http://www.pct.com/order/php/smartprocess2.php" id="smart-form" enctype="multipart/form-data">
                    <div class="form-body">
                    
                       <div class="spacer-b40">
                            <div class="tagline"><span>If Boundaries </span></div><!-- .tagline -->
                        </div>                 
                    
                        <div class="frm-row">
                        
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="NorthOf" id="NorthOf" class="gui-input" placeholder="North Of:">
                                    <span class="field-icon"><i class="fa fa-map"></i></span>
                                </label>
                            </div><!-- end section --> 
                            
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="SouthOf" id="SouthOf" class="gui-input" placeholder="South Of:">
                                    <span class="field-icon"><i class="fa fa-map"></i></span>  
                                </label>
                            </div><!-- end section -->
                        
                        </div><!-- end frm-row section -->
                        
                        <div class="frm-row">
                        
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="EastOf" id="EastOf" class="gui-input" placeholder="East Of:">
                                    <span class="field-icon"><i class="fa fa-map"></i></span>  
                                </label>
                            </div><!-- end section -->                   
                            
                             <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="WestOf" id="WestOf" class="gui-input" placeholder="West Of:">
                                    <span class="field-icon"><i class="fa fa-map"></i></span>  
                                </label>
                            </div><!-- end section -->
                        
                        </div><!-- end frm-row section -->
						
						 <div class="spacer-b30 spacer-t30">
							<div class="tagline"><span>** OR ** If Centroid</span></div><!-- .tagline -->
						</div>                         
                    
                    <div class="section">
                    	<label class="field prepend-icon">
                        	<input type="text" name="FarmProperty" id="FarmProperty" class="gui-input" placeholder="Property Address...">
                            <span class="field-icon"><i class="fa fa-home"></i></span>  
                        </label>
                    </div><!-- end section -->
                        
                        
					   
					   <div class="spacer-b30 spacer-t30">
                            <div class="tagline"><span> Property Type </span></div><!-- .tagline -->
                        </div>
					   
					   <div class="frm-row">
                       <div class="section colm colm3">
                        
								<div class="option-group field">
                            	
									<label class="option block spacer-t10">
										<input type="checkbox" name="Sfr" value="Sfr">
										<span class="checkbox"></span> SFR's           
									</label>                                
                                
								</div><!-- end .option-group section --> 
                                                    
							</div><!-- end .colm section -->
						
							<div class="section colm colm3">
							
								<div class="option-group field">
									<label class="option block spacer-t10">
										<input type="checkbox" name="Condo" value="Condo">
										<span class="checkbox"></span> Condo's           
									</label>                                
								</div><!-- end .option-group section --> 
														
							</div><!-- end .colm section -->
						
						<div class="section colm colm3">
                            <div class="option-group field">
                                <label class="option block spacer-t10">
                                    <input type="checkbox" name="Units" value="Units">
                                    <span class="checkbox"></span> 2-4 Units           
                                </label>                                
                                
                            </div><!-- end .option-group section --> 
                                                    
                        </div><!-- end .colm section --> 
						<div class="section colm colm3">
                            <div class="option-group field">
                                <label class="option block spacer-t10">
                                    <input type="checkbox" name="Vacant" value="Vacant">
                                    <span class="checkbox"></span> Vacant           
                                </label>                                
                                
                            </div><!-- end .option-group section --> 
                                                    
                        </div><!-- end .colm section -->
						
					</div><!-- end section -->
					 <div class="frm-row">
						
						<div class="section colm colm3">
                            <div class="option-group field">
                                <label class="option block spacer-t10">
                                    <input type="checkbox" name="Commercial" value="Commercial">
                                    <span class="checkbox"></span> Commercial           
                                </label>                                
                                
                            </div><!-- end .option-group section --> 
                                                    
                        </div><!-- end .colm section -->
						
						
						<div class="section colm colm3">
                            <div class="option-group field">
                                <label class="option block spacer-t10">
                                    <input type="checkbox" name="Other" value="Other">
                                    <span class="checkbox"></span> Other           
                                </label>                                
                                
                            </div><!-- end .option-group section --> 
                                                    
                        </div><!-- end .colm section -->						
                    </div><!-- end section -->
					
					 <div class="spacer-b30 spacer-t30">
                            <div class="tagline"><span> Ownership Type </span></div><!-- .tagline -->
                     </div>
					   
						 <div class="frm-row">
						 
						  <div class="section colm colm6">
                            <label class="field select">
                                <select id="Owntype" name="Owntype">
                                    <option value="">Select Ownertype...</option>
                                    <option value="Owner">Owner</option>
                                    <option value="Nonowner">Non-Owner</option>
                                    <option value="Both">Both</option>
                                </select>
                                <i class="arrow double"></i>                    
                            </label>  
                        </div><!-- end section -->
                    
                        <div class="section colm colm6">
                            <label class="field select">
                                <select id="Record" name="Record">
                                    <option value="">Number of Records...</option>
                                    <option value="100">100</option>
                                    <option value="250">250</option>
                                    <option value="500">500</option>
									<option value="1000">1000</option>
									<option value="all">All Available</option>
                                </select>
                                <i class="arrow double"></i>                    
                            </label>  
                        </div><!-- end section -->
						</div><!-- end frm-row section --> 
						
						 <div class="spacer-b30 spacer-t30">
                            <div class="tagline"><span> Delivery Format </span></div><!-- .tagline -->
                        </div>
							
							
							<div class="frm-row">
							
							<div class="frm-row">
								<div class="section colm colm3">
							
									<div class="option-group field">
									
										<label class="option block spacer-t10">
											<input type="checkbox" name="Excel" value="Excel">
											<span class="checkbox"></span> CSV/Excel           
										</label>                                
									
									</div><!-- end .option-group section --> 
														
								</div><!-- end .colm section -->
						
								<div class="section colm colm3">
								
									<div class="option-group field">
										<label class="option block spacer-t10">
											<input type="checkbox" name="Pdf" value="Pdf">
											<span class="checkbox"></span> 5-Line PDF           
										</label>                                
									</div><!-- end .option-group section --> 
															
								</div><!-- end .colm section -->
						
								<div class="section colm colm3">
									<div class="option-group field">
										<label class="option block spacer-t10">
											<input type="checkbox" name="Walking" value="Walking">
											<span class="checkbox"></span>Walking Farm           
										</label>                                
										
									</div><!-- end .option-group section --> 
															
								</div><!-- end .colm section --> 
								<div class="section colm colm3">
									<div class="option-group field">
										<label class="option block spacer-t10">
											<input type="checkbox" name="Labels" value="Labels">
											<span class="checkbox"></span> Labels          
										</label>                                
										
									</div><!-- end .option-group section --> 
															
								</div><!-- end .colm section -->						
							</div><!-- end section -->
								 
		

					</div>
							
					   
					    <div class="spacer-b30 spacer-t30">
                            <div class="tagline"><span> Delivery Info </span></div><!-- .tagline -->
                        </div>
					   
								<div class="section colm colm6">
                          <label class="field prepend-icon">
                                    <input type="text" name="ClientName" id="ClientName" class="gui-input" placeholder="Client Name">
                                    <span class="field-icon"><i class="fa fa-user"></i></span>
                                </label>
                       </div><!-- end section --> 
					   <div class="section colm colm6">
                          <label class="field prepend-icon">
                                    <input type="text" name="CompanyName" id="CompanyName" class="gui-input" placeholder="Company Name">
                                    <span class="field-icon"><i class="fa fa-building"></i></span>
                                </label>
                       </div><!-- end section --> 
					   <div class="section colm colm6">
                          <label class="field prepend-icon">
                                    <input type="text" name="DeliveryAddress" id="DeliveryAddress" class="gui-input" placeholder="Delivery Address">
                                    <span class="field-icon"><i class="fa fa-building"></i></span>
                                </label>
                       </div><!-- end section --> 
					  <div class="section colm colm6">
                                    <label class="field select">
                                        <select id="SalesRep" name="SalesRep">
                                            <option value="">Sales Rep...</option>
                                            <option value="Angeline Ahn">Angeline Ahn</option>
                                            <option value="Bethany Cummins">Bethany Cummins</option>
                                            <option value="Cibeli Tregembo">Cibeli Tregembo</option>
                                            <option value="David Gomez">David Gomez</option>
											<option value="Edgar Rivas">Edgar Rivas</option>
                                            <option value="Hai Tran">Hai Tran</option>
                                            <option value="Hugo Lopez">Hugo Lopez</option>
                                            <option value="Justin Nouri">Justin Nouri</option>
											<option value="Kim Buchok">Kim Buchok</option>
                                            <option value="Linda Ruiz">Linda Ruiz</option>
                                            <option value="Lisa Lee">Lisa Lee</option>
                                            <option value="Lou Morreale">Lou Morreale</option>
											<option value="Malay Wadhwa">Malay Wadhwa</option>
                                            <option value="Michael Nouri">Michael Nouri</option>
                                            <option value="Mike Johnson">Mike Johnson</option>
                                            <option value="Nelson Torres">Nelson Torres</option>
											<option value="Nick Watt">Nick Watt</option>
                                            <option value="Richard Bohn">Richard Bohn</option>
                                            <option value="Rick Cervantez">Rick Cervantez</option>
                                            <option value="Scott Smith">Scott Smith</option>
											<option value="Sonia Flores">Sonia Flores</option>
											<option value="Veronica Sanchez">Veronica Sanchez</option>											
                                        </select>
                                        <i class="arrow double"></i>                    
                                    </label>  
                                </div><!-- end section -->
					   
					   
                         <div class="spacer-b30 spacer-t30">
                            <div class="tagline"><span> Special Instructions </span></div><!-- .tagline -->
                        </div>
						
						
                        
                        <div class="section spacer-t20">
                            <label class="field prepend-icon">
                                <textarea class="gui-textarea" id="sendermessage" name="sendermessage" placeholder="Additional details"></textarea>
                                <span class="field-icon"><i class="fa fa-comments"></i></span>
                                <span class="input-hint"> <strong>NOTE:</strong> Be as detailed as possible for better feedback.</span>   
                            </label>
                        </div><!-- end section -->
                        
                       
                        
                        <div class="result spacer-b10"></div><!-- end .result  section -->                     
                        
                        <div class="section progress-section">
                            <div class="progress-bar progress-animated bar-primary">
                                <div class="bar"></div>
                                <div class="percent">0%</div>
                            </div>
                        </div><!-- end progress section --> 
                                                                                                                    
                    </div><!-- end .form-body section -->
                    <div class="form-footer">
                        <button type="submit" data-btntext-sending="Sending..." class="button btn-primary">Submit</button>
                        <button type="reset" class="button"> Cancel </button>
						<a href="http://www.pct.com">Homepage</a>
                    </div><!-- end .form-footer section -->
                </form>
            </div><!-- end .smart-forms section -->
        </div><!-- end .smart-wrap section -->
    </body>
</html>
