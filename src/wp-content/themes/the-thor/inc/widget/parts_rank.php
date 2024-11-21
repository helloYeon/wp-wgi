<?php
////////////////////////////////////////////////////////
//ランキングウィジェットアイテム
////////////////////////////////////////////////////////
class fit_ranking_archive_class extends WP_Widget {
	function __construct() {
		$widget_option = array( 'description' => 'PV数の多い順に記事を表示' );
		parent::__construct( false, $name = '[THE]人気記事', $widget_option );
	}

	// 設定フォームを出力するメソッド
	function form( $instance ) {
		$title             = ( isset( $instance['title'] ) ) ? esc_attr( $instance['title'] ) : '';
		$period            = ( isset( $instance['period'] ) ) ? $instance['period'] : '';
		$number            = ( isset( $instance['number'] ) ) ? esc_attr( $instance['number'] ) : '';
		$time              = ( isset( $instance['time'] ) && ! empty( $instance['time'] ) ) ? 'checked' : '';
		$update            = ( isset( $instance['update'] ) && ! empty( $instance['update'] ) ) ? 'checked' : '';
		$view              = ( isset( $instance['view'] ) && ! empty( $instance['view'] ) ) ? 'checked' : '';
		$layout            = ( isset( $instance['layout'] ) && ! empty( $instance['layout'] ) ) ? 'checked' : '';
		$category          = ( isset( $instance['category'] ) && ! empty( $instance['category'] ) ) ? 'checked' : '';
		$aspect            = ( isset( $instance['aspect'] ) ) ? $instance['aspect'] : '';
		$aspect_default    = ( '' === $aspect ) ? 'selected' : '';
		$aspect_43         = ( 'eyecatch-43' === $aspect ) ? 'selected' : '';
		$aspect_11         = ( 'eyecatch-11' === $aspect ) ? 'selected' : '';
		$aspect_none       = ( 'none' === $aspect ) ? 'selected' : '';
		$word              = ( isset( $instance['word'] ) ) ? esc_attr( $instance['word'] ) : '';
		$rank_category_all = ( isset( $instance['rank_category_all'] ) ) ? $instance['rank_category_all'] : '';
		$rank_category_new = ( isset( $instance['rank_category_new'] ) ) ? $instance['rank_category_new'] : '';
		$rank_category_in  = ( isset( $instance['rank_category_in'] ) ) ? $instance['rank_category_in'] : '';
		$rank_tag_all      = ( isset( $instance['rank_tag_all'] ) ) ? $instance['rank_tag_all'] : '';
		$rank_tag_new      = ( isset( $instance['rank_tag_new'] ) ) ? $instance['rank_tag_new'] : '';
		$rank_tag_in       = ( isset( $instance['rank_tag_in'] ) ) ? $instance['rank_tag_in'] : '';
		$rank_user_all     = ( isset( $instance['rank_user_all'] ) ) ? $instance['rank_user_all'] : '';
		$rank_user_new     = ( isset( $instance['rank_user_new'] ) ) ? $instance['rank_user_new'] : '';
		$rank_user_in      = ( isset( $instance['rank_user_in'] ) ) ? $instance['rank_user_in'] : '';
		if ( empty( $instance ) ) {
			$rank_category_all = 'on';
			$rank_category_new = 'on';
			$rank_tag_all      = 'on';
			$rank_tag_new      = 'on';
			$rank_user_all     = 'on';
			$rank_user_new     = 'on';
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">タイトル:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'period' ); ?>">ランキング対象期間:</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'period' ); ?>" name="<?php echo $this->get_field_name( 'period' ); ?>">
				<option <?php echo ( Fit_Rank::PERIOD_ALL === $period ) ? 'selected' : ''; ?> value="<?php echo Fit_Rank::PERIOD_ALL; ?>">全期間(default)</option>
				<option <?php echo ( Fit_Rank::PERIOD_MONTH === $period ) ? 'selected' : ''; ?> value="<?php echo Fit_Rank::PERIOD_MONTH; ?>">月</option>
				<option <?php echo ( Fit_Rank::PERIOD_WEEK === $period ) ? 'selected' : ''; ?> value="<?php echo Fit_Rank::PERIOD_WEEK; ?>">週</option>
				<option <?php echo ( Fit_Rank::PERIOD_DAY === $period ) ? 'selected' : ''; ?> value="<?php echo Fit_Rank::PERIOD_DAY; ?>">日</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>">表示する投稿数(1～10):</label>
			<input class="tiny-text" type="number" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $number; ?>" step="1" min="1" max="10" size="3">
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php echo $time; ?> id="<?php echo $this->get_field_id( 'time' ); ?>" name="<?php echo $this->get_field_name( 'time' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'time' ); ?>">投稿日を表示しますか ?</label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php echo $update; ?> id="<?php echo $this->get_field_id( 'update' ); ?>" name="<?php echo $this->get_field_name( 'update' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'update' ); ?>">更新日を表示しますか ?</label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php echo $view; ?> id="<?php echo $this->get_field_id( 'view' ); ?>" name="<?php echo $this->get_field_name( 'view' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'view' ); ?>">閲覧数を表示しますか ?</label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php echo $layout; ?> id="<?php echo $this->get_field_id( 'layout' ); ?>" name="<?php echo $this->get_field_name( 'layout' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'layout' ); ?>">ノーマルレイアウト(左画像)に変更しますか ?</label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php echo $category; ?> id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" />
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
			<input type="number" max="150" class="widefat" id="<?php echo $this->get_field_id( 'word' ); ?>" name="<?php echo $this->get_field_name( 'word' ); ?>" value="<?php echo $word; ?>">
		</p>

		<!-- 展開ボタン -->
		<p>
			<a href="#TB_inline?width=600&height=550&inlineId=widget-rank-setting-<?php echo $this->id; ?>" class="thickbox widget-btn" onclick="FIT_WIDGET.RANK.open_modal( '<?php echo $this->id; ?>', '<?php echo $this->name; ?>', '<?php echo $title; ?>' );">対象記事の絞り込み</a>
		</p>
		<!-- /展開ボタン -->

		<!-- changeイベント発行用 -->
		<input type="hidden" id="rank-setting-flag-<?php echo $this->id; ?>" value="">

		<!-- モーダルウィンドウ -->
		<div id="widget-rank-setting-<?php echo $this->id; ?>" class="widget-rank-setting" style="display:none;">

			<!-- タブ -->
			<div class="rank-setting-tab">
				<div class="tab tab-category active">カテゴリー</div>
				<div class="tab tab-tag">タグ</div>
				<div class="tab tab-user">著者</div>
			</div>
			<!-- /タブ -->

			<!-- 絞り込み -->
			<div class="rank-setting-search">
				<label for="rank-setting-search-<?php echo $this->id; ?>">絞り込み：</label>
				<input type="text" id="rank-setting-search-<?php echo $this->id; ?>" value="">
			</div>
			<!-- /絞り込み -->

			<!-- カテゴリー -->
			<div class="rank-setting-area-category">
				<div class="area-title">
					<span class="title">カテゴリー一覧</span>
					<span class="description">選択したカテゴリーに属する記事がランキングの対象になります</span>
				</div>
				<div id="list-category-<?php echo $this->id; ?>" class="list list-category list-rank-setting">データ取得中</div>
				<input type="hidden" name="<?php echo $this->get_field_name( 'rank_category_all' ); ?>" id="<?php echo $this->get_field_id( 'rank_category_all' ); ?>" value="<?php echo $rank_category_all; ?>">
				<input type="hidden" name="<?php echo $this->get_field_name( 'rank_category_new' ); ?>" id="<?php echo $this->get_field_id( 'rank_category_new' ); ?>" value="<?php echo $rank_category_new; ?>">
				<input type="hidden" name="<?php echo $this->get_field_name( 'rank_category_in' ); ?>" id="<?php echo $this->get_field_id( 'rank_category_in' ); ?>" value="<?php echo $rank_category_in; ?>">
			</div>
			<!-- /カテゴリー -->

			<!-- タグ -->
			<div class="rank-setting-area-tag" style="display: none;">
				<div class="area-title">
					<span class="title">タグ一覧</span>
					<span class="description">選択したタグを持つ記事がランキングの対象になります</span>
				</div>
				<div id="list-tag-<?php echo $this->id; ?>" class="list list-tag list-rank-setting">データ取得中</div>
				<input type="hidden" name="<?php echo $this->get_field_name( 'rank_tag_all' ); ?>" id="<?php echo $this->get_field_id( 'rank_tag_all' ); ?>" value="<?php echo $rank_tag_all; ?>">
				<input type="hidden" name="<?php echo $this->get_field_name( 'rank_tag_new' ); ?>" id="<?php echo $this->get_field_id( 'rank_tag_new' ); ?>" value="<?php echo $rank_tag_new; ?>">
				<input type="hidden" name="<?php echo $this->get_field_name( 'rank_tag_in' ); ?>" id="<?php echo $this->get_field_id( 'rank_tag_in' ); ?>" value="<?php echo $rank_tag_in; ?>">
			</div>
			<!-- /タグ -->

			<!-- 著者 -->
			<div class="rank-setting-area-user" style="display: none;">
				<div class="area-title">
					<span class="title">著者一覧</span>
					<span class="description">選択した著者の記事がランキングの対象になります</span>
				</div>
				<div id="list-user-<?php echo $this->id; ?>" class="list list-user list-rank-setting">データ取得中</div>
				<input type="hidden" name="<?php echo $this->get_field_name( 'rank_user_all' ); ?>" id="<?php echo $this->get_field_id( 'rank_user_all' ); ?>" value="<?php echo $rank_user_all; ?>">
				<input type="hidden" name="<?php echo $this->get_field_name( 'rank_user_new' ); ?>" id="<?php echo $this->get_field_id( 'rank_user_new' ); ?>" value="<?php echo $rank_user_new; ?>">
				<input type="hidden" name="<?php echo $this->get_field_name( 'rank_user_in' ); ?>" id="<?php echo $this->get_field_id( 'rank_user_in' ); ?>" value="<?php echo $rank_user_in; ?>">
			</div>
			<!-- /著者 -->

			<!-- 閉じるボタン -->
			<?php
			$save_button_label = '保存';
			if ( is_customize_preview() ) {
				$save_button_label = '適用ボタンを押した後に公開';
			}
			?>
			<div class="rank-setting-clause">
				<a href="#" class="widget-btn">設定完了（<?php echo $save_button_label; ?>ボタンを押さないと設定は反映されません）</a>
			</div>
			<!-- /閉じるボタン -->

			<input type="hidden" id="rank-setting-set-<?php echo $this->id; ?>" value="false">
		</div>

		<?php
	}

	// 設定を表示するメソッド
	function widget( $args, $instance ) {
		$before_widget = ( isset( $args['before_widget'] ) ) ? $args['before_widget'] : '';
		$after_widget  = ( isset( $args['after_widget'] ) ) ? $args['after_widget'] : '';
		$before_title  = ( isset( $args['before_title'] ) ) ? $args['before_title'] : '';
		$after_title   = ( isset( $args['after_title'] ) ) ? $args['after_title'] : '';

		echo $before_widget;

		$title = ( isset( $instance['title'] ) ) ? $instance['title'] : '';
		if ( ! $title ) {
			$title = apply_filters( 'widget_title', $title );
		}
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		// フッターにランキング取得用のスクリプト追加
		$id              = fit_ranklist_id_hash();
		$get_rank_action = new Fit_Get_Rank_Action( $id, Fit_Get_Rank_Action::TYPE_RANK, array( 'instance' => $instance ) );
		$get_rank_action->add_wp_footer();

		echo '<div id="rankwidget-' . $id . '">';
		echo '    <ol class="widgetArchive">';
		echo '        <li class="widgetArchive__item widgetArchive__item-no widgetArchive__item-offline rank-offline">';
		echo '            <div class="widgetArchive__contents ">';
		echo '                <p class="phrase phrase-tertiary">オフラインのためランキングが表示できません</p>';
		echo '            </div>';
		echo '        </li>';
		echo '    </ol>';
		echo '</div>';

		echo $after_widget;
	}

	// 設定を保存するメソッド
	function update( $new_instance, $old_instance ) {
		$instance             = $old_instance;
		$instance['title']    = sanitize_text_field( $new_instance['title'] );
		$instance['period']   = ( isset( $new_instance['period'] ) ) ? $new_instance['period'] : '';
		$instance['time']     = ( isset( $new_instance['time'] ) ) ? $new_instance['time'] : '';
		$instance['update']   = ( isset( $new_instance['update'] ) ) ? $new_instance['update'] : '';
		$instance['view']     = ( isset( $new_instance['view'] ) ) ? $new_instance['view'] : '';
		$instance['layout']   = ( isset( $new_instance['layout'] ) ) ? $new_instance['layout'] : '';
		$instance['category'] = ( isset( $new_instance['category'] ) ) ? $new_instance['category'] : '';
		$instance['aspect']   = ( isset( $new_instance['aspect'] ) ) ? $new_instance['aspect'] : '';

		$number = ( is_numeric( $new_instance['number'] ) ) ? intval( $new_instance['number'] ) : 5;
		if ( $number > 10 ) {
			$number = 10;
		}
		if ( $number < 1 ) {
			$number = 1;
		}
		$instance['number'] = $number;

		$word = ( is_numeric( $new_instance['word'] ) ) ? intval( $new_instance['word'] ) : 0;
		if ( $word > 150 ) {
			$word = 150;
		}
		if ( $word < 0 ) {
			$word = 0;
		}
		$instance['word'] = $word;

		$instance['rank_category_all'] = ( isset( $new_instance['rank_category_all'] ) ) ? $new_instance['rank_category_all'] : '';
		$instance['rank_category_new'] = ( isset( $new_instance['rank_category_new'] ) ) ? $new_instance['rank_category_new'] : '';
		$instance['rank_category_in']  = ( isset( $new_instance['rank_category_in'] ) ) ? $new_instance['rank_category_in'] : '';
		$instance['rank_tag_all']      = ( isset( $new_instance['rank_tag_all'] ) ) ? $new_instance['rank_tag_all'] : '';
		$instance['rank_tag_new']      = ( isset( $new_instance['rank_tag_new'] ) ) ? $new_instance['rank_tag_new'] : '';
		$instance['rank_tag_in']       = ( isset( $new_instance['rank_tag_in'] ) ) ? $new_instance['rank_tag_in'] : '';
		$instance['rank_user_all']     = ( isset( $new_instance['rank_user_all'] ) ) ? $new_instance['rank_user_all'] : '';
		$instance['rank_user_new']     = ( isset( $new_instance['rank_user_new'] ) ) ? $new_instance['rank_user_new'] : '';
		$instance['rank_user_in']      = ( isset( $new_instance['rank_user_in'] ) ) ? $new_instance['rank_user_in'] : '';

		return $instance;
	}

}

function fit_register_ranking_archive_widget() {
	// ウィジェット画面用のAjaxスクリプト
	add_action( 'admin_footer', 'fit_widget_rank_setting_category_list_script' );
	add_action( 'admin_footer', 'fit_widget_rank_setting_tag_list_script' );
	add_action( 'admin_footer', 'fit_widget_rank_setting_user_list_script' );

	// カスタマイザー用のAjaxスクリプト
	add_action( 'customize_controls_print_footer_scripts', 'fit_widget_rank_setting_category_list_script' );
	add_action( 'customize_controls_print_footer_scripts', 'fit_widget_rank_setting_tag_list_script' );
	add_action( 'customize_controls_print_footer_scripts', 'fit_widget_rank_setting_user_list_script' );

	register_widget( 'fit_ranking_archive_class' );
}
add_action( 'widgets_init', 'fit_register_ranking_archive_widget' );

if ( ! function_exists( 'fit_add_new_category_display_off' ) ) {
	/**
	 * カテゴリーが登録された場合にランキングの対象とする処理
	 *
	 * @param int $category_id カテゴリーのID
	 */
	function fit_add_new_category_rank_in( $category_id ) {
		$sidebars_widgets = wp_get_sidebars_widgets();
		foreach ( $sidebars_widgets as $widget_area => $widgets ) {
			if ( empty( $widgets ) ) {
				// ウィジェットが設定されていないエリアはスキップする
				continue;
			}

			foreach ( $widgets as $num => $widget_id ) {
				fit_update_new_rank_in_option( $widget_id, 'category', $category_id );
			}
		}
	}
	add_action( 'create_category', 'fit_add_new_category_rank_in' );
}

if ( ! function_exists( 'fit_add_new_tag_display_off' ) ) {
	/**
	 * タグが登録された場合にランキングの対象とする処理
	 *
	 * @param int $tag_id タグのID
	 */
	function fit_add_new_tag_rank_in( $tag_id ) {
		$sidebars_widgets = wp_get_sidebars_widgets();
		foreach ( $sidebars_widgets as $widget_area => $widgets ) {
			if ( empty( $widgets ) ) {
				// ウィジェットが設定されていないエリアはスキップする
				continue;
			}

			foreach ( $widgets as $num => $widget_id ) {
				fit_update_new_rank_in_option( $widget_id, 'tag', $tag_id );
			}
		}
	}
	add_action( 'create_post_tag', 'fit_add_new_tag_rank_in' );
}

if ( ! function_exists( 'fit_add_new_user_display_off' ) ) {
	/**
	 * ユーザーが登録された場合にランキングの対象とする処理
	 *
	 * @param int $user_id ユーザーのID
	 */
	function fit_add_new_user_rank_in( $user_id ) {
		$sidebars_widgets = wp_get_sidebars_widgets();
		foreach ( $sidebars_widgets as $widget_area => $widgets ) {
			if ( empty( $widgets ) ) {
				// ウィジェットが設定されていないエリアはスキップする
				continue;
			}

			foreach ( $widgets as $num => $widget_id ) {
				fit_update_new_rank_in_option( $widget_id, 'user', $user_id );
			}
		}
	}
	add_action( 'user_register', 'fit_add_new_user_rank_in' );
}

if ( ! function_exists( 'fit_update_new_rank_in_option' ) ) {
	/**
	 * 新しく追加されたらランキング対象にする設定となっている場合にランキング対象パラメーターにIDを追加する
	 */
	function fit_update_new_rank_in_option( $widget_id, $type, $id ) {
		$widget_info = fit_get_widget_info_by_id( $widget_id );
		// すべてランキング対象にする設定の取得
		$rank_all_switch = ( isset( $widget_info[ 'rank_' . $type . '_all' ] ) ) ? $widget_info[ 'rank_' . $type . '_all' ] : '';
		// 新しく追加されたらランキング対象にする設定の取得
		$rank_new_switch = ( isset( $widget_info[ 'rank_' . $type . '_new' ] ) ) ? $widget_info[ 'rank_' . $type . '_new' ] : '';

		if ( 'on' !== $rank_all_switch && 'on' === $rank_new_switch ) {
			$rank_in_id = ( isset( $widget_info[ 'rank_' . $type . '_in' ] ) ) ? $widget_info[ 'rank_' . $type . '_in' ] : '';
			if ( '' === $rank_in_id ) {
				$rank_in_id .= $id;
			} else {
				// すでに設定値がある場合はカンマでつなぐ
				$rank_in_id .= ',' . $id;
			}
			$widget_info[ 'rank_' . $type . '_in' ] = $rank_in_id;
			fit_update_widget_info_by_id( $widget_id, $widget_info );
		}
	}
}
