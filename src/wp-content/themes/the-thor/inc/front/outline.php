<?php
////////////////////////////////////////////////////////
// オリジナル目次
////////////////////////////////////////////////////////

if ( ! function_exists( 'fit_get_outline_info' ) ) {
	/**
	 * オリジナル目次を作成する
	 *
	 * @param string $content 記事内容
	 * @return array content => HタグにIDを付与した記事内容, outline => 目次ボックス, count => 見出しの数
	 */
	function fit_get_outline_info( $content ) {
		// 目次のHTMLを入れる変数を定義します。
		$outline = '';

		// h1〜h6タグの個数を入れる変数を定義します。
		$counter = 0;

		// 検索する見出しのレベルを定義
		$hNumber = '5';
		if ( is_singular( 'post' ) ) {
			$hNumber = get_option( 'fit_postOutline_hNumber', '5' );
		} elseif ( is_page() ) {
			$hNumber = get_option( 'fit_pageOutline_hNumber', '5' );
		}

		// 記事内のh1〜h?タグを検索します。(idやclass属性も含むように改良)
		if ( preg_match_all( '/<h([1-' . $hNumber . '])([^>]*)>([\s\S]*?)<\/h\1>/i', $content, $matches, PREG_SET_ORDER ) ) {
			// 記事内で使われているh1〜h6タグの中の、1〜6の中の一番小さな数字を取得します。
			// ※以降ソースの中にある、levelという単語は1〜6のことを表します。
			$min_level = min(
				array_map(
					function( $m ) {
						return $m[1];
					},
					$matches
				)
			);

			// スタート時のlevelを決定します。
			// ※このレベルが上がる毎に、<ul></li>タグが追加されていきます。
			$current_level = $min_level - 1;

			// 各レベルの出現数を格納する配列を定義します。
			$sub_levels = array(
				'1' => 0,
				'2' => 0,
				'3' => 0,
				'4' => 0,
				'5' => 0,
				'6' => 0,
			);

			// アンカーリングに使うIDの文字列の定義
			$outline_id_string = 'outline_';
			$outline_id_num    = 1;
			while ( false !== strpos( $content, $outline_id_string . $outline_id_num ) ) {
				$outline_id_num++;
			}

			// 記事内で見つかった、hタグの数だけループします。
			foreach ( $matches as $m ) {
				$level = $m[1]; // 見つかったhタグのlevelを取得します。
				$attr  = $m[2]; // 見つかったhタグの属性を取得します。
				$text  = $m[3]; // 見つかったhタグの、タグの中身を取得します。

				// li, ulタグを閉じる処理です。2ループ目以降に中に入る可能性があります。
				// 例えば、前回処理したのがh3タグで、今回出現したのがh2タグの場合、
				// h3タグ用のulを閉じて、h2タグに備えます。
				while ( $current_level > $level ) {
					$current_level--;
					$outline .= '</li></ul>';
				}

				// 同じlevelの場合、liタグを閉じ、新しく開きます。
				if ( $current_level == $level ) {
					$outline .= '</li><li class="outline__item">';
				} else {
					// 同じlevelでない場合は、ul, liタグを追加していきます。
					// 例えば、前回処理したのがh2タグで、今回出現したのがh3タグの場合、
					// h3タグのためにulを追加します。
					while ( $current_level < $level ) {
						$current_level++;
						$outline .= sprintf( '<ul class="outline__list outline__list-%s"><li class="outline__item">', $current_level );
					}
					// 見出しのレベルが変わった場合は、現在のレベル以下の出現回数をリセットします。
					for ( $idx = $current_level; $idx < count( $sub_levels ); $idx++ ) {
						$sub_levels[ $idx ] = 0;
					}
				}

				// 各レベルの出現数を格納する配列を更新します。
				$sub_levels[ $current_level ]++;
				// 現在処理中のhタグの、パスを入れる配列を定義します。
				// 例えば、h2 -> h3 -> h3タグと進んでいる場合は、
				// level_fullpathはarray(1, 2)のようになります。
				// ※level_fullpath[0]の1は、1番目のh2タグの直下に入っていることを表します。
				// ※level_fullpath[1]の2は、2番目のh3を表します。
				$level_fullpath = array();
				for ( $idx = $min_level; $idx <= $level; $idx++ ) {
					$level_fullpath[] = $sub_levels[ $idx ];
				}
				$target_anchor     = $outline_id_string . $outline_id_num . '__' . implode( '_', $level_fullpath );
				$idpos             = strpos( strtolower( $attr ), 'id=' );
				$tmp_target_anchor = '';
				if ( $idpos !== false ) {
					// もとからIDがある場合はアンカーにはそのIDを使う
					$tmp_target_anchor = substr( $attr, $idpos + 4 );
					$tmp_target_anchor = substr( $tmp_target_anchor, 0, strpos( $tmp_target_anchor, '"' ) );
					if ( $tmp_target_anchor !== '' ) {
						$target_anchor = $tmp_target_anchor;
					}
				}

				// 目次に、<a href="#outline_1_2">1.2 見出し</a>のような形式で見出しを追加します。
				$outline .= sprintf( '<a class="outline__link" href="#%s"><span class="outline__number">%s</span> %s</a>', $target_anchor, implode( '.', $level_fullpath ), strip_tags( $text ) );

				// 本文中の見出し本体を、IDが入っていない場合に <h3>見出し</h3> から <h3 id="outline_1_2">見出し</h3> のような形式で置き換えます。
				if ( $idpos === false ) {
					$hid     = preg_replace( '/<h([1-' . $hNumber . '])/i', '<h\1 id="' . $target_anchor . '"', $m[0] );
					$content = preg_replace( '/<h([1-' . $hNumber . '])(?![^<]*id=).*?>([\s\S]*?)<\/h([1-' . $hNumber . '])>/i', $hid, $content, 1 );
				} elseif ( $tmp_target_anchor === '' ) {
					$hid     = preg_replace( '/<h([1-' . $hNumber . '])/i', '<h\1 id="' . $target_anchor . '"', str_replace( 'id=""', '', $m[0] ) );
					$content = preg_replace( '/<h([1-' . $hNumber . ']).*id="".*>([\s\S]*?)<\/h([1-' . $hNumber . '])>/i', $hid, $content, 1 );
				}
			}

			// hタグのループが終了後、閉じられていないulタグを閉じていきます。
			while ( $current_level >= $min_level ) {
				$outline .= '</li></ul>';
				$current_level--;
			}
			// h1〜h6タグの個数
			$counter = count( $matches );
		}

		return array(
			'content' => $content,
			'outline' => $outline,
			'count'   => $counter,
		);
	}
}

if ( ! function_exists( 'fit_add_outline' ) ) {
	/**
	 * 記事内容に目次ボックスを追加する処理
	 *
	 * @param string $content 記事内容
	 * @return string 目次を追加した記事内容
	 */
	function fit_add_outline( $content ) {
		$type = '';
		if ( is_singular( 'post' ) ) {
			// 投稿
			$type = 'post';
		} elseif ( is_page() ) {
			// 固定ページ
			$type = 'page';
		} else {
			// 投稿でも固定ページでもない場合は処理終了
			return $content;
		}

		// カスタマイザーで目次を表示するが選択されているかどうか
		$outline_switch = '';
		if ( 'post' === $type ) {
			$outline_switch = get_option( 'fit_postOutline_switch' );
		} elseif ( 'page' === $type ) {
			$outline_switch = get_option( 'fit_pageOutline_switch' );
		}

		// カスタマイザーで目次を表示するが選択されている時＆個別非表示設定で目次を非表示にするがチェックされていない時は処理を続行
		if ( $outline_switch === 'on' && get_post_meta( get_the_ID(), 'outline_hide', true ) != '1' ) {
			// 目次を表示するために必要な見出しの数
			$number = 1;
			if ( 'post' === $type ) {
				$number = get_option( 'fit_postOutline_number', 1 );
			} elseif ( 'page' === $type ) {
				$number = get_option( 'fit_pageOutline_number', 1 );
			}

			// 目次関連の情報
			$outline_info = fit_get_outline_info( $content );
			// 記事内容
			$content = $outline_info['content'];
			// 目次
			$outline = $outline_info['outline'];
			// 見出しの数
			$count = $outline_info['count'];

			if ( $outline !== '' && $count >= $number ) {
				// 目次パネルをデフォルトで閉じるかどうか
				$close = '';
				// 検索する見出しのレベルを定義
				$hNumber = '5';
				if ( 'post' === $type ) {
					if ( ! get_option( 'fit_postOutline_close' ) ) {
						$close = 'checked';
					}
					$hNumber = get_option( 'fit_postOutline_hNumber', '5' );
				} elseif ( 'page' === $type ) {
					if ( ! get_option( 'fit_pageOutline_close' ) ) {
						$close = 'checked';
					}
					$hNumber = get_option( 'fit_pageOutline_hNumber', '5' );
				}

				// 目次を装飾します。
				$decorated_outline = sprintf(
					'
					<div class="outline">
					<span class="outline__title">目次</span>
					<input class="outline__toggle" id="outline__toggle" type="checkbox" ' . $close . '>
					<label class="outline__switch" for="outline__toggle"></label>
					%s
					</div>',
					$outline
				);

				$shortcode_outline = '[outline]';
				if ( strpos( $content, $shortcode_outline ) !== false ) {
					// 記事内にショートコードがある場合、ショートコードを目次で置換します。
					$content = str_replace( $shortcode_outline, $decorated_outline, $content );
				} elseif ( preg_match( '/<h[1-' . $hNumber . '].*>/', $content, $matches, PREG_OFFSET_CAPTURE ) ) {
					// 最初のhタグの前に目次を追加します。
					$pos     = $matches[0][1];
					$content = substr( $content, 0, $pos ) . $decorated_outline . substr( $content, $pos );
				}
			}
		} else {
			// 目次非表示の場合はoutlineショートコードを削除する
			$content = str_replace( '[outline]', '', $content );
		}

		return $content;
	}
	add_filter( 'the_content', 'fit_add_outline', 20 );
}

if ( ! function_exists( 'fit_remove_outline_from_excerpt' ) ) {
	/**
	 * 記事の抜粋から目次ショートコードを除去する
	 *
	 * @param string $excerpt 記事の抜粋
	 * @return string 目次ショートコードを除去した記事の抜粋
	 */
	function fit_remove_outline_from_excerpt( $excerpt ) {
		return str_replace( '[outline]', '', $excerpt );
	}
	add_filter( 'get_the_excerpt', 'fit_remove_outline_from_excerpt' );
}
