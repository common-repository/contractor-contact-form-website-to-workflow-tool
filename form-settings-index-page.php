<div class="wrap settings-form-screen">
	<h1>Form Settings</h1>
	<p class="settings-desc">Drag &amp; Drop to reorder the sections according to your preference. The saved settings will reflect on customer form. You can also hide or show the fields.
	<span style="display: block;"><span style="color: #444;">Note:</span> Required fields cannot be hidden.</span></p>
	<form method="post" action="options.php">
		<div class="form-settings-wrap" id="form-sortable">
			<div id="sortable_fields" class="sortable-fields-wrap">
				<?php
					$diabledFields = ['customer_type', 'customer_phone', 'trades', 'description'];
					settings_fields( 'jp_form_settings' ); 
					$count = 1;
					if(empty($settings)) {
						$settings = get_form_default_settings();
					}

					foreach($settings as $setting) {

						switch($setting['name']) {

							case 'customer_name':
							case 'company_name': 
								$disable_name = ($setting['name'] == 'customer_name') ? 'disabled' : '';
								$disable_company = ($setting['name'] == 'company_name') ? 'disabled' : '';
								?>
								<div class="ui-state-default">
									<div class="sortable-field-item multi-field-wrap">
										<span class="drag-icon">
											<svg viewBox="0 0 32 32" role="presentation"><path fill-rule="evenodd" clip-rule="evenodd" d="M18 8a2 2 0 104 0 2 2 0 00-4 0zm2 10a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-8 0a2 2 0 110-4 2 2 0 010 4zm-2-10a2 2 0 104 0 2 2 0 00-4 0zm2-6a2 2 0 110-4 2 2 0 010 4z" fill="currentColor"/></svg>
										</span>
										<div class="multi-field-inner">
											<span class="field-name"><?php echo esc_html($setting['title']); ?></span>
											<div class="multi-field-items-wrap">
												<!-- Residential Type -->
												<div class="multi-field-item">
													<div class="field-visibility field-hide-setting">
														<input value="<?php echo esc_attr($setting['name']); ?>" name="jp_customer_form_fields[field<?php echo esc_attr($count); ?>][name]" type="hidden">
														<input value="<?php echo esc_attr($setting['title']); ?>" name="jp_customer_form_fields[field<?php echo esc_attr($count) ?>][title]" type="hidden">
														<input value="0" name="jp_customer_form_fields[field<?php echo esc_attr($count); ?>][isHide]" type="hidden">
														<div class="field-visiblity-checkbox">
															<input <?php echo esc_attr($disable_name) ?> type="checkbox" name="jp_customer_form_fields[field<?php echo esc_attr($count); ?>][isHide]" value="1"<?php echo checked( 1, $setting['isHide'], false ) ?> />
															<span class="checkbox-style"></span>
														</div>
													</div>
													<span class="field-name">Residential</span>
												</div>
												<!-- Commercial Type -->
												<div class="multi-field-item">
													<div class="field-visibility field-hide-setting">
														<input value="<?php echo esc_attr($setting['isCommercial']['name']); ?>" name="jp_customer_form_fields[field<?php echo esc_attr($count); ?>][isCommercial][name]" type="hidden">
														<input value="<?php echo esc_attr($setting['isCommercial']['title']); ?>" name="jp_customer_form_fields[field<?php echo esc_attr($count); ?>][isCommercial][title]" type="hidden">
														<input value="0" name="jp_customer_form_fields[field<?php echo esc_attr($count); ?>][isCommercial][isHide]" type="hidden">
														<div class="field-visiblity-checkbox"><input <?php echo esc_attr($disable_company); ?> type="checkbox"  name="jp_customer_form_fields[field<?php echo esc_attr($count); ?>][isCommercial][isHide]" value="1"<?php echo checked( 1, $setting['isCommercial']['isHide'], false ) ?> /><span class="checkbox-style"></span></div>
													</div>
													<span class="field-name">Commercial</span>
												</div>
											</div>
											
										</div>
									</div>
									<!-- Residential Type -->
									<div class="field-required-setting <?php echo esc_attr($disable_name ? 'hide-required-field' : ''); ?>" style="margin-top: 18px;">
										<input value="0" name="jp_customer_form_fields[field<?php echo esc_attr($count); ?>][isRequired]" type="hidden">
										<div class="field-visiblity-checkbox"><input type="checkbox" <?php echo ($setting['isHide'] == 1) ? 'disabled' : '' ?> name="jp_customer_form_fields[field<?php echo esc_attr($count) ?>][isRequired]" value="1"<?php echo checked( 1, $setting['isRequired'], false ) ?> /><span class="checkbox-style"></span></div>
										<span>Is Required</span>
									</div>
									<!-- Commercial Type -->
									<div class="field-required-setting <?php echo esc_attr($disable_company ? 'hide-required-field' : ''); ?>" style="margin-top: 18px;">
										<input value="0" name="jp_customer_form_fields[field<?php echo esc_attr($count) ?>][isCommercial][isRequired]" type="hidden">
										<div class="field-visiblity-checkbox"><input type="checkbox" <?php echo ($setting['isCommercial']['isHide'] == 1) ? 'disabled' : '' ?> name="jp_customer_form_fields[field<?php echo esc_attr($count) ?>][isCommercial][isRequired]" value="1"<?php echo checked( 1, $setting['isCommercial']['isRequired'], false ) ?> /><span class="checkbox-style"></span></div>
										<span>Is Required</span>
									</div>
																	
								</div>
								<?php break;

							default: 
								$disable = in_array($setting['name'], $diabledFields) ? 'disabled' : '';
							?>
								<div class="ui-state-default">
									<div class="sortable-field-item">
										<span class="drag-icon">
											<svg viewBox="0 0 32 32" role="presentation"><path fill-rule="evenodd" clip-rule="evenodd" d="M18 8a2 2 0 104 0 2 2 0 00-4 0zm2 10a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-8 0a2 2 0 110-4 2 2 0 010 4zm-2-10a2 2 0 104 0 2 2 0 00-4 0zm2-6a2 2 0 110-4 2 2 0 010 4z" fill="currentColor"/></svg>
										</span>
										<span class="field-name"><?php echo esc_html($setting['title']) ?></span>
										<div class="field-visibility field-hide-setting">
											<input value="<?php echo esc_attr($setting['name']) ?>" name="jp_customer_form_fields[field<?php echo esc_attr($count) ?>][name]" type="hidden">
											<input value="<?php echo esc_attr($setting['title']) ?>" name="jp_customer_form_fields[field<?php echo esc_attr($count) ?>][title]" type="hidden">
											<input value="0" name="jp_customer_form_fields[field<?php echo esc_attr($count) ?>][isHide]" type="hidden">
											<div class="field-visiblity-checkbox"><input <?php echo esc_attr($disable) ?> type="checkbox" name="jp_customer_form_fields[field<?php echo esc_attr($count) ?>][isHide]" value="1"<?php echo checked( 1, $setting['isHide'], false ) ?> /><span class="checkbox-style"></span></div>
										</div>
									</div>
									<div class="field-required-setting <?php echo esc_attr($disable ? 'hide-required-field' : '') ?>">
										<input value="0" name="jp_customer_form_fields[field<?php echo esc_attr($count) ?>][isRequired]" type="hidden">
										<div class="field-visiblity-checkbox"><input <?php echo ($setting['isHide'] == 1) ? 'disabled' : '' ?> type="checkbox" name="jp_customer_form_fields[field<?php echo esc_attr($count) ?>][isRequired]" value="1"<?php echo checked( 1, $setting['isRequired'], false ) ?> /><span class="checkbox-style"></span></div>
										<span>Is Required </span>
									</div>
								</div>
							<?php } ?>
						<?php $count++; 
					} ?>
			</div>
			<div class="theme-setting">
				<h2>Form Theme</h2>
				<p class="settings-desc">Select the following option if you want to use your site's theme on the form.</p>
				<div class="sortable-field-item use-theme-option">
					<span class="field-name">Use custom theme</span>
					<div class="field-visibility">
						<div class="field-visiblity-checkbox">
							<input type="checkbox" name="jp_use_custom_theme" value="1" <?php echo checked( 1, $options, false ) ?>  />
							<span class="checkbox-style"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="form-settings-submit">
				<?php 
				submit_button();
				?>
				<span class="settings-loader">
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/img/loader.svg">
				</span>
			</div>
		</div>
	</form>
</div>