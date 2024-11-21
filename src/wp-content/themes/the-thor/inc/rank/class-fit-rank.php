<?php
/**
 * ランキング機能のクラス用ファイル
 */

if ( ! class_exists( 'Fit_Rank' ) ) {
	/**
	 * アクセス数ランキングの処理や使用するパラメータをまとめたクラス
	 */
	class Fit_Rank {
		const PERIOD_ALL              = 'all';
		const PERIOD_MONTH            = 'month';
		const PERIOD_WEEK             = 'week';
		const PERIOD_DAY              = 'day';
		const META_KEY_ALL            = 'post_views_not_realtime';
		const META_KEY_MONTH          = 'post_views_month';
		const META_KEY_WEEK           = 'post_views_week';
		const META_KEY_DAY            = 'post_views_day';
		const META_KEY_REALTIME_ALL   = 'post_views';
		const META_KEY_REALTIME_MONTH = 'post_views_realtime_month';
		const META_KEY_REALTIME_WEEK  = 'post_views_realtime_week';
		const META_KEY_REALTIME_DAY   = 'post_views_realtime_day';
		const REALTIME_TRUE           = '1';
		const REALTIME_FALSE          = '';

		// ランキング集計の基準に使う現在日時
		private $datetime_now;
		// リアルタイムで集計するかどうか
		public $realtime_flag;
		// 期間別集計の集計開始日時(Y/m/d H:i)
		public $start_datetime;
		// リアルタイムではない期間別集計用データを更新した日(Y/m/d)
		public $update_date;

		/**
		 * コンストラクタ
		 */
		public function __construct() {
			$this->realtime_flag  = get_option( 'fit_bsRank_realtime' );
			$this->start_datetime = get_option( 'fit_bsRank_startDatetime' );
			$this->update_date    = get_option( 'fit_bsRank_updateDate' );
		}

		/**
		 * リアルタイム集計するかどうかを判定する
		 *
		 * @return boolean TRUE:リアルタイム集計する FALSE:リアルタイム集計しない
		 */
		public function is_realtime() {
			return self::REALTIME_TRUE == $this->realtime_flag;
		}

		/**
		 * 集計開始日時を現在日時に更新する
		 *
		 * @return boolean 更新に成功したらTRUE
		 */
		public function set_start_datetime() {
			$now = date_i18n( 'Y/m/d H:i' );
			$ret = update_option( 'fit_bsRank_startDatetime', date_i18n( 'Y/m/d H:i' ) );
			if ( $ret ) {
				$this->start_datetime = $now;
			}
			return $ret;
		}

		/**
		 * 期間別集計データ更新日を更新する
		 *
		 * @param mixed $date 更新する日付
		 * @return boolean 更新に成功したらTRUE
		 */
		public function update_update_date( $date ) {
			try {
				$objectDatetime = new DateTime( $date );
				$date           = $objectDatetime->format( 'Y/m/d' );
			} catch ( Exception $e ) {
				// 日付のフォーマットが正しくない場合は処理終了
				return false;
			}
			$ret = update_option( 'fit_bsRank_updateDate', $date );
			if ( $ret ) {
				$this->update_date = $date;
			}
			return $ret;
		}

		/**
		 * ランキングの集計対象日時(開始)を返す
		 *
		 * @param string $period 集計期間
		 * @return mixed 日時の文字列(Y-m-d H:i:s) エラーがあった場合はFALSE
		 */
		private function get_from_datetime( $period ) {
			try {
				$now  = new DateTime( $this->datetime_now );
				$from = false;

				switch ( $period ) {
					// 月ランキング
					case self::PERIOD_MONTH:
						if ( $this->is_realtime() ) {
							// リアルタイムの場合は現在日時から30日前
							$from = $now->modify( '-30 day' )->format( 'Y-m-d H:i:s' );
						} else {
							// リアルタイムではない場合は前日から30日前
							$from = $now->modify( '-31 day' )->format( 'Y-m-d 00:00:00' );
						}
						break;
					// 週ランキング
					case self::PERIOD_WEEK:
						if ( $this->is_realtime() ) {
							// リアルタイムの場合は現在日時から7日前
							$from = $now->modify( '-7 day' )->format( 'Y-m-d H:i:s' );
						} else {
							// リアルタイムではない場合は前日から7日前
							$from = $now->modify( '-8 day' )->format( 'Y-m-d 00:00:00' );
						}
						break;
					// 日ランキング
					case self::PERIOD_DAY:
						if ( $this->is_realtime() ) {
							// リアルタイムの場合は現在日時から24時間前
							$from = $now->modify( '-24 hour' )->format( 'Y-m-d H:i:s' );
						} else {
							// リアルタイムではない場合は前日の24時間
							$from = $now->modify( '-1 day' )->format( 'Y-m-d 00:00:00' );
						}
						break;
					// 全期間ランキング
					case self::PERIOD_ALL:
						// 全期間の場合は全期間のリアルタイムの値から本日分をマイナスするため本日の値を取得する
						$from = $now->format( 'Y-m-d 00:00:00' );
						break;
				}

				return $from;
			} catch ( Exception $e ) {
				return false;
			}
		}

		/**
		 * ランキングの集計対象日時(終了)を返す
		 *
		 * @return string 日時の文字列(Y-m-d H:i:s)
		 */
		private function get_to_datetime( $period ) {
			$now = new DateTime( $this->datetime_now );
			if ( self::PERIOD_ALL === $period ) {
				// 全期間の場合は全期間のリアルタイムの値から本日分をマイナスするため本日の値を取得する
				return $now->format( 'Y-m-d 23:59:59' );
			} elseif ( $this->is_realtime() ) {
				// リアルタイムの場合は現在日時
				return $now->format( 'Y-m-d H:i:s' );
			} else {
				// リアルタイムではない場合は前日
				return $now->modify( '-1 day' )->format( 'Y-m-d 23:59:59' );
			}
		}

		/**
		 * 日週月ランキングとリアルタイムではない全期間ランキングのアクセス数を更新
		 *
		 * @param mixed $period 期間
		 * @return mixed 更新に成功:TRUE, 引数が不正:FALSE, 更新に失敗:失敗した投稿IDの配列
		 */
		public function update_post_meta_by_period( $period ) {
			if ( self::PERIOD_ALL === $period && $this->is_realtime() ) {
				// 全期間でリアルタイムの場合は処理終了
				return true;
			}
			$this->datetime_now = date_i18n( 'Y-m-d H:i:s' );
			$from_date          = $this->get_from_datetime( $period );
			$to_date            = $this->get_to_datetime( $period );
			$post_accesslog     = new Fit_Post_Accesslog();
			$meta_key           = $this->get_meta_key_id_by_period( $period );
			if ( ! $from_date || ! $meta_key ) {
				return false;
			}
			$post_views = $post_accesslog->get_page_view_list_group_by_post_id( $from_date, $to_date );

			// カスタムフィールドの削除
			$args = array(
				'numberposts' => -1,
				'post_type'   => array(
					'post',
					'aftag',
				),
				'post_status' => 'any',
			);

			$delete_posts = get_posts( $args );
			foreach ( $delete_posts as $delete_post ) {
				delete_post_meta( $delete_post->ID, $meta_key );
			}

			// カスタムフィールドの登録
			$ret = array();
			if ( empty( $post_views ) && self::PERIOD_ALL === $period ) {
				foreach ( $delete_posts as $p ) {
					$value = get_post_meta( $p->ID, self::META_KEY_REALTIME_ALL, true );
					if ( $value > 0 ) {
						if ( ! update_post_meta( $p->ID, $meta_key, $value ) ) {
							array_push( $ret, $p->ID );
						}
					}
				}
			}
			foreach ( $post_views as $key => $value ) {
				if ( self::PERIOD_ALL === $period ) {
					// 全期間の場合はリアルタイムの値から当日分を減算
					$value = intval( get_post_meta( $key, self::META_KEY_REALTIME_ALL, true ) ) - intval( $value );
				}
				if ( $value > 0 ) {
					if ( ! update_post_meta( $key, $meta_key, $value ) ) {
						array_push( $ret, $key );
					}
				}
			}

			return ( count( $ret ) > 0 ) ? $ret : true;
		}

		/**
		 * アクセス数リセット
		 */
		public function clear_post_views() {
			// アクセスログテーブルのレコード削除
			$post_accesslog = new Fit_Post_Accesslog();
			$post_accesslog->delete_all();

			// カスタムフィールドの削除
			$args = array(
				'numberposts' => -1,
				'post_type'   => array(
					'post',
					'aftag',
				),
				'post_status' => 'any',
			);

			$delete_posts = get_posts( $args );
			foreach ( $delete_posts as $delete_post ) {
				delete_post_meta( $delete_post->ID, self::META_KEY_ALL );
				delete_post_meta( $delete_post->ID, self::META_KEY_MONTH );
				delete_post_meta( $delete_post->ID, self::META_KEY_WEEK );
				delete_post_meta( $delete_post->ID, self::META_KEY_DAY );
				delete_post_meta( $delete_post->ID, self::META_KEY_REALTIME_ALL );
				delete_post_meta( $delete_post->ID, self::META_KEY_REALTIME_MONTH );
				delete_post_meta( $delete_post->ID, self::META_KEY_REALTIME_WEEK );
				delete_post_meta( $delete_post->ID, self::META_KEY_REALTIME_DAY );
			}

			// 期間別集計データ更新日時のクリア
			delete_option( 'fit_bsRank_updateDate' );
			$this->update_date = false;
		}

		/**
		 * 期間に対するmeta_keyのIDを返す
		 *
		 * @param mixed $period 期間
		 * @return string 投稿のmeta_keyに指定するIDの文字列
		 */
		public function get_meta_key_id_by_period( $period ) {
			switch ( $period ) {
				case self::PERIOD_ALL:
					if ( $this->is_realtime() ) {
						return self::META_KEY_REALTIME_ALL;
					} else {
						return self::META_KEY_ALL;
					}
				case self::PERIOD_MONTH:
					if ( $this->is_realtime() ) {
						return self::META_KEY_REALTIME_MONTH;
					} else {
						return self::META_KEY_MONTH;
					}
				case self::PERIOD_WEEK:
					if ( $this->is_realtime() ) {
						return self::META_KEY_REALTIME_WEEK;
					} else {
						return self::META_KEY_WEEK;
					}
				case self::PERIOD_DAY:
					if ( $this->is_realtime() ) {
						return self::META_KEY_REALTIME_DAY;
					} else {
						return self::META_KEY_DAY;
					}
				default:
					return '';
			}
		}
	}
}
