<?php
/**
 * ウィジェット画面用Ajax
 */

 if ( ! function_exists( 'fit_widget_display_setting_get_post_list' ) ) {
	/**
	 * 表示/非表示設定用投稿リストの取得
	 */
	function fit_widget_display_setting_get_post_list() {
		$args = array(
			'numberposts' => -1,
			/*'orderby' => 'ID',
			'order' => 'ASC',*/
			'post_status' => 'publish,future,draft,pending,private',
		);
		$posts = get_posts( $args );

		$html  = '<ul>';
		$html .= '    <li class="all">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>すべての投稿</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		$html .= '    <li class="new">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>今後新しく追加される投稿</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		foreach ( $posts as $post ) {
			$title = ( $post->post_title ) ? $post->post_title : '(タイトルなし)';
			$html .= '<li class="post-' . $post->ID . '">';
			$html .= '    <div class="select-item">';
			$html .= '        <span class="list-name">' . $title . '</span>';
			$html .= '    </div>';
			$html .= '</li>';
		}
		$html .= '</ul>';
		echo $html;
		die();
	}
	add_action('wp_ajax_fit_widget_display_setting_get_post_list', 'fit_widget_display_setting_get_post_list');
}
if ( ! function_exists( 'fit_widget_display_setting_post_list_script' ) ) {
	function fit_widget_display_setting_post_list_script() {
		$url    = admin_url( 'admin-ajax.php' );
		$script = <<<EOT
<div id="fit-display-setting-post-list" style="display: none;"></div>
<script>
jQuery( function( $ ) {
	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action': 'fit_widget_display_setting_get_post_list',
		},
	} )
	.done( function( data ) {
		$( '#fit-display-setting-post-list' ).html( data );
	} );
} );
</script>
EOT;

		echo $script;
	}
}

if ( ! function_exists( 'fit_widget_display_setting_get_page_list' ) ) {
	/**
	 * 表示/非表示設定用固定ページリストの取得
	 */
	function fit_widget_display_setting_get_page_list() {
		$args = array(
			/*'sort_column' => 'ID',
			'sort_order' => 'ASC',*/
			'post_status' => 'publish,future,draft,pending,private',
		);
		$pages = get_pages( $args );

		$html  = '<ul>';
		$html .= '    <li class="all">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>すべての固定ページ</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		$html .= '    <li class="new">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>今後新しく追加される固定ページ</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		$first = true;
		foreach ( $pages as $page ) {
			$title = ( $page->post_title ) ? $page->post_title : '(タイトルなし)';
			if ( $first ) {
				$html .= '<li class="page-' . $page->ID . '">';
				$html .= '    <div class="select-item">';
				$html .= '        <span class="list-name">' . $title . '</span>';
				$html .= '    </div>';
				$first = false;
			} else {
				if ( ! isset( $parent_ids ) ) {
					$parent_ids = array();
				}
				if ( ! ($page->post_parent && ! in_array( $page->post_parent, $parent_ids ) ) ) {
					$html .= '</li>';
				}
				$parent_count = count( $parent_ids );
				while (
					$parent_count !== 0
					&& (
						in_array( $page->post_parent, $parent_ids )
						|| ! $page->post_parent
					)
					&& $parent_ids[$parent_count - 1] !== $page->post_parent
				) {
					// 子ページの終わりはulを閉じる
					if ( $parent_count === 1 ) {
						$parent_ids = array();
					} else {
						unset( $parent_ids[$parent_count - 1] );
					}
					$parent_count--;
					$html .= '</ul>';
					$html .= '</li>';
				}
				if ( $page->post_parent && ! in_array( $page->post_parent, $parent_ids ) ) {
					// 子ページの始まりはulを追加
					$parent_ids[] = $page->post_parent;
					$html .= '<ul>';
				}
				$html .= '<li class="page-' . $page->ID . '">';
				$html .= '    <div class="select-item">';
				$html .= '        <span class="list-name">' . $title . '</span>';
				$html .= '    </div>';
			}
		}
		$html .= '</li></ul>';
		echo $html;
		die();
	}
	add_action('wp_ajax_fit_widget_display_setting_get_page_list', 'fit_widget_display_setting_get_page_list');
}
if ( ! function_exists( 'fit_widget_display_setting_page_list_script' ) ) {
	function fit_widget_display_setting_page_list_script() {
		$url    = admin_url( 'admin-ajax.php' );
		$script = <<<EOT
<div id="fit-display-setting-page-list" style="display: none;"></div>
<script>
jQuery( function( $ ) {
	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action': 'fit_widget_display_setting_get_page_list',
		},
	} )
	.done( function( data ) {
		$( '#fit-display-setting-page-list' ).html( data );
	} );
} );
</script>
EOT;

		echo $script;
	}
}

if ( ! function_exists( 'fit_widget_display_setting_get_category_list' ) ) {
	/**
	 * 表示/非表示設定用カテゴリーリストの取得
	 */
	function fit_widget_display_setting_get_category_list() {
		$args = array(
			/*'orderby' => 'ID',
			'order' => 'ASC',*/
			'hide_empty' => 0,
			'title_li' => '',
			'echo' => 0,
		);

		$categories = wp_list_categories( $args );

		$categories = preg_replace( '/<a\s[\s\S]*?>/', '<div class="select-item"><span class="list-name">', $categories );
		$categories = preg_replace( '/<\/a>/', '</span></div>', $categories );
		$categories = preg_replace( '/cat-item\scat-item([\d]+)/', 'category-$1 post_category-$1', $categories );

		$html  = '<ul>';
		$html .= '    <li class="all">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>すべてのカテゴリー</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		$html .= '    <li class="new">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>今後新しく追加されるカテゴリー</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		$html .= $categories;
		$html .= '</ul>';
		echo $html;
		die();
	}
	add_action('wp_ajax_fit_widget_display_setting_get_category_list', 'fit_widget_display_setting_get_category_list');
}
if ( ! function_exists( 'fit_widget_display_setting_category_list_script' ) ) {
	function fit_widget_display_setting_category_list_script() {
		$url    = admin_url( 'admin-ajax.php' );
		$script = <<<EOT
<div id="fit-display-setting-category-list" style="display: none;"></div>
<script>
jQuery( function( $ ) {
	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action': 'fit_widget_display_setting_get_category_list',
		},
	} )
	.done( function( data ) {
		$( '#fit-display-setting-category-list' ).html( data );
	} );
} );
</script>
EOT;

		echo $script;
	}
}

if ( ! function_exists( 'fit_widget_display_setting_get_tag_list' ) ) {
	/**
	 * 表示/非表示設定用タグリストの取得
	 */
	function fit_widget_display_setting_get_tag_list() {
		$args = array(
			/*'orderby' => 'id',
			'order' => 'ASC',*/
			'hide_empty' => 0,
		);
		$tags = get_tags( $args );

		$html  = '<ul>';
		$html .= '    <li class="all">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>すべてのタグ</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		$html .= '    <li class="new">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>今後新しく追加されるタグ</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		foreach ( $tags as $tag ) {
			$html .= '<li class="tag-' . $tag->term_id . ' post_tag-' . $tag->term_id . '">';
			$html .= '    <div class="select-item">';
			$html .= '        <span class="list-name">' . $tag->name . '</span>';
			$html .= '    </div>';
			$html .= '</li>';
		}
		$html .= '</ul>';
		echo $html;
		die();
	}
	add_action('wp_ajax_fit_widget_display_setting_get_tag_list', 'fit_widget_display_setting_get_tag_list');
}
if ( ! function_exists( 'fit_widget_display_setting_tag_list_script' ) ) {
	function fit_widget_display_setting_tag_list_script() {
		$url    = admin_url( 'admin-ajax.php' );
		$script = <<<EOT
<div id="fit-display-setting-tag-list" style="display: none;"></div>
<script>
jQuery( function( $ ) {
	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action': 'fit_widget_display_setting_get_tag_list',
		},
	} )
	.done( function( data ) {
		$( '#fit-display-setting-tag-list' ).html( data );
	} );
} );
</script>
EOT;

		echo $script;
	}
}

if ( ! function_exists( 'fit_widget_display_setting_get_user_list' ) ) {
	/**
	 * 表示/非表示設定用著者リストの取得
	 */
	function fit_widget_display_setting_get_user_list() {
		$args = array(
			/*'orderby' => 'ID',
			'order' => 'ASC',*/
		);
		$users = get_users( $args );

		$html  = '<ul>';
		$html .= '    <li class="all">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>すべての著者</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		$html .= '    <li class="new">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>今後新しく追加される著者</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		foreach ( $users as $user ) {
			$username = get_the_author_meta( 'display_name', $user->ID );
			$html .= '<li class="user-' . $user->ID . ' post_user-' . $user->ID . '">';
			$html .= '    <div class="select-item">';
			$html .= '        <span class="list-name">' . $username . '</span>';
			$html .= '    </div>';
			$html .= '</li>';
		}
		$html .= '</ul>';
		echo $html;
		die();
	}
	add_action('wp_ajax_fit_widget_display_setting_get_user_list', 'fit_widget_display_setting_get_user_list');
}
if ( ! function_exists( 'fit_widget_display_setting_user_list_script' ) ) {
	function fit_widget_display_setting_user_list_script() {
		$url    = admin_url( 'admin-ajax.php' );
		$script = <<<EOT
<div id="fit-display-setting-user-list" style="display: none;"></div>
<script>
jQuery( function( $ ) {
	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action': 'fit_widget_display_setting_get_user_list',
		},
	} )
	.done( function( data ) {
		$( '#fit-display-setting-user-list' ).html( data );
	} );
} );
</script>
EOT;

		echo $script;
	}
}

if ( ! function_exists( 'fit_widget_rank_setting_get_category_list' ) ) {
	/**
	 * 人気記事ランキング対象記事設定用カテゴリーリストの取得
	 */
	function fit_widget_rank_setting_get_category_list() {
		$args = array(
			/*'orderby' => 'ID',
			'order' => 'ASC',*/
			'hide_empty' => 0,
			'title_li' => '',
			'echo' => 0,
		);

		$categories = wp_list_categories( $args );

		$categories = preg_replace( '/<a\s[\s\S]*?>/', '<div class="select-item"><span class="list-name">', $categories );
		$categories = preg_replace( '/<\/a>/', '</span></div>', $categories );
		$categories = preg_replace( '/cat-item\scat-item([\d]+)/', 'category-$1', $categories );

		$html  = '<ul>';
		$html .= '    <li class="all">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>すべてのカテゴリー</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		$html .= '    <li class="new">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>今後新しく追加されるカテゴリー</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		$html .= $categories;
		$html .= '</ul>';
		echo $html;
		die();
	}
	add_action('wp_ajax_fit_widget_rank_setting_get_category_list', 'fit_widget_rank_setting_get_category_list');
}
if ( ! function_exists( 'fit_widget_rank_setting_category_list_script' ) ) {
	function fit_widget_rank_setting_category_list_script() {
		$url    = admin_url( 'admin-ajax.php' );
		$script = <<<EOT
<div id="fit-rank-setting-category-list" style="display: none;"></div>
<script>
jQuery( function( $ ) {
	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action': 'fit_widget_rank_setting_get_category_list',
		},
	} )
	.done( function( data ) {
		$( '#fit-rank-setting-category-list' ).html( data );
	} );
} );
</script>
EOT;

		echo $script;
	}
}

if ( ! function_exists( 'fit_widget_rank_setting_get_tag_list' ) ) {
	/**
	 * 人気記事ランキング対象記事設定用タグリストの取得
	 */
	function fit_widget_rank_setting_get_tag_list() {
		$args = array(
			/*'orderby' => 'id',
			'order' => 'ASC',*/
			'hide_empty' => 0,
		);
		$tags = get_tags( $args );

		$html  = '<ul>';
		$html .= '    <li class="all">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>すべてのタグ</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		$html .= '    <li class="new">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>今後新しく追加されるタグ</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		foreach ( $tags as $tag ) {
			$html .= '<li class="tag-' . $tag->term_id . '">';
			$html .= '    <div class="select-item">';
			$html .= '        <span class="list-name">' . $tag->name . '</span>';
			$html .= '    </div>';
			$html .= '</li>';
		}
		$html .= '</ul>';
		echo $html;
		die();
	}
	add_action('wp_ajax_fit_widget_rank_setting_get_tag_list', 'fit_widget_rank_setting_get_tag_list');
}
if ( ! function_exists( 'fit_widget_rank_setting_tag_list_script' ) ) {
	function fit_widget_rank_setting_tag_list_script() {
		$url    = admin_url( 'admin-ajax.php' );
		$script = <<<EOT
<div id="fit-rank-setting-tag-list" style="display: none;"></div>
<script>
jQuery( function( $ ) {
	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action': 'fit_widget_rank_setting_get_tag_list',
		},
	} )
	.done( function( data ) {
		$( '#fit-rank-setting-tag-list' ).html( data );
	} );
} );
</script>
EOT;

		echo $script;
	}
}

if ( ! function_exists( 'fit_widget_rank_setting_get_user_list' ) ) {
	/**
	 * 人気記事ランキング対象記事設定用著者リストの取得
	 */
	function fit_widget_rank_setting_get_user_list() {
		$args = array(
			/*'orderby' => 'ID',
			'order' => 'ASC',*/
		);
		$users = get_users( $args );

		$html  = '<ul>';
		$html .= '    <li class="all">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>すべての著者</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		$html .= '    <li class="new">';
		$html .= '        <div class="select-item">';
		$html .= '            <span>今後新しく追加される著者</span>';
		$html .= '        </div>';
		$html .= '    </li>';
		foreach ( $users as $user ) {
			$username = get_the_author_meta( 'display_name', $user->ID );
			$html .= '<li class="user-' . $user->ID . '">';
			$html .= '    <div class="select-item">';
			$html .= '        <span class="list-name">' . $username . '</span>';
			$html .= '    </div>';
			$html .= '</li>';
		}
		$html .= '</ul>';
		echo $html;
		die();
	}
	add_action('wp_ajax_fit_widget_rank_setting_get_user_list', 'fit_widget_rank_setting_get_user_list');
}
if ( ! function_exists( 'fit_widget_rank_setting_user_list_script' ) ) {
	function fit_widget_rank_setting_user_list_script() {
		$url    = admin_url( 'admin-ajax.php' );
		$script = <<<EOT
<div id="fit-rank-setting-user-list" style="display: none;"></div>
<script>
jQuery( function( $ ) {
	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action': 'fit_widget_rank_setting_get_user_list',
		},
	} )
	.done( function( data ) {
		$( '#fit-rank-setting-user-list' ).html( data );
	} );
} );
</script>
EOT;

		echo $script;
	}
}
