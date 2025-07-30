<style>
	.img-circle {
		border-radius: 100%;
	}
</style>
<section class="section-type-4a section-default typography-section-border" style="padding-bottom:0px;">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="typography-section__innera">
					<h2 class="ui-title-block ui-title-block_light">My Profile</h2>
					<div class="ui-decor-1a bg-accent"></div>
					<h3 class="ui-title-block_light">A little about you.</h3>
				</div>
                <?php if(!empty($success)) {?>
                    <div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible">
                        <?php foreach($success as $sucess) {
                                echo $sucess."<br \>";	
                            }?>
                    </div>
                    <?php } 
                        if(!empty($errors)) {?>
                    <div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible">
                        <?php foreach($errors as $error) {
                                echo $error."<br \>";	
                            }?>
                    </div>
                <?php } ?>
				<section class="section-default-contact-2 b-contact b-contact_mod-a ">
					<div class="container">
						<div class="row">

							<div class="col-xs-6">
								<div class="col-xs-12">
									<div class="col-sm-6">
										<?php if($userInfo['profile_img']): ?>
											<img class="img-responsive img-circle" src="<?php echo env('AWS_PATH').'hr/user/'.$userInfo['profile_img'];?>" alt="profile image">
										<?php else : ?>
											<img class="img-responsive img-circle" src="<?php echo base_url('assets/frontend/hr/images/default.png') ?>" alt="profile image">
										<?php endif; ?>
									</div>
									<div class="col-sm-6">
										<h2 class="ui-title-block-3 ui-title-block-4_sm"><?php echo $userInfo['first_name']." ".$userInfo['last_name'];?></h2>
										<div class="ui-decor-4 ui-decor-4_sm"></div>
										<h3 class="b-sm-about__title2"><?php echo $userInfo['position']; /* ($userInfo['user_type_id'] == 1) ? 'Employee' : 'Branch Manager'; */ ?></h3>
									</div>

								</div>
								<!-- <div class="col-xs-12">
									<form data-parsley-validate="" class="ui-form ui-form-2 ui-form-space" action="<?php echo base_url();?>hr/update-password" method="post" id="update-pwd-form">
										<div class="row">
											<div class="col-md-6">
												<input class="form-control" id="password" name="password" type="password" placeholder="New Password" data-parsley-required-message="Please enter new password" required>
                                                <?php if(!empty($pwd_err_msg)) { ?>         
                                                    <ul class="parsley-errors-list filled" id="parsley-id-5" aria-hidden="false">
                                                        <li class="parsley-required"><?php echo $pwd_err_msg;?></li>
                                                    </ul>
                                                <?php } ?>
                                            </div>
										</div>
                                        <div class="row">
											<div class="col-md-6">
												<input class="form-control" id="confirm_password" name="confirm_password" type="password" placeholder="Confirm Password" data-parsley-trigger="change" data-parsley-equalto="#password" data-parsley-required-message="Please enter confirm password" data-parsley-equalto-message="Confirm password should be same with new password." required>
                                                <?php if(!empty($confirm_pwd_err_msg)){ ?>     
                                                    <ul class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false">
                                                        <li class="parsley-required"><?php echo $confirm_pwd_err_msg;?></li>
                                                    </ul>
                                                <?php } ?>
											</div>
											<div class="col-md-6">
												<button type="submit" class="btn btn-grad-1 btn-round">Update Pwd</button>
											</div>
										</div>
									</form>
								</div> -->
								<!-- <div class="col-xs-12">
									<form data-parsley-validate="" class="ui-form ui-form-2 ui-form-space" action="<?php echo base_url();?>hr/upload-profile-pic" method="post" id="update-profile-img-form" enctype="multipart/form-data">
										<div class="row">
											<div class="col-md-6">
												<label class="pic">
													<input type="file" id="profile_img" name="profile_img" accept="image/*" aria-label="Profile Image" data-parsley-required-message="Please upload profile pic" required>
                                                    <?php if(!empty($profile_img_error_msg)){ ?>     
                                                        <ul class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false">
                                                            <li class="parsley-required"><?php echo $profile_img_error_msg;?></li>
                                                        </ul>
                                                    <?php } ?>
												</label>
											</div>
                                            <input type="hidden" id="user_id" name="user_id" value="<?php echo $userInfo['id'];?>">
											<div class="col-md-6">
												<button type="submit" class="btn btn-grad-1 btn-round">Upload Pic</button>
											</div>
										</div>
									</form>
								</div> -->
							</div>

							<div class="col-md-6">
								<?php
								$user_adderss_arr = array();
								$user_adderss_arr[] = $userInfo['address'];
								$user_adderss_arr[] = $userInfo['city'];
								$user_adderss_arr[] = $userInfo['state'];
								$user_adderss_arr[] = $userInfo['zip'];
								$user_adderss_arr =  array_filter($user_adderss_arr);
								?>
								<form method="post" class="ui-form ui-form-2 ui-form-space" action="<?php echo base_url('hr/update-profile') ?>">
								<div class="b-contact-desc">
									<!-- <div class="b-contact-desc__item">
										<div class="b-contact-desc__name"><i class="icon fa fa-home"></i>Home address</div>
										<div class="b-contact-desc__info profile-show-hide">
                                            <?php echo (count($user_adderss_arr)) ? implode(",",$user_adderss_arr) : "-" ;?>
										</div>
											<div class="b-contact-desc__info profile-show-hide hide">
												<input type="text" class="form-control" value="<?php echo $userInfo['address']?>" placeholder="Address line" name="address" />
												<input type="text" class="form-control" value="<?php echo $userInfo['city']?>" placeholder="city" name="city" />
												<input type="text" class="form-control" value="<?php echo $userInfo['state']?>" placeholder="state" name="state" />
												<input type="text" class="form-control" value="<?php echo $userInfo['zip']?>" placeholder="zip" name="zip" />
											</div>

									</div> -->
									<div class="b-contact-desc__item">
										<div class="b-contact-desc__name"><i class="icon fa fa-phone"></i> Mobile phone</div>
										<div class="b-contact-desc__info profile-show-hide">
                                            <?php echo (!empty($userInfo['cell_phone'])) ? $userInfo['cell_phone'] : '-';?>
                                        </div>
										
										<div class="b-contact-desc__info profile-show-hide hide">
												<input type="tel"  class="form-control" value="<?php echo $userInfo['cell_phone']?>" placeholder="Mobile Number" name="cell_phone" />
										</div>
										
									</div>

									<!-- <div class="b-contact-desc__item">
										<div class="b-contact-desc__name"><i class="icon fa fa-phone"></i> Home phone</div>
										<div class="b-contact-desc__info profile-show-hide">
                                            <?php echo (!empty($userInfo['home_phone'])) ? $userInfo['home_phone'] : '-';?>
                                        </div>
										
										<div class="b-contact-desc__info profile-show-hide hide">
												<input type="tel" class="form-control" value="<?php echo $userInfo['home_phone']?>" placeholder="Home phone Number" name="home_phone" />
										</div>
										
									</div> -->
									
									<div class="b-contact-desc__item">
										<div class="b-contact-desc__name"><i class="icon fa fa-birthday-cake"></i> Birthday
										</div>
										<div class="b-contact-desc__info profile-show-hide"><?php echo (!empty($userInfo['birth_date']) && strtotime($userInfo['birth_date'])) ? date("m/d/Y", strtotime($userInfo['birth_date'])) : '-';?></div>
										<div class="b-contact-desc__info profile-show-hide hide">
												<input type="date" class="form-control" value="<?php echo (!empty($userInfo['birth_date']) && strtotime($userInfo['birth_date'])) ? $userInfo['birth_date'] : '';?>" placeholder="Birth Date" name="birth_date" />
										</div>
									</div>
									<div class="b-contact-desc__item">
										<div class="b-contact-desc__name"><i class="icon fa fa-envelope"></i> Email</div>
										<div class="b-contact-desc__info "><?php echo $userInfo['email'];?></div>
										
									</div>
									<div class="b-contact-desc__item">
										<div class="b-contact-desc__name"><i class="icon fa fa-calendar"></i> Hire Date
										</div>
										<div class="b-contact-desc__info"><?php echo date("m/d/Y", strtotime($userInfo['hire_date']));?></div>
									</div>
									<div class="b-contact-desc__item">
										<div class="b-contact-desc__name"><i class="icon fa fa-building"></i> Department</div>
										<div class="b-contact-desc__info "><?php echo $userInfo['department_name'];?></div>
									</div>
									<div class="b-contact-desc__item">
										<div class="b-contact-desc__name"><i class="icon fa fa-globe"></i> Position</div>
										<div class="b-contact-desc__info "><?php echo $userInfo['position'];?></div>
									</div>
									<!-- <div class="col-md-12">
                                        <button type="button" class="btn btn-grad-1 btn-round profile-show-hide profile-show-hide-btn">Edit Profile</button>
										<div class="row">
												<div class="col-md-6">
													<button type="submit" class="btn btn-grad-1 btn-round profile-show-hide hide">Update</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-grad-1 btn-round profile-show-hide profile-show-hide-btn hide">Cancel</button>
												</div>
										</div>
                                    </div> -->
								</div>
								</form>
							</div>

							<div class="smart-forms" style="display:none;">
								<div class="col-md-6">
									<form method="POST" id="smart-form" enctype="multipart/form-data">
										<div class="form-body">
											<div class="spacer-b30 spacer-t30">
												<div class="tagline">
                                                    <span>Your Details</span>
												</div>
											</div>

											<div class="frm-row">
												<div class="section colm colm6">
													<label class="field prepend-icon">
														<input value="<?php echo $userInfo['first_name'];?>"
															type="text" name="first_name" id="first_name" class="gui-input"
															placeholder=" First Name">
														<span class="field-icon"><i class="fa fa-user"></i></span>
													</label>
												</div>

												<div class="section colm colm6">
													<label class="field prepend-icon">
														<input value="<?php echo $userInfo['last_name'];?>"
															type="text" name="last_name" id="last_name"
															class="gui-input" placeholder="Last Name">
														<span class="field-icon"><i class="fa fa-user"></i></span>
													</label>
												</div>
											</div>

											<div class="frm-row">
												<div class="section colm colm6">
													<label class="field prepend-icon">
														<input value="<?php echo $userInfo['telephone_no'];?>"
															type="tel" name="telephone_no" id="telephone_no"
															class="gui-input" placeholder="Telephone">
														<span class="field-icon"><i class="fa fa-phone-square"></i></span>
													</label>
												</div>
												<div class="section colm colm6">
													<label class="field prepend-icon">
														<input value="<?php echo $userInfo['hire_date'];?>"
															type="text" name="hire_date" id="hire_date"
															class="gui-input" placeholder="Hire Date">
														<span class="field-icon"><i class="fa fa-envelope"></i></span>
													</label>
												</div>
											</div>

                                            <div class="frm-row">
				
												<div class="section colm colm12">
													<label class="field prepend-icon">
														<input value="<?php echo $userInfo['email'];?>"
															type="email" name="email" id="email"
															class="gui-input" placeholder="Email address">
														<span class="field-icon"><i class="fa fa-envelope"></i></span>
													</label>
												</div>
											</div>

											<div class="frm-row">
												<div class="section colm colm6">
													<label class="field prepend-icon">
														<input value="<?php echo $userInfo['address'];?>"
															type="text" name="StreetAddress" id="StreetAddress"
															class="gui-input" placeholder="Street Address">
														<span class="field-icon"><i class="fa fa-envelope"></i></span>
													</label>
												</div>

                                                <div class="section colm colm6">
													<label class="field prepend-icon">
														<input value="<?php echo $userInfo['city'];?>" type="text"
															name="City" id="City" class="gui-input" placeholder="City">
														<span class="field-icon"><i class="fa fa-user"></i></span>
													</label>
												</div>
											</div>

											<div class="frm-row">
                                                <div class="section colm colm6">
													<label class="field prepend-icon">
														<input value="<?php echo $userInfo['state'];?>"
															type="text" name="state" id="state"
															class="gui-input" placeholder="State">
														<span class="field-icon"><i class="fa fa-user"></i></span>
													</label>
												</div>

												<div class="section colm colm6">
													<label class="field prepend-icon">
														<input value="<?php echo $userInfo['zip_code'];?>"
															type="text" name="Zipcode" id="Zipcode" class="gui-input"
															placeholder="Zipcode">
														<span class="field-icon"><i class="fa fa-envelope"></i></span>
													</label>
												</div>
											</div>

											<div class="spacer-b30 spacer-t30">
												<div class="tagline">
													<span>Find Your Property</span>
												</div>
											</div>

											

											<div id="address_container">
												<div class="frm-row">
													<div class="section colm colm12">
														<label class="field prepend-icon">
															<input type="text" name="Property" id="property-search"
																class="gui-input" placeholder="Property Address">
															<span class="field-icon"><i class="fa fa-user"></i></span>
														</label>
													</div>
												</div>

												<div class="frm-row">
													<div class="section colm colm3">
														<a class="button btn-primary search-property search-property-button"
															href="javascript:void(0);" id="search-btn">Update
															</a>
													</div>
													
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
							<!-- </div> -->
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</section>
