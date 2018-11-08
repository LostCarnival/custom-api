<?php

namespace Drupal\tickets_api\Controller;

class TicketsAPIController {
	public function my_tickets() {
		$output = 'My tickets will be here';
		return array(
			'#markup' => render( $output ),
		);
	}
}