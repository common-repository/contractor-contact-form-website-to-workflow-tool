<?php 
if($order == 'asc') {
	$order = 'desc';
} else {
	$order ='asc';
}
?>
<div class="wrap">
	<h1>
		Customers
	</h1>
	<h2 class="screen-reader-text">Filter pages list</h2>
	<ul class="subsubsub">
		<li class="all">
			<a class="current" href="edit.php?post_type=page">All 
				<span class="count">(<?php echo esc_html($total); ?>)</span>
			</a>
		</li>
	</ul>
	<form method="get" id="posts-filter" action="<?php echo admin_url( 'admin.php') . "?page=jp_customer_page"; ?>">
		<div class="tablenav top">
			<div class="alignleft actions">
				<?php $args = array(
					'base'               =>  add_query_arg( 'page_num', '%#%' ),
					'format'             => '',
					'total'              => $num_of_pages,
					'current'            => $page_num,
					'show_all'           => true,
					'end_size'           => 2,
					'mid_size'           => 2,
					'prev_next'          => true,
					'prev_text'          => __('« Previous'),
					'next_text'          => __('Next »'),
					'type'               => 'plain',
					'add_args'           => false,
					'add_fragment'       => '',
					'before_page_number' => '',
					'after_page_number'  => ''
					); 
				 $page_links = paginate_links( $args ); 
				if ( $page_links ) {
					echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . wp_kses_post($page_links) . '</div></div>';
				}
				?>
			</div>

		<br class="clear">
		</div>
		<h2 class="screen-reader-text">Pages list</h2>
		<table class="wp-list-table widefat fixed striped pages">
			<thead>
				<tr>
					<th class="manage-column column-title column-primary sortable desc" id="title" scope="col">

						<a href="<?php echo esc_url(admin_url( 'admin.php'). "?order=$order&order_by=first_name&page=jp_customer_page"); ?>">
							<span>Full Name</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>

					<th class="manage-column column-email column-primary" id="email" scope="col">
						<a href="">
							<span>Email</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>

					<th class="manage-column column-address column-primary" id="address" scope="col">
						<a href="">
							<span>Address</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>

					<th class="manage-column column-trade" id="trade" scope="col">
						<a href="">
							<span>Trade</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>

					<th class="manage-column column-creation-date column-primary" id="creation-date" scope="col">
						<a href="<?php echo esc_url(admin_url( 'admin.php'). "?order=$order&order_by=created_at&page=jp_customer_page"); ?>">
							<span>Creation Date</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
				</tr>
			</thead>
			<tbody id="the-list">
				<?php if(empty($customers)): ?>
				<tr>
					<td colspan="5"><center><b>No Customer Found.</b></center></td>
				</tr>
				<?php endif; ?>
			<?php foreach ($customers as $key => $customer): ?>
			<tr class="iedit author-self level-0 post-2 type-page status-publish hentry" id="post-2">
				<td data-colname="Title" class="title column-title has-row-actions column-primary page-title">
					<strong>
						<a class="row-title">
							<?php echo esc_html($customer->first_name . ' '. $customer->last_name); ?>
						</a>
					</strong>
				</td>
				<td data-colname="Email" class="email column-email">
					<a><?php 
						$emails = array();
						$email = $customer->email;
						if(!empty($customer->additional_emails)) {
							$emails = json_decode($customer->additional_emails, true);
						}
						array_unshift($emails, $email);
						$emails = array_filter($emails);
						if(empty($emails)) {
							echo '--';
						}else {
							echo wp_kses_post(implode('<br>', $emails));
						}
					 ?></a>
				</td>
				
				<td data-colname="Address" class="address column-address">

					<a>	
						<?php  
						$address = array();
						$completeAddress = json_decode($customer->address, true);
						$addressArray = $completeAddress['address'];
						if(ine($addressArray, 'address')) {
							$address[] = $addressArray['address'];
						}
						if(ine($addressArray, 'address_line_1')) {
							$address[] = $addressArray['address_line_1'];
						}
						if(ine($addressArray, 'city')) {
							$address[] = $addressArray['city'];
						}
						if(ine($addressArray, 'state_id')) {
							$state = explode('_', $addressArray['state_id']);
							$address[] = $state[1];
						}
						if(ine($addressArray, 'zip')) {
							$address[] = $addressArray['zip'] . '<br>';
						}
						if(ine($addressArray, 'country_id')) {
							$country = explode('_', $addressArray['country_id']);
							$address[] = $country[1];
						}
						if(empty($address)) {
							echo '--';
						} else {
							echo wp_kses_post(implode(', ', $address));
						}
					?>
				</a>
			</td>
			<td data-colname="job-detail" class="column-job-detail">
				<a>
					<?php
						$job = json_decode($customer->job, true);
						$trades = array_values($job['trades']);
						$jpTrades = get_transient("jp_trades");
						$tradeName = array();

						if( !empty($jpTrades) && !empty($trades)) {
							foreach ($trades as $key => $value){
								$key = array_search($value, array_column($jpTrades, 'id'));
								$name = $jpTrades[$key]['name'];
								if((string)$value == 24) {
									 $name .= ' ('.$job['other_trade_type_description'].')';
								}
								$tradeName[] = $name;
							}
							echo wp_kses_post(implode(', ', $tradeName));
						} else {
							echo '--';
						}

					?>
				</a>
				</td>
				<td data-colname="Creation Time" class="date column-created-at">
					<abbr><?php echo esc_html(date("Y/m/d", strtotime($customer->created_at))); ?></abbr>
				</td>	
			</tr>
		<?php endforeach; ?>
</tbody>
<tfoot>
	<tr>
		<th class="manage-column column-title column-primary sortable desc" id="title" scope="col">
			<a href="<?php echo esc_url(admin_url( 'admin.php') . "?order=$order&order_by=first_name&page=jp_customer_page"); ?>">
				<span>Full Name</span>
				<span class="sorting-indicator"></span>
			</a>
		</th>

		<th class="manage-column column-email column-primary" id="email" scope="col">
			<a href="">
				<span>Email</span>
				<span class="sorting-indicator"></span>
			</a>
		</th>

		<th class="manage-column column-address column-primary" id="address" scope="col">
			<a href="">
				<span>Address</span>
				<span class="sorting-indicator"></span>
			</a>
		</th>

		<th class="manage-column column-trade" id="trade" scope="col">
			<a href="">
				<span>Trade</span>
				<span class="sorting-indicator"></span>
			</a>
		</th>

		<th class="manage-column column-creation-date column-primary" id="creation-date" scope="col">
			<a href="<?php echo esc_url(admin_url( 'admin.php'). "?order=$order&order_by=created_at&page=jp_customer_page"); ?>">
				<span>Creation Date</span>
				<span class="sorting-indicator"></span>
			</a>
		</th>
	</tr>
</tfoot>
</table>
</form>
<div id="ajax-response"></div>
<br class="clear">
</div>