// filterのポリフィル
if ( ! Array.prototype.filter ) {
	Array.prototype.filter = function( func, thisArg ) {
		'use strict';
		if ( ! ( ( typeof func === 'Function' || typeof func === 'function' ) && this ) )
			throw new TypeError();

		var len = this.length >>> 0,
			res = new Array(len), // preallocate array
			t = this, c = 0, i = -1;

		var kValue;
		if ( thisArg === undefined ) {
			while ( ++i !== len ) {
				// checks to see if the key was set
				if ( i in this ) {
					kValue = t[i]; // in case t is changed in callback
					if ( func( t[i], i, t ) ) {
						res[c++] = kValue;
					}
				}
			}
		} else {
			while ( ++i !== len ) {
				// checks to see if the key was set
				if ( i in this ) {
					kValue = t[i];
					if ( func.call( thisArg, t[i], i, t ) ) {
						res[c++] = kValue;
					}
				}
			}
		}

		res.length = c; // shrink down array to proper size
		return res;
	};
}

/**
 * ウィジェット画面用JavaScript
 */
var FIT_WIDGET = FIT_WIDGET || {};

( function( $ ) {
	/**
	 * 表示/非表示設定
	 */
	FIT_WIDGET.DISPLAY_SETTING = {
		tab_type: {
			post:     'post',
			page:     'page',
			category: 'category',
			tag:      'tag',
			user:     'user',
			other:    'other',
		},
		post_list_type: {
			post:     'post',
			category: 'post_category',
			tag:      'post_tag',
			user:     'post_user',
		},
		/**
		 * 表示/非表示設定のモーダルウィンドウに各種項目の一覧をセットする
		 * @param {string} widget_id ウィジェットのID
		 */
		open_modal: function( widget_id, widget_name, title ) {
			this.set_modal_title( widget_name, title );
			this.bind_event();

			// すでにセット済みかを判定するフラグ
			if ( 'false' === $( '#display-setting-set-' + widget_id ).val() ) {
				// 投稿のリスト
				this.set_post_list( widget_id );
				// 固定ページのリスト
				this.set_page_list( widget_id );
				// カテゴリーのリスト
				this.set_category_list( widget_id );
				// タグのリスト
				this.set_tag_list( widget_id );
				// 著者のリスト
				this.set_user_list( widget_id );
				// フラグをtrueに
				$( '#display-setting-set-' + widget_id ).val( 'true' );
			}
		},
		/**
		 * モーダルウィンドウのタイトルを表示する
		 * @param {string} widget_name ウィジェット名
		 * @param {string} title ウィジェットに設定したタイトル
		 */
		set_modal_title: function( widget_name, title ) {
			if ( $( '#TB_ajaxWindowTitle' ).length ) {
				$( '#TB_ajaxWindowTitle' ).text( '表示 / 非表示設定 ' + widget_name + ': ' + title );
			} else {
				setTimeout( this.set_modal_title, 100, widget_name, title );
			}
		},
		/**
		 * 投稿の一覧をセットする
		 * @param {string} widget_id ウィジェットのID
		 */
		set_post_list: function( widget_id ) {
			if ( '' !== $( '#fit-display-setting-post-list' ).html() ) {
				// データをセット
				$( '.display-setting-area-post > #list-post-' + widget_id ).html( $( '#fit-display-setting-post-list' ).html() );
			} else {
				// データが取得できていなければ2秒後に処理を繰り返す
				setTimeout( this.set_post_list, 2000, widget_id );
			}
			this.add_checkbox( this.tab_type.post, widget_id );
		},
		/**
		 * 固定ページの一覧をセットする
		 * @param {string} widget_id ウィジェットのID
		 */
		set_page_list: function( widget_id ) {
			if ( '' !== $( '#fit-display-setting-page-list' ).html() ) {
				// データをセット
				$( '.display-setting-area-page > #list-page-' + widget_id ).html( $( '#fit-display-setting-page-list' ).html() );
			} else {
				// データが取得できていなければ2秒後に処理を繰り返す
				setTimeout( this.set_page_list, 2000, widget_id );
			}
			this.add_checkbox( this.tab_type.page, widget_id );
		},
		/**
		 * カテゴリーの一覧をセットする
		 * @param {string} widget_id ウィジェットのID
		 */
		set_category_list: function( widget_id ) {
			if ( '' !== $( '#fit-display-setting-category-list' ).html() ) {
				// データをセット
				$( '.display-setting-area-category > #list-category-' + widget_id ).html( $( '#fit-display-setting-category-list' ).html() );
				$( '.display-setting-area-post > #list-post_category-' + widget_id ).html( $( '#fit-display-setting-category-list' ).html() );
			} else {
				// データが取得できていなければ2秒後に処理を繰り返す
				setTimeout( this.set_category_list, 2000, widget_id );
			}
			this.add_checkbox( this.tab_type.category, widget_id );
			this.add_checkbox( this.post_list_type.category, widget_id );
		},
		/**
		 * タグの一覧をセットする
		 * @param {string} widget_id ウィジェットのID
		 */
		set_tag_list: function( widget_id ) {
			if ( '' !== $( '#fit-display-setting-tag-list' ).html() ) {
				// データをセット
				$( '.display-setting-area-tag > #list-tag-' + widget_id ).html( $( '#fit-display-setting-tag-list' ).html() );
				$( '.display-setting-area-post > #list-post_tag-' + widget_id ).html( $( '#fit-display-setting-tag-list' ).html() );
			} else {
				// データが取得できていなければ2秒後に処理を繰り返す
				setTimeout( this.set_tag_list, 2000, widget_id );
			}
			this.add_checkbox( this.tab_type.tag, widget_id );
			this.add_checkbox( this.post_list_type.tag, widget_id );
		},
		/**
		 * 著者の一覧をセットする
		 * @param {string} widget_id ウィジェットのID
		 */
		set_user_list: function( widget_id ) {
			if ( '' !== $( '#fit-display-setting-user-list' ).html() ) {
				// データをセット
				$( '.display-setting-area-user > #list-user-' + widget_id ).html( $( '#fit-display-setting-user-list' ).html() );
				$( '.display-setting-area-post > #list-post_user-' + widget_id ).html( $( '#fit-display-setting-user-list' ).html() );
			} else {
				// データが取得できていなければ2秒後に処理を繰り返す
				setTimeout( this.set_user_list, 2000, widget_id );
			}
			this.add_checkbox( this.tab_type.user, widget_id );
			this.add_checkbox( this.post_list_type.user, widget_id );
		},
		/**
		 * チェックボックスを追加する
		 * @param {string} type 
		 * @param {string} widget_id ウィジェットのID
		 */
		add_checkbox: function( type, widget_id ) {
			// チェックボックス作成
			var checkbox = '';
			checkbox += '<div class="select-checkbox">';
			checkbox += '    <input type="checkbox" id="' + type + '-all-' + widget_id + '">';
			checkbox += '    <label for="' + type + '-all-' + widget_id + '">非表示にする</label>';
			checkbox += '</div>';
			$( 'div[class*="display-setting-area"] #list-' + type + '-' + widget_id + ' .all' ).children( '.select-item' ).append( checkbox );

			checkbox = '';
			checkbox += '<div class="select-checkbox">';
			checkbox += '    <input type="checkbox" id="' + type + '-new-' + widget_id + '">';
			checkbox += '    <label for="' + type + '-new-' + widget_id + '">非表示にする</label>';
			checkbox += '</div>';
			$( 'div[class*="display-setting-area"] #list-' + type + '-' + widget_id + ' .new' ).children( '.select-item' ).append( checkbox );

			$( 'div[class*="display-setting-area"] #list-' + type + '-' + widget_id + ' li[class*="' + type + '-"]' ).each( function() {
				var class_name = $( this ).attr( 'class' );
				var sep_pos = class_name.lastIndexOf( '-' );
				var id = class_name.substring( sep_pos + 1 );
				checkbox = '';
				checkbox += '<div class="select-checkbox">';
				checkbox += '    <input type="checkbox" id="' + type + '-' + id + '-' + widget_id + '" class="check-' + type + '-' + widget_id + '" value="' + id + '">';
				checkbox += '    <label for="' + type + '-' + id + '-' + widget_id + '">非表示にする</label>';
				checkbox += '</div>';
				$( this ).children( '.select-item' ).children( '.list-name' ).after( checkbox );
			} );

			// チェックボックスをチェック
			if ( 'on' === $( '#widget-' + widget_id + '-display_off_' + type + '_all' ).val() ) {
				$( 'div[class*="display-setting-area"] #' + type + '-all-' + widget_id ).prop( 'checked', true );
			}

			if ( 'on' === $( '#widget-' + widget_id + '-display_off_' + type + '_new' ).val() ) {
				$( 'div[class*="display-setting-area"] #' + type + '-new-' + widget_id ).prop( 'checked', true );
			}

			if ( '' !== $( '#widget-' + widget_id + '-display_off_' + type ).val() ) {
				var ids = $( '#widget-' + widget_id + '-display_off_' + type ).val().split( ',' );
				ids.forEach( function( id ) {
					if ( '' !== id ) {
						$( 'div[class*="display-setting-area"] #' + type + '-' + id + '-' + widget_id ).prop( 'checked', true );
					}
				} );
			}

			// チェックした時の処理をバインド
			$( 'div[class*="display-setting-area"] #' + type + '-all-' + widget_id ).change( function() {
				if ( $( this ).prop( 'checked' ) ) {
					$( '#widget-' + widget_id + '-display_off_' + type + '_all' ).val( 'on' );
					$( 'div[class*="display-setting-area"] #' + type + '-new-' + widget_id ).prop( 'checked', true );
					$( 'div[class*="display-setting-area"] #' + type + '-new-' + widget_id ).change();
					$( 'div[class*="display-setting-area"] .check-' + type + '-' + widget_id ).each( function() {
						$( this ).prop( 'checked', true );
						$( this ).change();
					} );
				} else {
					$( '#widget-' + widget_id + '-display_off_' + type + '_all' ).val( '' );
					$( 'div[class*="display-setting-area"] #' + type + '-new-' + widget_id ).prop( 'checked', false );
					$( 'div[class*="display-setting-area"] #' + type + '-new-' + widget_id ).change();
					$( 'div[class*="display-setting-area"] .check-' + type + '-' + widget_id ).each( function() {
						$( this ).prop( 'checked', false );
						$( this ).change();
					} );
				}
				$( '#display-setting-flag-' + widget_id ).change();
			} );

			$( 'div[class*="display-setting-area"] #' + type + '-new-' + widget_id ).change( function() {
				if ( $( this ).prop( 'checked' ) ) {
					$( '#widget-' + widget_id + '-display_off_' + type + '_new' ).val( 'on' );
				} else {
					$( '#widget-' + widget_id + '-display_off_' + type + '_new' ).val( '' );
					$( '#widget-' + widget_id + '-display_off_' + type + '_all' ).val( '' );
					$( 'div[class*="display-setting-area"] #' + type + '-all-' + widget_id ).prop( 'checked', false );
				}
				$( '#display-setting-flag-' + widget_id ).change();
			} );

			$( 'div[class*="display-setting-area"] .check-' + type + '-' + widget_id ).change( function() {
				var ids = $( '#widget-' + widget_id + '-display_off_' + type ).val().split( ',' );
				if ( $( this ).prop( 'checked' ) ) {
					if ( $.inArray( $( this ).val(), ids ) === -1 ) {
						ids.push( $( this ).val() );
					}
				} else {
					ids = ids.filter( function( value ) {
						return value != this;
					}, $( this ).val() );
					$( '#widget-' + widget_id + '-display_off_' + type + '_all' ).val( '' );
					$( 'div[class*="display-setting-area"] #' + type + '-all-' + widget_id ).prop( 'checked', false );
				}
				ids = ids.filter( function( value ) {
					return value;
				} );
				$( '#widget-' + widget_id + '-display_off_' + type ).val( ids.join( ',' ) );
				$( '#display-setting-flag-' + widget_id ).change();
			} );
		},
		/**
		 * タブの切り替え
		 * @param {string} type 
		 */
		change_tab: function( type ) {
			if (
				this.tab_type.post !== type
				&& this.tab_type.page !== type
				&& this.tab_type.category !== type
				&& this.tab_type.tag !== type
				&& this.tab_type.user !== type
				&& this.tab_type.other !== type
			) {
				return;
			}
			$( '.display-setting-area-post' ).hide();
			$( '.display-setting-area-page' ).hide();
			$( '.display-setting-area-category' ).hide();
			$( '.display-setting-area-tag' ).hide();
			$( '.display-setting-area-user' ).hide();
			$( '.display-setting-area-other' ).hide();
			$( 'div.display-setting-tab > .tab-post' ).removeClass( 'active' );
			$( 'div.display-setting-tab > .tab-page' ).removeClass( 'active' );
			$( 'div.display-setting-tab > .tab-category' ).removeClass( 'active' );
			$( 'div.display-setting-tab > .tab-tag' ).removeClass( 'active' );
			$( 'div.display-setting-tab > .tab-user' ).removeClass( 'active' );
			$( 'div.display-setting-tab > .tab-other' ).removeClass( 'active' );

			$( '.display-setting-area-' + type ).show();
			$( 'div.display-setting-tab > .tab-' + type ).addClass( 'active' );
		},
		/**
		 * 投稿タブに表示するリストの切り替え
		 * @param {*} type 
		 */
		change_list: function( type ) {
			if (
				this.post_list_type.post !== type
				&& this.post_list_type.category !== type
				&& this.post_list_type.tag !== type
				&& this.post_list_type.user !== type
			) {
				return;
			}
			$( '.display-setting-area-post .list-post' ).hide();
			$( '.display-setting-area-post .list-post_category' ).hide();
			$( '.display-setting-area-post .list-post_tag' ).hide();
			$( '.display-setting-area-post .list-post_user' ).hide();

			$( '.list-' + type ).show();
			switch ( type ) {
				case this.post_list_type.post:
					$( '.display-setting-area-post > .area-title > .title-post' ).text( '投稿一覧' );
					$( '.display-setting-area-post > .area-title > .description-post' ).text( '選択した投稿ページでウィジェットを非表示にできます' );
					break;
				case this.post_list_type.category:
					$( '.display-setting-area-post > .area-title > .title-post' ).text( 'カテゴリー一覧' );
					$( '.display-setting-area-post > .area-title > .description-post' ).text( '選択したカテゴリーに属する投稿ページでウィジェットを非表示にできます' );
					break;
				case this.post_list_type.tag:
					$( '.display-setting-area-post > .area-title > .title-post' ).text( 'タグ一覧' );
					$( '.display-setting-area-post > .area-title > .description-post' ).text( '選択したタグに属する投稿ページでウィジェットを非表示にできます' );
					break;
				case this.post_list_type.user:
					$( '.display-setting-area-post > .area-title > .title-post' ).text( '著者一覧' );
					$( '.display-setting-area-post > .area-title > .description-post' ).text( '選択した著者の投稿ページでウィジェットを非表示にできます' );
					break;
			}
			$( '.display-setting-area-post > .post-select' ).each( function() {
				$( this ).val( type );
			} );
		},
		/**
		 * 絞り込み検索
		 * @param {string} value 絞り込みに入力された値
		 * @param {string} widget_id ウィジェットID
		 */
		apply_list_filter: function( value, widget_id ) {
			$( 'div[id*="' + widget_id + '"].list-display-setting .list-name' ).each( function() {
				if ( $( this ).text().indexOf( value ) >= 0 ) {
					$( this ).parent().show();
				} else {
					$( this ).parent().hide();
				}
			} );
		},
		/**
		 * 各種操作した際のアクションをイベントにバインド
		 */
		bind_event: function() {
			// 投稿タブのクリック
			$( '.display-setting-tab > .tab-post' ).off( 'click' );
			$( '.display-setting-tab > .tab-post' ).click( function() {
				FIT_WIDGET.DISPLAY_SETTING.change_tab( FIT_WIDGET.DISPLAY_SETTING.tab_type.post );
			} );
			// 固定ページタブのクリック
			$( '.display-setting-tab > .tab-page' ).off( 'click' );
			$( '.display-setting-tab > .tab-page' ).click( function() {
				FIT_WIDGET.DISPLAY_SETTING.change_tab( FIT_WIDGET.DISPLAY_SETTING.tab_type.page );
			} );
			// カテゴリータブのクリック
			$( '.display-setting-tab > .tab-category' ).off( 'click' );
			$( '.display-setting-tab > .tab-category' ).click( function() {
				FIT_WIDGET.DISPLAY_SETTING.change_tab( FIT_WIDGET.DISPLAY_SETTING.tab_type.category );
			} );
			// タグタブのクリック
			$( '.display-setting-tab > .tab-tag' ).off( 'click' );
			$( '.display-setting-tab > .tab-tag' ).click( function() {
				FIT_WIDGET.DISPLAY_SETTING.change_tab( FIT_WIDGET.DISPLAY_SETTING.tab_type.tag );
			} );
			// 著者タブのクリック
			$( '.display-setting-tab > .tab-user' ).off( 'click' );
			$( '.display-setting-tab > .tab-user' ).click( function() {
				FIT_WIDGET.DISPLAY_SETTING.change_tab( FIT_WIDGET.DISPLAY_SETTING.tab_type.user );
			} );
			// その他タブのクリック
			$( '.display-setting-tab > .tab-other' ).off( 'click' );
			$( '.display-setting-tab > .tab-other' ).click( function() {
				FIT_WIDGET.DISPLAY_SETTING.change_tab( FIT_WIDGET.DISPLAY_SETTING.tab_type.other );
			} );
			// 投稿タブで選択する種類の変更
			$( '.display-setting-area-post > .post-select' ).off( 'change' );
			$( '.display-setting-area-post > .post-select' ).change( function() {
				FIT_WIDGET.DISPLAY_SETTING.change_list( $( this ).val() );
			} );
			// 絞り込みの入力
			$( '[id^="display-setting-search-"]' ).off( 'keyup' );
			$( '[id^="display-setting-search-"]' ).keyup( function() {
				widget_id = $( this ).attr( 'id' ).replace( 'display-setting-search-', '' );
				FIT_WIDGET.DISPLAY_SETTING.apply_list_filter( $( this ).val(), widget_id );
			} );
			// 年月日アーカイブチェックボックスのチェック
			$( '[id$="display_off_archive"]' ).off( 'change' );
			$( '[id$="display_off_archive"]' ).change( function() {
				widget_id = $( this ).attr( 'id' ).replace( 'widget-', '' ).replace( '-display_off_archive', '' );
				$( '#display-setting-flag-' + widget_id ).change();
			} );
			// 検索結果チェックボックスのチェック
			$( '[id$="display_off_search_result"]' ).off( 'change' );
			$( '[id$="display_off_search_result"]' ).change( function() {
				widget_id = $( this ).attr( 'id' ).replace( 'widget-', '' ).replace( '-display_off_search_result', '' );
				$( '#display-setting-flag-' + widget_id ).change();
			} );
			// 404ページチェックボックスのチェック
			$( '[id$="display_off_404"]' ).off( 'change' );
			$( '[id$="display_off_404"]' ).change( function() {
				widget_id = $( this ).attr( 'id' ).replace( 'widget-', '' ).replace( '-display_off_404', '' );
				$( '#display-setting-flag-' + widget_id ).change();
			} );
			// PCでのアクセスチェックボックスのチェック
			$( '[id$="display_off_pc"]' ).off( 'change' );
			$( '[id$="display_off_pc"]' ).change( function() {
				widget_id = $( this ).attr( 'id' ).replace( 'widget-', '' ).replace( '-display_off_pc', '' );
				$( '#display-setting-flag-' + widget_id ).change();
			} );
			// スマートフォンでのアクセスチェックボックスのチェック
			$( '[id$="display_off_sp"]' ).off( 'change' );
			$( '[id$="display_off_sp"]' ).change( function() {
				widget_id = $( this ).attr( 'id' ).replace( 'widget-', '' ).replace( '-display_off_sp', '' );
				$( '#display-setting-flag-' + widget_id ).change();
			} );
			// 設定完了ボタンのクリック
			$( '.display-setting-clause > a' ).off( 'click' );
			$( '.display-setting-clause > a' ).click( function() {
				tb_remove();
			} );
		}
	}

	/**
	 * 人気記事
	 */
	FIT_WIDGET.RANK = {
		tab_type: {
			category: 'category',
			tag:      'tag',
			user:     'user',
		},
		/**
		 * 対象記事設定のモーダルウィンドウに各種項目の一覧をセットする
		 * @param {string} widget_id ウィジェットのID
		 */
		open_modal: function( widget_id, widget_name, title ) {
			this.set_modal_title( widget_name, title );
			this.bind_event();

			// すでにセット済みかを判定するフラグ
			if ( 'false' === $( '#rank-setting-set-' + widget_id ).val() ) {
				// カテゴリーのリスト
				this.set_category_list( widget_id );
				// タグのリスト
				this.set_tag_list( widget_id );
				// 著者のリスト
				this.set_user_list( widget_id );
				// フラグをtrueに
				$( '#rank-setting-set-' + widget_id ).val( 'true' );
			}
		},
		/**
		 * モーダルウィンドウのタイトルを表示する
		 * @param {string} widget_name ウィジェット名
		 * @param {string} title ウィジェットに設定したタイトル
		 */
		set_modal_title: function( widget_name, title ) {
			if ( $( '#TB_ajaxWindowTitle' ).length ) {
				$( '#TB_ajaxWindowTitle' ).text( '対象記事の絞り込み ' + widget_name + ': ' + title );
			} else {
				setTimeout( this.set_modal_title, 100, widget_name, title );
			}
		},
		/**
		 * カテゴリーの一覧をセットする
		 * @param {string} widget_id ウィジェットのID
		 */
		set_category_list: function( widget_id ) {
			if ( '' !== $( '#fit-rank-setting-category-list' ).html() ) {
				// データをセット
				$( '.rank-setting-area-category > #list-category-' + widget_id ).html( $( '#fit-rank-setting-category-list' ).html() );
			} else {
				// データが取得できていなければ2秒後に処理を繰り返す
				setTimeout( this.set_category_list, 2000, widget_id );
			}
			this.add_checkbox( this.tab_type.category, widget_id );
		},
		/**
		 * タグの一覧をセットする
		 * @param {string} widget_id ウィジェットのID
		 */
		set_tag_list: function( widget_id ) {
			if ( '' !== $( '#fit-rank-setting-tag-list' ).html() ) {
				// データをセット
				$( '.rank-setting-area-tag > #list-tag-' + widget_id ).html( $( '#fit-rank-setting-tag-list' ).html() );
			} else {
				// データが取得できていなければ2秒後に処理を繰り返す
				setTimeout( this.set_tag_list, 2000, widget_id );
			}
			this.add_checkbox( this.tab_type.tag, widget_id );
		},
		/**
		 * 著者の一覧をセットする
		 * @param {string} widget_id ウィジェットのID
		 */
		set_user_list: function( widget_id ) {
			if ( '' !== $( '#fit-rank-setting-user-list' ).html() ) {
				// データをセット
				$( '.rank-setting-area-user > #list-user-' + widget_id ).html( $( '#fit-rank-setting-user-list' ).html() );
			} else {
				// データが取得できていなければ2秒後に処理を繰り返す
				setTimeout( this.set_user_list, 2000, widget_id );
			}
			this.add_checkbox( this.tab_type.user, widget_id );
		},
		/**
		 * チェックボックスを追加する
		 * @param {string} type 
		 * @param {string} widget_id ウィジェットのID
		 */
		add_checkbox: function( type, widget_id ) {
			// チェックボックス作成
			var checkbox = '';
			checkbox += '<div class="select-checkbox">';
			checkbox += '    <input type="checkbox" id="' + type + '-all-' + widget_id + '">';
			checkbox += '    <label for="' + type + '-all-' + widget_id + '">ランキングの対象にする</label>';
			checkbox += '</div>';
			$( 'div[class*="rank-setting-area"] #list-' + type + '-' + widget_id + ' .all' ).children( '.select-item' ).append( checkbox );

			checkbox = '';
			checkbox += '<div class="select-checkbox">';
			checkbox += '    <input type="checkbox" id="' + type + '-new-' + widget_id + '">';
			checkbox += '    <label for="' + type + '-new-' + widget_id + '">ランキングの対象にする</label>';
			checkbox += '</div>';
			$( 'div[class*="rank-setting-area"] #list-' + type + '-' + widget_id + ' .new' ).children( '.select-item' ).append( checkbox );

			$( 'div[class*="rank-setting-area"] #list-' + type + '-' + widget_id + ' li[class*="' + type + '-"]' ).each( function() {
				var class_name = $( this ).attr( 'class' );
				var sep_pos = class_name.lastIndexOf( '-' );
				var id = class_name.substring( sep_pos + 1 );
				checkbox = '';
				checkbox += '<div class="select-checkbox">';
				checkbox += '    <input type="checkbox" id="' + type + '-' + id + '-' + widget_id + '_in" class="check-' + type + '-' + widget_id + '_in" value="' + id + '">';
				checkbox += '    <label for="' + type + '-' + id + '-' + widget_id + '">ランキングの対象にする</label>';
				checkbox += '</div>';
				$( this ).children( '.select-item' ).children( '.list-name' ).after( checkbox );
			} );

			// チェックボックスをチェック
			if ( 'on' === $( '#widget-' + widget_id + '-rank_' + type + '_all' ).val() ) {
				$( 'div[class*="rank-setting-area"] #' + type + '-all-' + widget_id ).prop( 'checked', true );
				$( 'div[class*="rank-setting-area"] .check-' + type + '-' + widget_id + '_in' ).each( function() {
					$( this ).prop( 'checked', true );
				} );
			}

			if ( 'on' === $( '#widget-' + widget_id + '-rank_' + type + '_new' ).val() ) {
				$( 'div[class*="rank-setting-area"] #' + type + '-new-' + widget_id ).prop( 'checked', true );
			}

			if ( '' !== $( '#widget-' + widget_id + '-rank_' + type + '_in' ).val() ) {
				var ids = $( '#widget-' + widget_id + '-rank_' + type + '_in' ).val().split( ',' );
				ids.forEach( function( id ) {
					if ( '' !== id ) {
						$( 'div[class*="rank-setting-area"] #' + type + '-' + id + '-' + widget_id + '_in' ).prop( 'checked', true );
					}
				} );
			}

			// チェックした時の処理をバインド
			$( 'div[class*="rank-setting-area"] #' + type + '-all-' + widget_id ).change( function() {
				if ( $( this ).prop( 'checked' ) ) {
					$( '#widget-' + widget_id + '-rank_' + type + '_all' ).val( 'on' );
					$( 'div[class*="rank-setting-area"] #' + type + '-new-' + widget_id ).prop( 'checked', true );
					$( 'div[class*="rank-setting-area"] #' + type + '-new-' + widget_id ).change();
					$( 'div[class*="rank-setting-area"] .check-' + type + '-' + widget_id + '_in' ).each( function() {
						$( this ).prop( 'checked', true );
					} );
					$( '#widget-' + widget_id + '-rank_' + type + '_in' ).val( '' );
				} else {
					$( '#widget-' + widget_id + '-rank_' + type + '_all' ).val( '' );
					$( 'div[class*="rank-setting-area"] #' + type + '-new-' + widget_id ).prop( 'checked', false );
					$( 'div[class*="rank-setting-area"] #' + type + '-new-' + widget_id ).change();
					$( 'div[class*="rank-setting-area"] .check-' + type + '-' + widget_id + '_in' ).each( function() {
						$( this ).prop( 'checked', false );
					} );
					$( '#widget-' + widget_id + '-rank_' + type + '_in' ).val( '' );
				}
				$( '#rank-setting-flag-' + widget_id ).change();
			} );

			$( 'div[class*="rank-setting-area"] #' + type + '-new-' + widget_id ).change( function() {
				if ( $( this ).prop( 'checked' ) ) {
					$( '#widget-' + widget_id + '-rank_' + type + '_new' ).val( 'on' );
				} else {
					$( '#widget-' + widget_id + '-rank_' + type + '_new' ).val( '' );
					$( '#widget-' + widget_id + '-rank_' + type + '_all' ).val( '' );
					$( 'div[class*="rank-setting-area"] #' + type + '-all-' + widget_id ).prop( 'checked', false );
					var ids = FIT_WIDGET.RANK.get_checked_ids( type, widget_id );
					$( '#widget-' + widget_id + '-rank_' + type + '_in' ).val( ids.join( ',' ) );
				}
				$( '#display-setting-flag-' + widget_id ).change();
			} );

			$( 'div[class*="rank-setting-area"] .check-' + type + '-' + widget_id + '_in' ).change( function() {
				var ids = FIT_WIDGET.RANK.get_checked_ids( type, widget_id );
				$( '#widget-' + widget_id + '-rank_' + type + '_in' ).val( ids.join( ',' ) );
				$( '#rank-setting-flag-' + widget_id ).change();
			} );
		},
		/**
		 * チェックされている項目のIDをすべて取得する
		 * @param {*} type 
		 * @param {*} widget_id 
		 * @return {array} IDの配列
		 */
		get_checked_ids: function( type, widget_id ) {
			var ids = $( '#widget-' + widget_id + '-rank_' + type + '_in' ).val().split( ',' );
			$( 'div[class*="rank-setting-area"] .check-' + type + '-' + widget_id + '_in' ).each( function() {
				if ( $( this ).prop( 'checked' ) ) {
					if ( $.inArray( $( this ).val(), ids ) === -1 ) {
						ids.push( $( this ).val() );
					}
				} else {
					ids = ids.filter( function( value ) {
						return value != this;
					}, $( this ).val() );
					$( '#widget-' + widget_id + '-rank_' + type + '_all' ).val( '' );
					$( 'div[class*="rank-setting-area"] #' + type + '-all-' + widget_id ).prop( 'checked', false );
				}
				ids = ids.filter( function( value ) {
					return value;
				} );
			} );
			return ids;
		},
		/**
		 * タブの切り替え
		 * @param {string} type 
		 */
		change_tab: function( type ) {
			if (
				this.tab_type.category !== type
				&& this.tab_type.tag !== type
				&& this.tab_type.user !== type
			) {
				return;
			}
			$( '.rank-setting-area-category' ).hide();
			$( '.rank-setting-area-tag' ).hide();
			$( '.rank-setting-area-user' ).hide();
			$( 'div.rank-setting-tab > .tab-category' ).removeClass( 'active' );
			$( 'div.rank-setting-tab > .tab-tag' ).removeClass( 'active' );
			$( 'div.rank-setting-tab > .tab-user' ).removeClass( 'active' );

			$( '.rank-setting-area-' + type ).show();
			$( 'div.rank-setting-tab > .tab-' + type ).addClass( 'active' );
		},
		/**
		 * 絞り込み検索
		 * @param {string} value 絞り込みに入力された値
		 * @param {string} widget_id ウィジェットID
		 */
		apply_list_filter: function( value, widget_id ) {
			$( 'div[id*="' + widget_id + '"].list-rank-setting .list-name' ).each( function() {
				if ( $( this ).text().indexOf( value ) >= 0 ) {
					$( this ).parent().show();
				} else {
					$( this ).parent().hide();
				}
			} );
		},
		/**
		 * 各種操作した際のアクションをイベントにバインド
		 */
		bind_event: function() {
			// カテゴリータブのクリック
			$( '.rank-setting-tab > .tab-category' ).off( 'click' );
			$( '.rank-setting-tab > .tab-category' ).click( function() {
				FIT_WIDGET.RANK.change_tab( FIT_WIDGET.RANK.tab_type.category );
			} );
			// タグタブのクリック
			$( '.rank-setting-tab > .tab-tag' ).off( 'click' );
			$( '.rank-setting-tab > .tab-tag' ).click( function() {
				FIT_WIDGET.RANK.change_tab( FIT_WIDGET.RANK.tab_type.tag );
			} );
			// 著者タブのクリック
			$( '.rank-setting-tab > .tab-user' ).off( 'click' );
			$( '.rank-setting-tab > .tab-user' ).click( function() {
				FIT_WIDGET.RANK.change_tab( FIT_WIDGET.RANK.tab_type.user );
			} );
			// 絞り込みの入力
			$( '[id^="rank-setting-search-"]' ).off( 'keyup' );
			$( '[id^="rank-setting-search-"]' ).keyup( function() {
				widget_id = $( this ).attr( 'id' ).replace( 'rank-setting-search-', '' );
				FIT_WIDGET.RANK.apply_list_filter( $( this ).val(), widget_id );
			} );
			// 設定完了ボタンのクリック
			$( '.rank-setting-clause > a' ).off( 'click' );
			$( '.rank-setting-clause > a' ).click( function() {
				tb_remove();
			} );
		}
	}
} ( jQuery ) );
