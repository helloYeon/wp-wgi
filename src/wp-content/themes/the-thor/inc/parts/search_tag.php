<?php
////////////////////////////////////////////////////////
// 複数タグ検索時の検索方法を指定する
////////////////////////////////////////////////////////
function fit_search_filter( $query ) {
  if ( !is_admin() && $query->is_main_query() ) {

    //$_GETにtagの値がある時に検索方法を『or・and』で切り替える
    if( array_key_exists( 'tag',$_GET ) ) {
      $method = 'tag_slug__in';
      if ( get_option('fit_bsSearch_tagMethod')){
        $method = get_option('fit_bsSearch_tagMethod');
	  }
	  $param_tags = is_array( $_GET['tag'] ) ? $_GET['tag'] : array($_GET['tag']);
      $query->set( $method, $param_tags );
  	}

  }
}
add_action( 'pre_get_posts','fit_search_filter' );
