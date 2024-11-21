<?php

// 管理画面の表示設定に404ページ設定を追加
function fit_add_select_404_field() {
    add_settings_field( 'select404page', '404ページの設定', 'fit_select_404_field', 'reading', 'default', array( 'label_for' => '404_page' ) );
}
add_action( 'admin_init', 'fit_add_select_404_field' );

// 表示するフィールド
function fit_select_404_field( $args ) {
    $page404 = get_option( '404_page' );
?>
    <!-- 固定ページ一覧をプルダウンで表示し、IDが保存されるようにする -->
    <select name="404_page" id="404_page" size="1">
    <option value="">デフォルト</option>
    <?php
    $page_list = get_posts( 'numberposts=-1&order=ASC&post_type=page' );
    if( !empty( $page_list ) ) {
        foreach ($page_list as $page) { ?>
            <?php 
            $page_title = $page->post_title;
            $page_id = $page->ID; ?>
            <option value="<?php echo $page_id ?>"
                <?php if( $page404 == $page_id) echo "selected" ?>>
                <?php echo $page_title ?>
            </option>
        <?php
        }
    }
    ?>
    </select>
<?php
}

// フィールドの内容を保存
function fit_add_custom_whitelist() {
    register_setting( 'reading', '404_page' );
}
add_filter( 'admin_init', 'fit_add_custom_whitelist' );

// 404ページを設定する
function fit_redirect_404() {
	if ( is_404() ) {
        $page404_id = get_option( '404_page' );
        if( !empty( $page404_id) ) {
            wp_safe_redirect( get_permalink( $page404_id ) );
        }
	}
}
add_action( 'get_header', 'fit_redirect_404' );