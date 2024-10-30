<div id="disconnect-dialog-confirm" title="Disconnect from JobProgress">
      <p>
      	Do you really want to disconnect?
      </p>
  </div>

<form class="jp-btn-connect disconnect-form" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>">
	<input type="hidden" name="disconnect" value = "1"/>
	<input class="btn btn-red jp-disconnect" value="Disconnect">
</form>
<h5 class="shortcode-text">For Use customer form please add this shortcode <strong>[jobprogress_customer_form_code]</strong></h5>
 <?php if($jp_user): ?>
<div class="subsDetail-container">
	<div class="profile-header">
		<div class="address-container">
			<?php if(ine($jp_user['company_details'], 'logo')): ?>
			<div class="pull-right address-container-profile-pic">
				<div class="company-image-viewer profile-pic" image-url="https://staging.jobprogress.com/api/public/uploads/company/users/2_1450702741.jpg">
						<img class="img-thumbnail" alt="Logo" src="<?php echo esc_url($jp_user['company_details']['logo']); ?>">
				</div>
			</div>
			<?php endif; ?>
			<div class="address-bar address-bar-section-first">
				<div class="subscriber-info">
					<h3>SUBSCRIBER DETAILS</h3>
					<?php
					$address = array();

					if(ine($jp_user['profile'], 'address')) {
						$address[] = $jp_user['profile']['address'];  
					}

					if(ine($jp_user['profile'], 'city')) {
						$address[] = $jp_user['profile']['city'];  
					}

					if(ine($jp_user['profile'], 'state')) {
						$address[] = $jp_user['profile']['state'];  
					}

					if(ine($jp_user['profile'], 'zip')) {
						$address[] = $jp_user['profile']['zip'];
					}

					if(ine($jp_user['profile'], 'country')) {
						$address[] = $jp_user['profile']['country'];  
					}
					if(!empty($address)):
					?>
					<div class="info-label">
						<label>Address: </label>
						<span>
							<span class=""><?php echo esc_html(implode(', ', $address)) ?></span>
						</span>
					</div>
					<?php endif; ?>

					<?php if(ine($jp_user['profile'], 'additional_phone')): ?>			
					<div class="info-label ">
						<?php $phone = reset($jp_user['profile']['additional_phone']);?>
						<label class=""><?php echo esc_html(ucfirst($phone['label'])); ?>: </label>
						<span class="">
							<?php 
							echo esc_html(format_number($phone['phone']));
							if(!empty($phone['ext'])):
							?>
							<span ng-if="phone.extension" class="userData-extension  ">Extension: 
								<?php echo esc_html($phone['ext']); ?></span>
							<?php endif; ?>
						</span>
					</div>
					<?php endif ?>
					<div class="info-label">
						<label>Email: </label>
						<span>
							<a href="mailto:<?php echo esc_url($jp_user['email']) ?>" target="_top">
							<?php echo esc_html($jp_user['email']); ?></a>
						</span>
					</div>
				</div>
			</div>

			<div class="address-bar address-bar-section-second">
				<div class="subscriber-info">
					<h3>COMPANY DETAILS</h3>
					<?php
					$billing_address = array();

					if(ine($jp_user['company_details'], 'office_address')) {
						$billing_address[] = $jp_user['company_details']['office_address'];  
					}

					if(ine($jp_user['company_details'], 'office_city')) {
						$billing_address[] = $jp_user['company_details']['office_city'];  
					}

					if(ine($jp_user['company_details'], 'office_state')
						&& ine($jp_user['company_details']['office_state'], 'name') ) {
						$billing_address[] = $jp_user['company_details']['office_state']['name'];
					}

					if(ine($jp_user['company_details'], 'office_zip')) {
						$billing_address[] = $jp_user['company_details']['office_zip'];  
					}

					if(ine($jp_user['company_details'], 'office_country')
						&& ine($jp_user['company_details']['office_country'], 'name') ) {
						$billing_address[] = $jp_user['company_details']['office_country']['name'];
					}
					?>
					<?php if(!empty($billing_address)): ?>
					<div class="info-label " style="">
						<label>Address: </label>
						<span>
							<span class="">
								<?php echo esc_html(implode(', ', $billing_address)); ?>
							</span>
						</span>
					</div>
					<?php endif ?>
					<?php if(ine($jp_user['company_details'], 'office_phone')): ?>
					<div class="info-label">
						<label>Phone: </label>
						
						<span class=""><?php echo esc_html(format_number($jp_user['company_details']['office_phone'])); ?></span>
					</div>
					<?php endif; ?>
					<div class="info-label">
						<label>Email: </label>
						<span>
							<a href="mailto:<?php echo esc_url($jp_user['company_details']['office_email']); ?>" target="_top">
								<?php echo esc_html($jp_user['company_details']['office_email']); ?>
							</a>
						</span>
					</div>
					<?php if(ine($jp_user['company_details'], 'office_fax')): ?>
					<div class="info-label ">
						<label>Fax: </label>
						<span class=""><?php echo esc_html(format_number($jp_user['company_details']['office_fax'])); ?></span>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>