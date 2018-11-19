<?php

namespace Drupal\tickets_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;



class TicketsAPIController extends ControllerBase {

	public function my_tickets() {
		$output = '';
		return array(
			'#markup' => render( $output ),
		);
	}

	public function get_example( Request $request ) {
		$response['data'] = 'Return JSON GET data!';
		$response['method'] = 'GET';
		return new JsonResponse( $response );
	}

	public function put_example( Request $request ) {
		$response['data'] = 'Return PUT data';
		$response['method'] = 'PUT';
		return new JsonResponse( $response );
	}

	public function post_example( Request $request ) {
		if ( 0 === strpos( $request->headers->get( 'Content-Type' ), 'application/json' ) ) {
			$data = json_decode( $request->getContent(), TRUE );
			$request->request->replace( is_array( $data ) ? $data : [] );
		}
		$response['data'] = 'Return POST JSON data';
		$response['method'] = 'POST';
		return new JsonResponse( $response );
	}

	public function delete_example( Request $request ) {
		$response['data'] = 'Return Delete method';
		$response['method'] = 'DELETE';
		return new JsonResponse( $response );
	}

}