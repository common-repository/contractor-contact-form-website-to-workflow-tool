<?php if(ine($_GET, 'error')): ?>
<div class="alert-msg alert-msg-danger invalid-msg"><?php echo esc_html($_GET['error']['message']);?></div>
<?php endif; ?>
<form method="get" action="<?php echo JP_AUTHORIZATION_URL;?>" class="jp-btn-connect">

<input type="hidden" name="domain" value="<?php echo esc_html($domain); ?>">
<input type="hidden" name="redirect_uri" value="<?php echo esc_html($redirect_url); ?>">
<input type="hidden" name="client_id" value="<?php echo PUBLIC_JP_CLIENT_ID ;?>"/>
<input type="hidden" name="client_secret" value="<?php echo PUBLIC_JP_CLIENT_SECRET; ?>"/>
<?php wp_nonce_field('jp_connect_form'); ?>
<input type="hidden" name="grant_type" value="password"/>
<input class="btn btn-blue" type="submit" value="Connect">
</form>