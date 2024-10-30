<script type="text/template" id="tpl-hello">
<div>
<span>Hello</span>
<span>{{NAME}}  </span>
</div>
</script>
<?php 
	if(!get_option('jp_customer_form_fields')) {
		$jp_customer_form_fields = get_form_default_settings();
	} else {
		$jp_customer_form_fields = get_option('jp_customer_form_fields');
	}
?>
<script type="text/template" class="additional-phone">
	<div class="jps-standard-fieldset jps-field-wrap jps-field-required jobprogress-customer-phone {{className}}">
		<label>Phone</label>
		<div class="jps-additional-field">
			<div class="jps-field-left">
				<div class="jps-phone-field">
					<div class="phone-num-type">
						<select required="" name="phones[{{ index }}][label]" class="phone-label form-control select-input" aria-required="true" tabindex="{{ index }}" aria-hidden="true">
							<option value="home">Home</option>
							<option value="cell">Cell</option>
							<option value="phone">Phone</option>
							<option value="office">Office</option>
							<option value="fax">Fax</option>
							<option value="other">Other</option>
						</select>
					</div>
					<input type="text" placeholder="(xxx) xxx-xxxx" name="phones[{{ index }}][number]" class="phones mask-select phone-num form-control" aria-required="true" required /> 
					<div class="jps-extension-field">
						<input type="text" placeholder="Extension" name="phones[{{ index }}][ext]" class="extension-field form-control" maxlength="12" aria-required="true">
					</div>
				</div>
			</div>
			<a class="jps-field-add add-item-repeat add-additional-phone" title="Add Additional Phone">
				<svg viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
			</a>
			<a title="Remove Additional Phone" class="jps-field-remove remove remove-additional-phone">
				<svg viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
			</a>
			<?php echo $this->get_error_wrapper('phones.{{ index }}.number'); ?>
		</div>
	</div>
</script>

<script type="text/template" class="additional-email">

	<?php
		$isRequiredAdditionalEmail = false;
		foreach($jp_customer_form_fields as $addfield) {
			switch($addfield['name']) {
				case 'customer_email' : 
					if(!$addfield['isHide'] && ine($addfield, 'isRequired')) {
						$isRequiredAdditionalEmail = true;
					}
			}
		}
	?>
	<div class="jps-standard-fieldset jps-field-wrap additional-emails {{className}} <?php echo(!$isRequiredAdditionalEmail ? 'jps-field-required' : ''); ?>">
		<label>Additional Email</label>
		<div class="jps-additional-field">
			<div class="jps-field-left">
				<input type="text" class="email form-control <?php echo(!$isRequiredAdditionalEmail ? 'jps-required-input' : ''); ?>" placeholder="Additional Email" name="additional_emails[{{index}}]" />
			</div>
			<a class="jps-field-add add-item-repeat start-additional-emails" title="Add Additional Email">
				<svg viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
			</a>
			<a title="Remove Additional Email" class="jps-field-remove remove additional-email-remove">
				<svg viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
			</a>
			<?php echo $this->get_error_wrapper('additional_emails.{{ index }}'); ?>
		</div>
	</div>
</script>
<script type="text/template" class="billing-address">
</script>
