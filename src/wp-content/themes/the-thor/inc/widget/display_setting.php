<?php
/**
 * ウィジェットの表示/非表示設定
 */

if ( ! function_exists( 'fit_widget_display_setting_control' ) ) {
	/**
	 * 各ウィジェットに表示/非表示設定のコントロール追加
	 */
	function fit_widget_display_setting_control( $widget, $return, $instance ) {
		$title                         = ( isset( $instance['title'] ) ) ? $instance['title'] : '';
		$display_off_post_all          = ( isset( $instance['display_off_post_all'] ) ) ? $instance['display_off_post_all'] : '';
		$display_off_post_new          = ( isset( $instance['display_off_post_new'] ) ) ? $instance['display_off_post_new'] : '';
		$display_off_post              = ( isset( $instance['display_off_post'] ) ) ? $instance['display_off_post'] : '';
		$display_off_post_category_all = ( isset( $instance['display_off_post_category_all'] ) ) ? $instance['display_off_post_category_all'] : '';
		$display_off_post_category_new = ( isset( $instance['display_off_post_category_new'] ) ) ? $instance['display_off_post_category_new'] : '';
		$display_off_post_category     = ( isset( $instance['display_off_post_category'] ) ) ? $instance['display_off_post_category'] : '';
		$display_off_post_tag_all      = ( isset( $instance['display_off_post_tag_all'] ) ) ? $instance['display_off_post_tag_all'] : '';
		$display_off_post_tag_new      = ( isset( $instance['display_off_post_tag_new'] ) ) ? $instance['display_off_post_tag_new'] : '';
		$display_off_post_tag          = ( isset( $instance['display_off_post_tag'] ) ) ? $instance['display_off_post_tag'] : '';
		$display_off_post_user_all     = ( isset( $instance['display_off_post_user_all'] ) ) ? $instance['display_off_post_user_all'] : '';
		$display_off_post_user_new     = ( isset( $instance['display_off_post_user_new'] ) ) ? $instance['display_off_post_user_new'] : '';
		$display_off_post_user         = ( isset( $instance['display_off_post_user'] ) ) ? $instance['display_off_post_user'] : '';
		$display_off_page_all          = ( isset( $instance['display_off_page_all'] ) ) ? $instance['display_off_page_all'] : '';
		$display_off_page_new          = ( isset( $instance['display_off_page_new'] ) ) ? $instance['display_off_page_new'] : '';
		$display_off_page              = ( isset( $instance['display_off_page'] ) ) ? $instance['display_off_page'] : '';
		$display_off_category_all      = ( isset( $instance['display_off_category_all'] ) ) ? $instance['display_off_category_all'] : '';
		$display_off_category_new      = ( isset( $instance['display_off_category_new'] ) ) ? $instance['display_off_category_new'] : '';
		$display_off_category          = ( isset( $instance['display_off_category'] ) ) ? $instance['display_off_category'] : '';
		$display_off_tag_all           = ( isset( $instance['display_off_tag_all'] ) ) ? $instance['display_off_tag_all'] : '';
		$display_off_tag_new           = ( isset( $instance['display_off_tag_new'] ) ) ? $instance['display_off_tag_new'] : '';
		$display_off_tag               = ( isset( $instance['display_off_tag'] ) ) ? $instance['display_off_tag'] : '';
		$display_off_user_all          = ( isset( $instance['display_off_user_all'] ) ) ? $instance['display_off_user_all'] : '';
		$display_off_user_new          = ( isset( $instance['display_off_user_new'] ) ) ? $instance['display_off_user_new'] : '';
		$display_off_user              = ( isset( $instance['display_off_user'] ) ) ? $instance['display_off_user'] : '';
		$display_off_archive           = ( isset( $instance['display_off_archive'] ) && ! empty( $instance['display_off_archive'] ) ) ? 'checked' : '';
		$display_off_search_result     = ( isset( $instance['display_off_search_result'] ) && ! empty( $instance['display_off_search_result'] ) ) ? 'checked' : '';
		$display_off_404               = ( isset( $instance['display_off_404'] ) && ! empty( $instance['display_off_404'] ) ) ? 'checked' : '';
		$display_off_pc                = ( isset( $instance['display_off_pc'] ) && ! empty( $instance['display_off_pc'] ) ) ? 'checked' : '';
		$display_off_sp                = ( isset( $instance['display_off_sp'] ) && ! empty( $instance['display_off_sp'] ) ) ? 'checked' : '';
		?>

		<!-- 展開ボタン -->
		<p>
			<a href="#TB_inline?width=600&height=550&inlineId=widget-display-setting-<?php echo $widget->id; ?>" class="thickbox widget-btn" onclick="FIT_WIDGET.DISPLAY_SETTING.open_modal( '<?php echo $widget->id; ?>', '<?php echo $widget->name; ?>', '<?php echo esc_attr( $title ); ?>' );">表示 / 非表示設定</a>
		</p>
		<!-- /展開ボタン -->

		<!-- changeイベント発行用 -->
		<input type="hidden" id="display-setting-flag-<?php echo $widget->id; ?>" value="">

		<!-- モーダルウィンドウ -->
		<div id="widget-display-setting-<?php echo $widget->id; ?>" class="widget-display-setting" style="display:none;">

			<!-- タブ -->
			<div class="display-setting-tab">
				<div class="tab tab-post active">投稿</div>
				<div class="tab tab-page">固定ページ</div>
				<div class="tab tab-category">カテゴリー</div>
				<div class="tab tab-tag">タグ</div>
				<div class="tab tab-user">著者</div>
				<div class="tab tab-other">その他</div>
			</div>
			<!-- /タブ -->

			<!-- 絞り込み -->
			<div class="display-setting-search">
				<label for="display-setting-search-<?php echo $widget->id; ?>">絞り込み：</label><input type="text" id="display-setting-search-<?php echo $widget->id; ?>" value="">
			</div>
			<!-- /絞り込み -->

			<!-- 投稿 -->
			<div class="display-setting-area-post">
				<select class="post-select">
					<option value="post">投稿から選択</option>
					<option value="post_category">カテゴリーから選択</option>
					<option value="post_tag">タグから選択</option>
					<option value="post_user">著者から選択</option>
				</select>
				<div class="area-title">
					<span class="title title-post">投稿一覧</span>
					<span class="description description-post">選択した投稿ページでウィジェットを非表示にできます</span>
				</div>
				<div id="list-post-<?php echo $widget->id; ?>" class="list list-post list-display-setting">データ取得中</div>
				<div id="list-post_category-<?php echo $widget->id; ?>" class="list list-post_category list-display-setting" style="display: none;">データ取得中</div>
				<div id="list-post_tag-<?php echo $widget->id; ?>" class="list list-post_tag list-display-setting" style="display: none;">データ取得中</div>
				<div id="list-post_user-<?php echo $widget->id; ?>" class="list list-post_user list-display-setting" style="display: none;">データ取得中</div>
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_post_all' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_post_all' ); ?>" value="<?php echo $display_off_post_all; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_post_new' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_post_new' ); ?>" value="<?php echo $display_off_post_new; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_post' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_post' ); ?>" value="<?php echo $display_off_post; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_post_category_all' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_post_category_all' ); ?>" value="<?php echo $display_off_post_category_all; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_post_category_new' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_post_category_new' ); ?>" value="<?php echo $display_off_post_category_new; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_post_category' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_post_category' ); ?>" value="<?php echo $display_off_post_category; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_post_tag_all' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_post_tag_all' ); ?>" value="<?php echo $display_off_post_tag_all; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_post_tag_new' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_post_tag_new' ); ?>" value="<?php echo $display_off_post_tag_new; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_post_tag' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_post_tag' ); ?>" value="<?php echo $display_off_post_tag; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_post_user_all' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_post_user_all' ); ?>" value="<?php echo $display_off_post_user_all; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_post_user_new' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_post_user_new' ); ?>" value="<?php echo $display_off_post_user_new; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_post_user' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_post_user' ); ?>" value="<?php echo $display_off_post_user; ?>">
			</div>
			<!-- /投稿 -->

			<!-- 固定ページ -->
			<div class="display-setting-area-page" style="display: none;">
				<div class="area-title">
					<span class="title">固定ページ一覧</span>
					<span class="description">選択した固定ページでウィジェットを非表示にできます</span>
				</div>
				<div id="list-page-<?php echo $widget->id; ?>" class="list list-page list-display-setting">データ取得中</div>
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_page_all' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_page_all' ); ?>" value="<?php echo $display_off_page_all; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_page_new' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_page_new' ); ?>" value="<?php echo $display_off_page_new; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_page' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_page' ); ?>" value="<?php echo $display_off_page; ?>">
			</div>
			<!-- /固定ページ -->

			<!-- カテゴリー -->
			<div class="display-setting-area-category" style="display: none;">
				<div class="area-title">
					<span class="title">カテゴリー一覧</span>
					<span class="description">選択したカテゴリーアーカイブページでウィジェットを非表示にできます</span>
				</div>
				<div id="list-category-<?php echo $widget->id; ?>" class="list list-category list-display-setting">データ取得中</div>
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_category_all' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_category_all' ); ?>" value="<?php echo $display_off_category_all; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_category_new' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_category_new' ); ?>" value="<?php echo $display_off_category_new; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_category' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_category' ); ?>" value="<?php echo $display_off_category; ?>">
			</div>
			<!-- /カテゴリー -->

			<!-- タグ -->
			<div class="display-setting-area-tag" style="display: none;">
				<div class="area-title">
					<span class="title">タグ一覧</span>
					<span class="description">選択したタグアーカイブページでウィジェットを非表示にできます</span>
				</div>
				<div id="list-tag-<?php echo $widget->id; ?>" class="list list-tag list-display-setting">データ取得中</div>
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_tag_all' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_tag_all' ); ?>" value="<?php echo $display_off_tag_all; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_tag_new' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_tag_new' ); ?>" value="<?php echo $display_off_tag_new; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_tag' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_tag' ); ?>" value="<?php echo $display_off_tag; ?>">
			</div>
			<!-- /タグ -->

			<!-- 著者 -->
			<div class="display-setting-area-user" style="display: none;">
				<div class="area-title">
					<span class="title">著者一覧</span>
					<span class="description">選択した著者アーカイブページでウィジェットを非表示にできます</span>
				</div>
				<div id="list-user-<?php echo $widget->id; ?>" class="list list-user list-display-setting">データ取得中</div>
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_user_all' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_user_all' ); ?>" value="<?php echo $display_off_user_all; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_user_new' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_user_new' ); ?>" value="<?php echo $display_off_user_new; ?>">
				<input type="hidden" name="<?php echo $widget->get_field_name( 'display_off_user' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_user' ); ?>" value="<?php echo $display_off_user; ?>">
			</div>
			<!-- /著者 -->

			<!-- その他 -->
			<div class="display-setting-area-other" style="display: none;">
				<div class="area-title">
					<span class="title">その他</span>
					<span class="description">選択したページでウィジェットを非表示にできます</span>
				</div>
				<div id="list-other-<?php echo $widget->id; ?>" class="list list-other list-display-setting">
					<ul>
						<li>
							<div class="select-item">
								<span class="list-name">年月日アーカイブ</span>
								<div class="select-checkbox">
									<input type="checkbox" name="<?php echo $widget->get_field_name( 'display_off_archive' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_archive' ); ?>" <?php echo $display_off_archive; ?>>
									<label for="<?php echo $widget->get_field_id( 'display_off_archive' ); ?>">非表示にする</label>
								</div>
							</div>
						</li>
						<li>
							<div class="select-item">
								<span class="list-name">検索結果</span>
								<div class="select-checkbox">
									<input type="checkbox" name="<?php echo $widget->get_field_name( 'display_off_search_result' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_search_result' ); ?>" <?php echo $display_off_search_result; ?>>
									<label for="<?php echo $widget->get_field_id( 'display_off_search_result' ); ?>">非表示にする</label>
								</div>
							</div>
						</li>
						<li>
							<div class="select-item">
								<span class="list-name">404ページ</span>
								<div class="select-checkbox">
									<input type="checkbox" name="<?php echo $widget->get_field_name( 'display_off_404' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_404' ); ?>" <?php echo $display_off_404; ?>>
									<label for="<?php echo $widget->get_field_id( 'display_off_404' ); ?>">非表示にする</label>
								</div>
							</div>
						</li>
						<li>
							<div class="select-item">
								<span class="list-name">PCでのアクセス</span>
								<div class="select-checkbox">
									<input type="checkbox" name="<?php echo $widget->get_field_name( 'display_off_pc' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_pc' ); ?>" <?php echo $display_off_pc; ?>>
									<label for="<?php echo $widget->get_field_id( 'display_off_pc' ); ?>">非表示にする</label>
								</div>
							</div>
						</li>
						<li>
							<div class="select-item">
								<span class="list-name">スマートフォンでのアクセス</span>
								<div class="select-checkbox">
									<input type="checkbox" name="<?php echo $widget->get_field_name( 'display_off_sp' ); ?>" id="<?php echo $widget->get_field_id( 'display_off_sp' ); ?>" <?php echo $display_off_sp; ?>>
									<label for="<?php echo $widget->get_field_id( 'display_off_sp' ); ?>">非表示にする</label>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<!-- /その他 -->

			<!-- 閉じるボタン -->
			<?php
			$save_button_label = '保存';
			if ( is_customize_preview() ) {
				$save_button_label = '適用ボタンを押した後に公開';
			}
			?>
			<div class="display-setting-clause">
				<a href="#" class="widget-btn">設定完了（<?php echo $save_button_label; ?>ボタンを押さないと設定は反映されません）</a>
			</div>
			<!-- /閉じるボタン -->

			<input type="hidden" id="display-setting-set-<?php echo $widget->id; ?>" value="false">
		</div>

		<?php
	}
	add_filter( 'in_widget_form', 'fit_widget_display_setting_control', 10, 3 );
}

if ( ! function_exists( 'fit_widget_display_setting_init' ) ) {
	/**
	 * 表示/非表示設定用初期処理
	 */
	function fit_widget_display_setting_init() {
		// モーダル表示用ライブラリ追加
		add_thickbox();

		// widget.js読み込み
		add_action( 'admin_enqueue_scripts', 'fit_enqueue_widget_script' );

		// ウィジェット画面用のAjaxスクリプト
		add_action( 'admin_footer', 'fit_widget_display_setting_category_list_script' );
		add_action( 'admin_footer', 'fit_widget_display_setting_tag_list_script' );
		add_action( 'admin_footer', 'fit_widget_display_setting_user_list_script' );
		add_action( 'admin_footer', 'fit_widget_display_setting_post_list_script' );
		add_action( 'admin_footer', 'fit_widget_display_setting_page_list_script' );

		// カスタマイザー用のAjaxスクリプト
		add_action( 'customize_controls_print_footer_scripts', 'fit_widget_display_setting_category_list_script' );
		add_action( 'customize_controls_print_footer_scripts', 'fit_widget_display_setting_tag_list_script' );
		add_action( 'customize_controls_print_footer_scripts', 'fit_widget_display_setting_user_list_script' );
		add_action( 'customize_controls_print_footer_scripts', 'fit_widget_display_setting_post_list_script' );
		add_action( 'customize_controls_print_footer_scripts', 'fit_widget_display_setting_page_list_script' );
	}
	add_action( 'widgets_init', 'fit_widget_display_setting_init' );
}

if ( ! function_exists( 'fit_update_widget_display_setting' ) ) {
	/**
	 * ウィジェット更新時に表示/非表示設定を保存する
	 */
	function fit_update_widget_display_setting( $instance, $new_instance, $old_instance, $this_widget ) {
		$instance['display_off_post_all']          = ( isset( $new_instance['display_off_post_all'] ) ) ? $new_instance['display_off_post_all'] : '';
		$instance['display_off_post_new']          = ( isset( $new_instance['display_off_post_new'] ) ) ? $new_instance['display_off_post_new'] : '';
		$instance['display_off_post']              = ( isset( $new_instance['display_off_post'] ) ) ? $new_instance['display_off_post'] : '';
		$instance['display_off_post_category_all'] = ( isset( $new_instance['display_off_post_category_all'] ) ) ? $new_instance['display_off_post_category_all'] : '';
		$instance['display_off_post_category_new'] = ( isset( $new_instance['display_off_post_category_new'] ) ) ? $new_instance['display_off_post_category_new'] : '';
		$instance['display_off_post_category']     = ( isset( $new_instance['display_off_post_category'] ) ) ? $new_instance['display_off_post_category'] : '';
		$instance['display_off_post_tag_all']      = ( isset( $new_instance['display_off_post_tag_all'] ) ) ? $new_instance['display_off_post_tag_all'] : '';
		$instance['display_off_post_tag_new']      = ( isset( $new_instance['display_off_post_tag_new'] ) ) ? $new_instance['display_off_post_tag_new'] : '';
		$instance['display_off_post_tag']          = ( isset( $new_instance['display_off_post_tag'] ) ) ? $new_instance['display_off_post_tag'] : '';
		$instance['display_off_post_user_all']     = ( isset( $new_instance['display_off_post_user_all'] ) ) ? $new_instance['display_off_post_user_all'] : '';
		$instance['display_off_post_user_new']     = ( isset( $new_instance['display_off_post_user_new'] ) ) ? $new_instance['display_off_post_user_new'] : '';
		$instance['display_off_post_user']         = ( isset( $new_instance['display_off_post_user'] ) ) ? $new_instance['display_off_post_user'] : '';
		$instance['display_off_page_all']          = ( isset( $new_instance['display_off_page_all'] ) ) ? $new_instance['display_off_page_all'] : '';
		$instance['display_off_page_new']          = ( isset( $new_instance['display_off_page_new'] ) ) ? $new_instance['display_off_page_new'] : '';
		$instance['display_off_page']              = ( isset( $new_instance['display_off_page'] ) ) ? $new_instance['display_off_page'] : '';
		$instance['display_off_category_all']      = ( isset( $new_instance['display_off_category_all'] ) ) ? $new_instance['display_off_category_all'] : '';
		$instance['display_off_category_new']      = ( isset( $new_instance['display_off_category_new'] ) ) ? $new_instance['display_off_category_new'] : '';
		$instance['display_off_category']          = ( isset( $new_instance['display_off_category'] ) ) ? $new_instance['display_off_category'] : '';
		$instance['display_off_tag_all']           = ( isset( $new_instance['display_off_tag_all'] ) ) ? $new_instance['display_off_tag_all'] : '';
		$instance['display_off_tag_new']           = ( isset( $new_instance['display_off_tag_new'] ) ) ? $new_instance['display_off_tag_new'] : '';
		$instance['display_off_tag']               = ( isset( $new_instance['display_off_tag'] ) ) ? $new_instance['display_off_tag'] : '';
		$instance['display_off_user_all']          = ( isset( $new_instance['display_off_user_all'] ) ) ? $new_instance['display_off_user_all'] : '';
		$instance['display_off_user_new']          = ( isset( $new_instance['display_off_user_new'] ) ) ? $new_instance['display_off_user_new'] : '';
		$instance['display_off_user']              = ( isset( $new_instance['display_off_user'] ) ) ? $new_instance['display_off_user'] : '';
		$instance['display_off_archive']           = ( isset( $new_instance['display_off_archive'] ) ) ? $new_instance['display_off_archive'] : '';
		$instance['display_off_search_result']     = ( isset( $new_instance['display_off_search_result'] ) ) ? $new_instance['display_off_search_result'] : '';
		$instance['display_off_404']               = ( isset( $new_instance['display_off_404'] ) ) ? $new_instance['display_off_404'] : '';
		$instance['display_off_pc']                = ( isset( $new_instance['display_off_pc'] ) ) ? $new_instance['display_off_pc'] : '';
		$instance['display_off_sp']                = ( isset( $new_instance['display_off_sp'] ) ) ? $new_instance['display_off_sp'] : '';
		return $instance;
	}
	add_action( 'widget_update_callback', 'fit_update_widget_display_setting', 10, 4 );
}

if ( ! function_exists( 'fit_display_widgets_filter' ) ) {
	/**
	 * 非表示対象のウィジェットを非表示にする
	 */
	function fit_display_widgets_filter( $sidebars_widgets ) {
		foreach ( $sidebars_widgets as $widget_area => $widgets ) {
			if ( $widget_area === 'wp_inactive_widgets' || empty( $widgets ) ) {
				// 使用停止中のウィジェット、ウィジェットが設定されていないエリアはスキップする
				continue;
			}

			foreach ( $widgets as $num => $widget_id ) {
				$widget_info = fit_get_widget_info_by_id( $widget_id );
				$is_display = fit_check_widget_display( $widget_info );
				if ( ! $is_display ) {
					unset( $sidebars_widgets[ $widget_area ][ $num ] );
				}
			}
		}

		return $sidebars_widgets;
	}
	if ( ! is_admin() ) {
		add_filter( 'sidebars_widgets', 'fit_display_widgets_filter', 10 );
	}
}

if ( ! function_exists( 'fit_add_new_post_display_off' ) ) {
	/**
	 * 投稿、固定ページが登録された場合に非表示設定に追加する処理
	 *
	 * @param string  $new_status 投稿、固定ページの更新後のステータス
	 * @param string  $old_status 投稿、固定ページの更新前のステータス
	 * @param WP_Post $post 更新された投稿、固定ページのWP_Postオブジェクト
	 */
	function fit_add_new_post_display_off( $new_status, $old_status, $post ) {
		// 自動保存から 公開済み、下書き、承認待ち、非公開 になったタイミングで実行する
		if ( 'auto-draft' === $old_status
			&& ( 'publish' === $new_status
				|| 'draft' === $new_status
				|| 'pending' === $new_status
				|| 'private' === $new_status
			)
		) {
			if ( 'post' === $post->post_type ) {
				// 投稿
				$sidebars_widgets = wp_get_sidebars_widgets();
				foreach ( $sidebars_widgets as $widget_area => $widgets ) {
					if ( empty( $widgets ) ) {
						// ウィジェットが設定されていないエリアはスキップする
						continue;
					}

					foreach ( $widgets as $num => $widget_id ) {
						fit_update_new_display_off_option( $widget_id, 'post', $post->ID );
					}
				}
			} elseif ( 'page' === $post->post_type ) {
				// 固定ページ
				$sidebars_widgets = wp_get_sidebars_widgets();
				foreach ( $sidebars_widgets as $widget_area => $widgets ) {
					if ( empty( $widgets ) ) {
						// ウィジェットが設定されていないエリアはスキップする
						continue;
					}

					foreach ( $widgets as $num => $widget_id ) {
						fit_update_new_display_off_option( $widget_id, 'page', $post->ID );
					}
				}
			}
		}
	}
	add_action( 'transition_post_status', 'fit_add_new_post_display_off', 10, 3 );
}

if ( ! function_exists( 'fit_add_new_category_display_off' ) ) {
	/**
	 * カテゴリーが登録された場合に非表示設定に追加する処理
	 *
	 * @param int $category_id カテゴリーのID
	 */
	function fit_add_new_category_display_off( $category_id ) {
		$sidebars_widgets = wp_get_sidebars_widgets();
		foreach ( $sidebars_widgets as $widget_area => $widgets ) {
			if ( empty( $widgets ) ) {
				// ウィジェットが設定されていないエリアはスキップする
				continue;
			}

			foreach ( $widgets as $num => $widget_id ) {
				fit_update_new_display_off_option( $widget_id, 'post_category', $category_id );
				fit_update_new_display_off_option( $widget_id, 'category', $category_id );
			}
		}
	}
	add_action( 'create_category', 'fit_add_new_category_display_off' );
}

if ( ! function_exists( 'fit_add_new_tag_display_off' ) ) {
	/**
	 * タグが登録された場合に非表示設定に追加する処理
	 *
	 * @param int $tag_id タグのID
	 */
	function fit_add_new_tag_display_off( $tag_id ) {
		$sidebars_widgets = wp_get_sidebars_widgets();
		foreach ( $sidebars_widgets as $widget_area => $widgets ) {
			if ( empty( $widgets ) ) {
				// ウィジェットが設定されていないエリアはスキップする
				continue;
			}

			foreach ( $widgets as $num => $widget_id ) {
				fit_update_new_display_off_option( $widget_id, 'post_tag', $tag_id );
				fit_update_new_display_off_option( $widget_id, 'tag', $tag_id );
			}
		}
	}
	add_action( 'create_post_tag', 'fit_add_new_tag_display_off' );
}

if ( ! function_exists( 'fit_add_new_user_display_off' ) ) {
	/**
	 * ユーザーが登録された場合に非表示設定に追加する処理
	 *
	 * @param int $user_id ユーザーのID
	 */
	function fit_add_new_user_display_off( $user_id ) {
		$sidebars_widgets = wp_get_sidebars_widgets();
		foreach ( $sidebars_widgets as $widget_area => $widgets ) {
			if ( empty( $widgets ) ) {
				// ウィジェットが設定されていないエリアはスキップする
				continue;
			}

			foreach ( $widgets as $num => $widget_id ) {
				fit_update_new_display_off_option( $widget_id, 'post_user', $user_id );
				fit_update_new_display_off_option( $widget_id, 'user', $user_id );
			}
		}
	}
	add_action( 'user_register', 'fit_add_new_user_display_off' );
}

if ( ! function_exists( 'fit_check_widget_display' ) ) {
	/**
	 * 表示対象のウィジェットかチェックする
	 * ひとつでも条件に当てはまれば非表示にする
	 *
	 * @return boolean 表示するウィジェット => TRUE, 非表示のウィジェット => FALSE.
	 */
	function fit_check_widget_display( $widget_info ) {
		// すべての投稿を非表示
		$display_off_post_all = ( isset( $widget_info['display_off_post_all'] ) ) ? $widget_info['display_off_post_all'] : '';
		if ( 'on' === $display_off_post_all ) {
			if ( is_singular( 'post' ) ) {
				// 個別の投稿ページの場合は非表示(カスタム投稿タイプは含まない)
				return false;
			}
		}
		// 指定した投稿を非表示
		$display_off_post = ( isset( $widget_info['display_off_post'] ) ) ? $widget_info['display_off_post'] : '';
		if ( '' !== $display_off_post ) {
			if ( is_single( explode( ',', $display_off_post ) ) ) {
				// 指定したIDの投稿ページの場合は非表示
				return false;
			}
		}
		// すべてのカテゴリーの投稿を非表示
		$display_off_post_category_all = ( isset( $widget_info['display_off_post_category_all'] ) ) ? $widget_info['display_off_post_category_all'] : '';
		if ( 'on' === $display_off_post_category_all ) {
			if ( is_singular( 'post' ) ) {
				// 個別の投稿ページの場合は非表示(カスタム投稿タイプは含まない)
				return false;
			}
		}
		// 指定したカテゴリーの投稿を非表示
		$display_off_post_category = ( isset( $widget_info['display_off_post_category'] ) ) ? $widget_info['display_off_post_category'] : '';
		if ( '' !== $display_off_post_category ) {
			if ( is_singular( 'post' ) && in_category( explode( ',', $display_off_post_category ) ) ) {
				// 指定したカテゴリーのいずれかに属する投稿ページの場合は非表示
				return false;
			}
		}
		// すべてのタグの投稿を非表示
		$display_off_post_tag_all = ( isset( $widget_info['display_off_post_tag_all'] ) ) ? $widget_info['display_off_post_tag_all'] : '';
		if ( 'on' === $display_off_post_tag_all ) {
			if ( is_singular( 'post' ) && has_tag() ) {
				// タグのある投稿ページの場合は非表示
				return false;
			}
		}
		// 指定したタグの投稿を非表示
		$display_off_post_tag = ( isset( $widget_info['display_off_post_tag'] ) ) ? $widget_info['display_off_post_tag'] : '';
		if ( '' !== $display_off_post_tag ) {
			if ( is_singular( 'post' ) && has_tag( explode( ',', $display_off_post_tag ) ) ) {
				// 指定したいずれかのタグがある投稿ページの場合は非表示
				return false;
			}
		}
		// すべての著者の投稿を非表示
		$display_off_post_user_all = ( isset( $widget_info['display_off_post_user_all'] ) ) ? $widget_info['display_off_post_user_all'] : '';
		if ( 'on' === $display_off_post_user_all ) {
			if ( is_singular( 'post' ) ) {
				// 個別の投稿ページの場合は非表示(カスタム投稿タイプは含まない)
				return false;
			}
		}
		// 指定した著者の投稿を非表示
		$display_off_post_user = ( isset( $widget_info['display_off_post_user'] ) ) ? $widget_info['display_off_post_user'] : '';
		if ( '' !== $display_off_post_user ) {
			global $post;
			if ( is_singular( 'post' ) && $post && in_array( $post->post_author, explode( ',', $display_off_post_user ) ) ) {
				// 指定した著者の投稿ページの場合は非表示
				return false;
			}
		}
		// すべての固定ページを非表示
		$display_off_page_all = ( isset( $widget_info['display_off_page_all'] ) ) ? $widget_info['display_off_page_all'] : '';
		if ( 'on' === $display_off_page_all ) {
			if ( is_page() ) {
				// 固定ページの場合は非表示
				return false;
			}
		}
		// 指定した固定ページを非表示
		$display_off_page = ( isset( $widget_info['display_off_page'] ) ) ? $widget_info['display_off_page'] : '';
		if ( '' !== $display_off_page ) {
			if ( is_page( explode( ',', $display_off_page ) ) ) {
				// 指定した固定ページの場合は非表示
				return false;
			}
		}
		// すべてのカテゴリーアーカイブページを非表示
		$display_off_category_all = ( isset( $widget_info['display_off_category_all'] ) ) ? $widget_info['display_off_category_all'] : '';
		if ( 'on' === $display_off_category_all ) {
			if ( is_category() ) {
				// カテゴリーアーカイブページの場合は非表示
				return false;
			}
		}
		// 指定したカテゴリーアーカイブページを非表示
		$display_off_category = ( isset( $widget_info['display_off_category'] ) ) ? $widget_info['display_off_category'] : '';
		if ( '' !== $display_off_category ) {
			if ( is_category( explode( ',', $display_off_category ) ) ) {
				// 指定したカテゴリーアーカイブページの場合は非表示
				return false;
			}
		}
		// すべてのタグアーカイブページを非表示
		$display_off_tag_all = ( isset( $widget_info['display_off_tag_all'] ) ) ? $widget_info['display_off_tag_all'] : '';
		if ( 'on' === $display_off_tag_all ) {
			if ( is_tag() ) {
				// タグアーカイブページの場合は非表示
				return false;
			}
		}
		// 指定したタグアーカイブページを非表示
		$display_off_tag = ( isset( $widget_info['display_off_tag'] ) ) ? $widget_info['display_off_tag'] : '';
		if ( '' !== $display_off_tag ) {
			if ( is_archive() && is_tag( explode( ',', $display_off_tag ) ) ) {
				// 指定したタグアーカイブページの場合は非表示
				return false;
			}
		}
		// すべての著者アーカイブページを非表示
		$display_off_user_all = ( isset( $widget_info['display_off_user_all'] ) ) ? $widget_info['display_off_user_all'] : '';
		if ( 'on' === $display_off_user_all ) {
			if ( is_author() ) {
				// 著者アーカイブページの場合は非表示
				return false;
			}
		}
		// 指定した著者アーカイブページを非表示
		$display_off_user = ( isset( $widget_info['display_off_user'] ) ) ? $widget_info['display_off_user'] : '';
		if ( '' !== $display_off_user ) {
			if ( is_author( explode( ',', $display_off_user ) ) ) {
				// 指定したタグアーカイブページの場合は非表示
				return false;
			}
		}
		// 日付別アーカイブページを非表示
		$display_off_archive = ( isset( $widget_info['display_off_archive'] ) ) ? $widget_info['display_off_archive'] : '';
		if ( 'on' === $display_off_archive ) {
			if ( is_date() ) {
				// 日付別アーカイブページの場合は非表示
				return false;
			}
		}
		// 検索結果ページを非表示
		$display_off_search_result = ( isset( $widget_info['display_off_search_result'] ) ) ? $widget_info['display_off_search_result'] : '';
		if ( 'on' === $display_off_search_result ) {
			if ( is_search() ) {
				// 検索結果ページの場合は非表示
				return false;
			}
		}
		// 404ページを非表示
		$display_off_404 = ( isset( $widget_info['display_off_404'] ) ) ? $widget_info['display_off_404'] : '';
		if ( 'on' === $display_off_404 ) {
			if ( is_404() ) {
				// 404ページの場合は非表示
				return false;
			}
		}
		// PCからのアクセスでは非表示
		$display_off_pc = ( isset( $widget_info['display_off_pc'] ) ) ? $widget_info['display_off_pc'] : '';
		if ( 'on' === $display_off_pc ) {
			if ( ! is_mobile() ) {
				// スマホでない場合は非表示
				return false;
			}
		}
		// スマホからのアクセスでは非表示
		$display_off_sp = ( isset( $widget_info['display_off_sp'] ) ) ? $widget_info['display_off_sp'] : '';
		if ( 'on' === $display_off_sp ) {
			if ( is_mobile() ) {
				// スマホの場合は非表示
				return false;
			}
		}

		return true;
	}
}

if ( ! function_exists( 'fit_update_new_display_off_option' ) ) {
	/**
	 * 新しく追加されたら非表示にする設定となっている場合に非表示用のパラメーターにIDを追加する
	 */
	function fit_update_new_display_off_option( $widget_id, $type, $id ) {
		$widget_info = fit_get_widget_info_by_id( $widget_id );
		// 新しく追加されたら非表示にする設定の取得
		$display_off_new_switch = ( isset( $widget_info[ 'display_off_' . $type . '_new' ] ) ) ? $widget_info[ 'display_off_' . $type . '_new' ] : '';

		if ( 'on' === $display_off_new_switch ) {
			$display_off_id = ( isset( $widget_info[ 'display_off_' . $type ] ) ) ? $widget_info[ 'display_off_' . $type ] : '';
			if ( '' === $display_off_id ) {
				$display_off_id .= $id;
			} else {
				// すでに設定値がある場合はカンマでつなぐ
				$display_off_id .= ',' . $id;
			}
			$widget_info[ 'display_off_' . $type ] = $display_off_id;
			fit_update_widget_info_by_id( $widget_id, $widget_info );
		}
	}
}
