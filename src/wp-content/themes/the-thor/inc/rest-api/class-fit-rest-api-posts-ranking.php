<?php
/**
 * 記事ランキング取得REST-API
 */

if ( ! class_exists( 'Fit_REST_API_Posts_Ranking' ) ) {
	/**
	 * 記事のランキングを取得するREST-APIクラス
	 */
	class Fit_REST_API_Posts_Ranking extends Fit_REST_API_Base {
		/**
		 * コンストラクタ
		 */
		public function __construct() {
			$this->namespace = parent::FIT_REST_NAMESPACE;
			$this->rest_base = 'posts-ranking';
		}

		/**
		 * エンドポイントを登録する
		 */
		public function register_routes() {
			register_rest_route(
				$this->namespace,
				'/' . $this->rest_base,
				array(
					'methods'  => WP_REST_Server::READABLE,
					'callback' => array( $this, 'get_items' ),
					'args'     => $this->get_collection_params(),
				)
			);
		}

		/**
		 * リクエストパラメーターの定義
		 */
		public function get_collection_params() {
			return array(
				'num'    => array(
					'type'              => 'integer',
					'default'           => 5,
					'sanitize_callback' => 'absint',
				),
				'period' => array(
					'type'              => 'string',
					'default'           => 'a',
					'sanitize_callback' => 'sanitize_text_field',
				),
			);
		}

		/**
		 * レスポンスデータの取得
		 *
		 * @param WP_REST_Request $request リクエスト情報
		 * @return WP_REST_Response レスポンス情報
		 */
		public function get_items( $request ) {
			$num    = ( isset( $request['num'] ) ) ? $request['num'] : '5';
			$cat    = ( isset( $request['cat'] ) ) ? $request['cat'] : '';
			$tag    = ( isset( $request['tag'] ) ) ? $request['tag'] : '';
			$writer = ( isset( $request['writer'] ) ) ? $request['writer'] : '';
			$period = ( isset( $request['period'] ) ) ? $request['period'] : 'a';
			$amp    = ( isset( $request['amp'] ) ) ? $request['amp'] : 'false';
			$args   = array();

			// 表示件数
			if ( ! is_numeric( $num ) || $num <= 0 ) {
				$num = 5;
			}
			$args['numberposts'] = intval( $num );

			// カテゴリー
			if ( $cat ) {
				$cat_in     = array();
				$cat_not_in = array();
				$categories = explode( ',', strval( $cat ) );
				foreach ( $categories as $category ) {
					if ( is_numeric( $category ) ) {
						if ( $category > 0 ) {
							$cat_in[] = $category;
						} elseif ( $category < 0 ) {
							$cat_not_in[] = abs( $category );
						}
					}
				}
				if ( count( $cat_in ) > 0 ) {
					$args['category__in'] = $cat_in;
				}
				if ( count( $cat_not_in ) > 0 ) {
					$args['category__not_in'] = $cat_not_in;
				}
			}

			// タグ
			if ( $tag ) {
				$tag_in     = array();
				$tag_not_in = array();
				$tags       = explode( ',', strval( $tag ) );
				foreach ( $tags as $tag ) {
					if ( is_numeric( $tag ) ) {
						if ( $tag > 0 ) {
							$tag_in[] = $tag;
						} elseif ( $tag < 0 ) {
							$tag_not_in[] = abs( $tag );
						}
					}
				}
				if ( count( $tag_in ) > 0 ) {
					$args['tag__in'] = $tag_in;
				}
				if ( count( $tag_not_in ) > 0 ) {
					$args['tag__not_in'] = $tag_not_in;
				}
			}

			// 投稿者
			if ( $writer ) {
				$writer_in     = array();
				$writer_not_in = array();
				$writers       = explode( ',', strval( $writer ) );
				foreach ( $writers as $writer ) {
					if ( is_numeric( $writer ) ) {
						if ( $writer > 0 ) {
							$writer_in[] = $writer;
						} elseif ( $writer < 0 ) {
							$writer_not_in[] = abs( $writer );
						}
					}
				}
				if ( count( $writer_in ) > 0 ) {
					$args['author__in'] = $writer_in;
				}
				if ( count( $writer_not_in ) > 0 ) {
					$args['author__not_in'] = $writer_not_in;
				}
			}

			// 期間
			$fit_rank = new Fit_Rank();
			switch ( $period ) {
				case 'm':
					$meta_key = $fit_rank->get_meta_key_id_by_period( Fit_Rank::PERIOD_MONTH );
					break;
				case 'w':
					$meta_key = $fit_rank->get_meta_key_id_by_period( Fit_Rank::PERIOD_WEEK );
					break;
				case 'd':
					$meta_key = $fit_rank->get_meta_key_id_by_period( Fit_Rank::PERIOD_DAY );
					break;
				default:
					$meta_key = $fit_rank->get_meta_key_id_by_period( Fit_Rank::PERIOD_ALL );
			}
			$args['meta_key'] = $meta_key;

			// PVの多い順に並べる
			$args['orderby'] = 'meta_value_num';
			$args['order']   = 'DESC';

			$query_result = get_posts( $args );
			$total_posts  = count( $query_result );

			// 対象記事が見つかったらレスポンス用のデータをセット
			if ( $query_result ) {
				global $post;
				foreach ( $query_result as $post ) {
					setup_postdata( $post );

					if ( has_post_thumbnail() ) {
						$thumbnail = get_the_post_thumbnail( null, array( 100, 100 ) );
					} elseif ( get_fit_noimg() ) {
						$thumbnail = '<img ' . fit_correct_src() . '="' . get_fit_noimg() . '" width="150" height="150" alt="NO IMAGE" ' . fit_dummy_src() . '>';
					} else {
						$thumbnail = '<img ' . fit_correct_src() . '="' . get_template_directory_uri() . '/img/img_no_thumbnail.gif" width="150" height="150" alt="NO IMAGE" ' . fit_dummy_src() . '>';
					}
					$post->thumbnail = $thumbnail;
					if ( boolval( $amp ) ) {
						$doc = new DOMDocument();
						$doc->loadHTML( $thumbnail );
						$elements = $doc->getElementsByTagName( 'img' );
						foreach ( $elements as $element ) {
							$post->thumbnail_src    = $element->getAttribute( 'src' );
							$post->thumbnail_alt    = $element->getAttribute( 'alt' );
							$post->thumbnail_width  = $element->getAttribute( 'width' );
							$post->thumbnail_height = $element->getAttribute( 'height' );
						}
						// $thumbnail = preg_replace( '/<img (.*?)>/i', '<amp-img layout="responsive" $1></amp-img>', $thumbnail );
					}

					$cats = get_the_category();
					require_once locate_template( 'inc/parts/display_category.php' );
					$display_category = null;
					$display_category = new FitDisplayTheCategory( $cats, 'category' );

					$most_btm_names = array();
					$most_btm_names = $display_category->get_deisplay_the_category();
					$term_id = get_cat_ID( $most_btm_names[0] );

					$post->term_id  = esc_attr( $term_id );
					$post->cat_link = esc_attr( get_category_link( $term_id ) );
					$post->cat_name = esc_html( $most_btm_names[0] );

					$post->link    = esc_attr( get_the_permalink() );
					$post->excerpt = get_the_excerpt();
				}
				wp_reset_postdata();
			}

			$response = rest_ensure_response( array( 'items' => $query_result ) );
			$response->header( 'X-WP-Total', (int) $total_posts );

			return $response;
		}
	}
 }