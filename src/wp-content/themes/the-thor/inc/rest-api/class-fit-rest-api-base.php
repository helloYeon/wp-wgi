<?php
/**
 * REST-API共通で使うようなものを書く
 */

if ( ! class_exists( 'Fit_REST_API_Base' ) ) {
	/**
	 * REST-APIの親クラスとして使う
	 */
	class Fit_REST_API_Base extends WP_REST_Controller {
		const FIT_REST_NAMESPACE = 'wp/fit';
	}
}