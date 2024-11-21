<?php
////////////////////////////////////////////////////////
// おすすめ記事ウィジェットアイテム
////////////////////////////////////////////////////////

if ( ! class_exists( 'Fit_Recommend_Post_Widget' ) ) {
	class Fit_Recommend_Post_Widget extends WP_Widget {
		function __construct() {
			$name           = '[THE]おすすめ記事';
			$widget_options = array( 'description' => '任意のおすすめの記事を表示' );
			parent::__construct( false, $name, $widget_options );
		}

		// 設定フォームを出力するメソッド
		function form( $instance ) {
			$title          = ( isset( $instance['title'] ) ) ? esc_attr( $instance['title'] ) : '';
			$post_01        = ( isset( $instance['post_01'] ) ) ? esc_attr( $instance['post_01'] ) : '';
			$post_02        = ( isset( $instance['post_02'] ) ) ? esc_attr( $instance['post_02'] ) : '';
			$post_03        = ( isset( $instance['post_03'] ) ) ? esc_attr( $instance['post_03'] ) : '';
			$post_04        = ( isset( $instance['post_04'] ) ) ? esc_attr( $instance['post_04'] ) : '';
			$post_05        = ( isset( $instance['post_05'] ) ) ? esc_attr( $instance['post_05'] ) : '';
			$time           = ( isset( $instance['time'] ) && ! empty( $instance['time'] ) ) ? 'checked' : '';
			$update         = ( isset( $instance['update'] ) && ! empty( $instance['update'] ) ) ? 'checked' : '';
			$view           = ( isset( $instance['view'] ) && ! empty( $instance['view'] ) ) ? 'checked' : '';
			$layout         = ( isset( $instance['layout'] ) && ! empty( $instance['layout'] ) ) ? 'checked' : '';
			$category       = ( isset( $instance['category'] ) && ! empty( $instance['category'] ) ) ? 'checked' : '';
			$aspect         = ( isset( $instance['aspect'] ) ) ? $instance['aspect'] : '';
			$aspect_default = ( '' === $aspect ) ? 'selected' : '';
			$aspect_43      = ( 'eyecatch-43' === $aspect ) ? 'selected' : '';
			$aspect_11      = ( 'eyecatch-11' === $aspect ) ? 'selected' : '';
			$aspect_none    = ( 'none' === $aspect ) ? 'selected' : '';
			$word           = ( isset( $instance['word'] ) ) ? esc_attr( $instance['word'] ) : '';
			?>

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">タイトル:</label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'post_01' ); ?>">投稿ID 01:</label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'post_01' ); ?>" name="<?php echo $this->get_field_name( 'post_01' ); ?>" value="<?php echo $post_01; ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'post_02' ); ?>">投稿ID 02:</label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'post_02' ); ?>" name="<?php echo $this->get_field_name( 'post_02' ); ?>" value="<?php echo $post_02; ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'post_03' ); ?>">投稿ID 03:</label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'post_03' ); ?>" name="<?php echo $this->get_field_name( 'post_03' ); ?>" value="<?php echo $post_03; ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'post_04' ); ?>">投稿ID 04:</label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'post_04' ); ?>" name="<?php echo $this->get_field_name( 'post_04' ); ?>" value="<?php echo $post_04; ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'post_05' ); ?>">投稿ID 05:</label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'post_05' ); ?>" name="<?php echo $this->get_field_name( 'post_05' ); ?>" value="<?php echo $post_05; ?>">
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php echo $time; ?> id="<?php echo $this->get_field_id( 'time' ); ?>" name="<?php echo $this->get_field_name( 'time' ); ?>">
				<label for="<?php echo $this->get_field_id( 'time' ); ?>">投稿日を表示しますか ?</label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php echo $update; ?> id="<?php echo $this->get_field_id( 'update' ); ?>" name="<?php echo $this->get_field_name( 'update' ); ?>">
				<label for="<?php echo $this->get_field_id( 'update' ); ?>">更新日を表示しますか ?</label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php echo $view; ?> id="<?php echo $this->get_field_id( 'view' ); ?>" name="<?php echo $this->get_field_name( 'view' ); ?>">
				<label for="<?php echo $this->get_field_id( 'view' ); ?>">閲覧数を表示しますか ?</label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php echo $layout; ?> id="<?php echo $this->get_field_id( 'layout' ); ?>" name="<?php echo $this->get_field_name( 'layout' ); ?>">
				<label for="<?php echo $this->get_field_id( 'layout' ); ?>">ノーマルレイアウト(左画像)に変更しますか ?</label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php echo $category; ?> id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>">
				<label for="<?php echo $this->get_field_id( 'category' ); ?>">アイキャッチ上のカテゴリを非表示にしますか ?</label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_name( 'aspect' ); ?>">画像アスペクト比:</label>
				<select class="widefat" id="<?php echo $this->get_field_name( 'aspect' ); ?>" name="<?php echo $this->get_field_name( 'aspect' ); ?>">
					<option value="" <?php echo $aspect_default; ?>>16：9(default)</option>
					<option value="eyecatch-43" <?php echo $aspect_43; ?>>4：3</option>
					<option value="eyecatch-11" <?php echo $aspect_11; ?>>1：1</option>
					<option value="none" <?php echo $aspect_none; ?>>0：0(非表示)</option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'word' ); ?>">本文抜粋文字数(0～150　※ 0の場合は本文は非表示となります)：</label>
				<input type="number" min="0" max="150" class="widefat" id="<?php echo $this->get_field_id( 'word' ); ?>" name="<?php echo $this->get_field_name( 'word' ); ?>" value="<?php echo $word; ?>">
			</p>

			<?php
		}

		// 設定を表示するメソッド
		function widget( $args, $instance ) {
			$before_widget = ( isset( $args['before_widget'] ) ) ? $args['before_widget'] : '';
			$after_widget  = ( isset( $args['after_widget'] ) ) ? $args['after_widget'] : '';
			$before_title  = ( isset( $args['before_title'] ) ) ? $args['before_title'] : '';
			$after_title   = ( isset( $args['after_title'] ) ) ? $args['after_title'] : '';
			$title         = ( isset( $instance['title'] ) ) ? $instance['title'] : '';
			$post_01       = ( isset( $instance['post_01'] ) ) ? $instance['post_01'] : '';
			$post_02       = ( isset( $instance['post_02'] ) ) ? $instance['post_02'] : '';
			$post_03       = ( isset( $instance['post_03'] ) ) ? $instance['post_03'] : '';
			$post_04       = ( isset( $instance['post_04'] ) ) ? $instance['post_04'] : '';
			$post_05       = ( isset( $instance['post_05'] ) ) ? $instance['post_05'] : '';
			$time          = ( isset( $instance['time'] ) ) ? $instance['time'] : '';
			$update        = ( isset( $instance['update'] ) ) ? $instance['update'] : '';
			$view          = ( isset( $instance['view'] ) ) ? $instance['view'] : '';
			$layout        = ( isset( $instance['layout'] ) ) ? $instance['layout'] : '';
			$category      = ( isset( $instance['category'] ) ) ? $instance['category'] : '';
			$aspect        = ( isset( $instance['aspect'] ) ) ? $instance['aspect'] : '';
			$word          = ( isset( $instance['word'] ) ) ? $instance['word'] : '';

			echo $before_widget;

			if ( ! empty( $title ) ) {
				$title = apply_filters( 'widget_title', $title );
			}
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}

			$ids = array();
			if ( is_numeric( $post_01 ) ) {
				$ids[] = $post_01;
			}
			if ( is_numeric( $post_02 ) ) {
				$ids[] = $post_02;
			}
			if ( is_numeric( $post_03 ) ) {
				$ids[] = $post_03;
			}
			if ( is_numeric( $post_04 ) ) {
				$ids[] = $post_04;
			}
			if ( is_numeric( $post_05 ) ) {
				$ids[] = $post_05;
			}

			$query = null;
			if ( count( $ids ) > 0 ) {
				$args  = array(
					'post__in'            => $ids,
					'orderby'             => 'post__in',
					'ignore_sticky_posts' => '1',
					'posts_per_page'      => -1,
				);
				$query = new WP_Query( $args );
			}
			if ( $query !== null && $query->have_posts() ) {
				?>

				<ol class="widgetArchive">
					<?php
					while ( $query->have_posts() ) {
						$query->the_post();
						?>
						<li class="widgetArchive__item <?php echo ( $layout ) ? 'widgetArchive__item-normal' : ''; ?>">

							<?php if ( $aspect != 'none' ) : ?>
								<div class="eyecatch <?php echo ( $aspect ) ? esc_attr( $aspect ) : ''; ?>">
									<?php
									if ( ! $category ) {
										$cats = get_the_category();
										if ( ! empty( $cats[0] ) ) {
											require_once locate_template( 'inc/parts/display_category.php' );
											$display_category = null;
											$display_category = new FitDisplayTheCategory( $cats, 'category' );

											$most_btm_names = array();
											$most_btm_names = $display_category->get_deisplay_the_category();

											$term_id  = get_cat_ID( $most_btm_names[0] );
											$cat_link = get_category_link( $term_id );
											$cat_name = $most_btm_names[0];
											?>
											<span class="eyecatch__cat cc-bg<?php echo $term_id; ?>"><a 
												href="<? echo $cat_link; ?>"><?php echo $cat_name; ?></a></span>
											<?php
										}
									}
									?>
									<a class="eyecatch__link <?php echo ( get_option( 'fit_bsEyecatch_hover', 'off' ) != 'off' ) ? 'eyecatch__link-' . get_option( 'fit_bsEyecatch_hover' ) : ''; ?>"
										href="<?php the_permalink(); ?>">
										<?php
										if ( has_post_thumbnail() ) {
											$size = get_option( 'fit_bsEyecatch_widgetSize', 'icatch768' );
											the_post_thumbnail( $size );
										} elseif ( get_fit_noimg() ) {
											echo '<img ' . fit_correct_src() . '="' . get_fit_noimg() . '" alt="NO IMAGE" ' . fit_dummy_src() . '>';
										} else {
											echo '<img ' . fit_correct_src() . '="' . get_template_directory_uri() . '/img/img_no_768.gif" alt="NO IMAGE" ' . fit_dummy_src() . '>';
										}
										?>
									</a>
								</div>
							<?php endif; ?>

							<div class="widgetArchive__contents <?php echo ( $aspect === 'none' ) ? 'widgetArchive__contents-none' : ''; ?>">

								<?php
								if ( $aspect === 'none' ) {
									$cats = get_the_category();
									if ( ! empty( $cat[0] ) ) {
										$display_category = null;
										$display_category = new FitDisplayTheCategory( $cats, 'category' );

										// 最下層レベルのカテゴリー名リストを取得
										$most_btm_names = array();
										$most_btm_names = $display_category->get_deisplay_the_category();

										// リストの一番最初のカテゴリーを表示
										$term_id  = get_cat_ID( $most_btm_names[0] );
										$cat_link = get_category_link( $term_id );
										$cat_name = $most_btm_names[0];

										echo '<div class="the__category cc-bg' . $term_id . '">';
										echo '<a href="' . $cat_link . '">' . $cat_name . '</a>';
										echo '</div>';

										?>

										<div class="the__category cc-bg<?php echo $term_id; ?>">
											<a href="<?php echo $cat_link; ?>"><?php echo $cat_name; ?></a>
										</div>
										<?php
									}
								}
								?>

								<?php
								if ( $time || $update || $view ) :
									?>
									<ul class="dateList">

										<?php if ( $time ) : ?>
											<li class="dateList__item icon-clock"><?php the_time( get_option( 'date_format' ) ); ?></li>
										<?php endif; ?>

										<?php
										if (
											( $update ) // 更新日を表示する
											&&
											(
												( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) // 投稿日と更新日が違う
												||
												( ! $time ) // 投稿日を表示しない
											)
										) :
											?>
											<li class="dateList__item icon-update"><?php the_modified_date( get_option( 'date_format' ) ); ?></li>
										<?php endif; ?>

										<?php if ( $view ) : ?>
											<li class="dateList__item icon-eye"><?php echo get_post_meta( get_the_ID(), 'post_views', true ) . get_option( 'fit_bsRank_unit', 'view' ); ?></li>
										<?php endif; ?>
									</ul>
								<?php endif; ?>

								<h3 class="heading heading-tertiary">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>

								<?php if ( $word ) : ?>
									<p class="phrase phrase-tertiary"><?php echo mb_substr( get_the_excerpt(), 0, $word ) . '[…]'; ?></p>
								<?php endif; ?>

							</div>

						</li>
						<?php
					}
					wp_reset_postdata();
					?>
				</ol>

				<?php
			}

			echo $after_widget;
		}

		// 設定を保存するメソッド
		function update( $new_instance, $old_instance ) {
			$instance             = $old_instance;
			$instance['title']    = sanitize_text_field( $new_instance['title'] );
			$instance['post_01']  = ( is_numeric( $new_instance['post_01'] ) ) ? $new_instance['post_01'] : '';
			$instance['post_02']  = ( is_numeric( $new_instance['post_02'] ) ) ? $new_instance['post_02'] : '';
			$instance['post_03']  = ( is_numeric( $new_instance['post_03'] ) ) ? $new_instance['post_03'] : '';
			$instance['post_04']  = ( is_numeric( $new_instance['post_04'] ) ) ? $new_instance['post_04'] : '';
			$instance['post_05']  = ( is_numeric( $new_instance['post_05'] ) ) ? $new_instance['post_05'] : '';
			$instance['time']     = ( isset( $new_instance['time'] ) ) ? $new_instance['time'] : '';
			$instance['update']   = ( isset( $new_instance['update'] ) ) ? $new_instance['update'] : '';
			$instance['view']     = ( isset( $new_instance['view'] ) ) ? $new_instance['view'] : '';
			$instance['layout']   = ( isset( $new_instance['layout'] ) ) ? $new_instance['layout'] : '';
			$instance['category'] = ( isset( $new_instance['category'] ) ) ? $new_instance['category'] : '';
			$instance['aspect']   = ( isset( $new_instance['aspect'] ) ) ? $new_instance['aspect'] : '';
			$word                 = ( is_numeric( $new_instance['word'] ) ) ? intval( $new_instance['word'] ) : 0;
			if ( $word > 150 ) {
				$word = 150;
			}
			if ( $word < 0 ) {
				$word = 0;
			}
			$instance['word'] = $word;

			return $instance;
		}
	}
	function fit_register_recommend_post_widget() {
		register_widget( 'Fit_Recommend_Post_Widget' );
	}
	add_action( 'widgets_init', 'fit_register_recommend_post_widget' );
}
