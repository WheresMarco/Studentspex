<?php
/**
 * Newsletter admin: subscriber list, delete, CSV export.
 *
 * @package Studentspex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Menu entry under Spexet.
 */
function studentspex_nl_admin_menu() {
	$hook = add_submenu_page( 'studentspex', __( 'Nyhetsbrev', 'studentspex' ), __( 'Nyhetsbrev', 'studentspex' ), 'manage_options', 'studentspex-newsletter', 'studentspex_nl_admin_page' );
	add_action( 'load-' . $hook, 'studentspex_nl_admin_actions' );
}
add_action( 'admin_menu', 'studentspex_nl_admin_menu', 20 );

/**
 * Subscriber list table.
 */
function studentspex_nl_list_table() {
	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
	}

	return new class() extends WP_List_Table {
		/** Constructor. */
		public function __construct() {
			parent::__construct(
				array(
					'singular' => 'subscriber',
					'plural'   => 'subscribers',
					'ajax'     => false,
				)
			);
		}

		/** Columns. */
		public function get_columns() {
			return array(
				'cb'           => '<input type="checkbox">',
				'email'        => __( 'E-post', 'studentspex' ),
				'status'       => __( 'Status', 'studentspex' ),
				'created_at'   => __( 'Registrerad', 'studentspex' ),
				'confirmed_at' => __( 'Bekräftad', 'studentspex' ),
			);
		}

		/** Sortable columns. */
		protected function get_sortable_columns() {
			return array(
				'email'        => array( 'email', false ),
				'created_at'   => array( 'created_at', true ),
				'confirmed_at' => array( 'confirmed_at', false ),
			);
		}

		/** Bulk actions. */
		protected function get_bulk_actions() {
			return array( 'delete' => __( 'Radera permanent', 'studentspex' ) );
		}

		/**
		 * Checkbox column.
		 *
		 * @param object $item Row.
		 */
		protected function column_cb( $item ) {
			return sprintf( '<input type="checkbox" name="ids[]" value="%d">', (int) $item->id );
		}

		/**
		 * Default column.
		 *
		 * @param object $item Row.
		 * @param string $col  Column.
		 */
		protected function column_default( $item, $col ) {
			if ( 'status' === $col ) {
				$labels = array(
					'confirmed'    => __( 'Bekräftad', 'studentspex' ),
					'pending'      => __( 'Väntar på bekräftelse', 'studentspex' ),
					'unsubscribed' => __( 'Avregistrerad', 'studentspex' ),
				);
				return esc_html( isset( $labels[ $item->status ] ) ? $labels[ $item->status ] : $item->status );
			}
			if ( in_array( $col, array( 'created_at', 'confirmed_at' ), true ) ) {
				return $item->$col ? esc_html( get_date_from_gmt( $item->$col, 'Y-m-d H:i' ) ) : '—';
			}
			return esc_html( $item->$col );
		}

		/** Status filter links. */
		protected function get_views() {
			global $wpdb;
			$table   = studentspex_nl_table();
			$counts  = $wpdb->get_results( "SELECT status, COUNT(*) AS n FROM {$table} GROUP BY status", OBJECT_K ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery
			$current = isset( $_GET['status'] ) ? sanitize_key( $_GET['status'] ) : 'confirmed'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$views   = array();
			foreach ( array(
				'confirmed'    => __( 'Bekräftade', 'studentspex' ),
				'pending'      => __( 'Väntar', 'studentspex' ),
				'unsubscribed' => __( 'Avregistrerade', 'studentspex' ),
				'all'          => __( 'Alla', 'studentspex' ),
			) as $key => $label ) {
				$n             = 'all' === $key ? array_sum( wp_list_pluck( $counts, 'n' ) ) : ( isset( $counts[ $key ] ) ? (int) $counts[ $key ]->n : 0 );
				$views[ $key ] = sprintf(
					'<a href="%s"%s>%s <span class="count">(%d)</span></a>',
					esc_url(
						add_query_arg(
							array(
								'page'   => 'studentspex-newsletter',
								'status' => $key,
							),
							admin_url( 'admin.php' )
						)
					),
					$current === $key ? ' class="current" aria-current="page"' : '',
					esc_html( $label ),
					(int) $n
				);
			}
			return $views;
		}

		/** Query. */
		public function prepare_items() {
			global $wpdb;
			$table    = studentspex_nl_table();
			$per_page = 50;
			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			$status  = isset( $_GET['status'] ) ? sanitize_key( $_GET['status'] ) : 'confirmed';
			$search  = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
			$orderby = isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], array( 'email', 'created_at', 'confirmed_at' ), true ) ? sanitize_key( $_GET['orderby'] ) : 'created_at';
			$order   = isset( $_GET['order'] ) && 'asc' === strtolower( sanitize_key( $_GET['order'] ) ) ? 'ASC' : 'DESC';
			// phpcs:enable
			$where = array( '1=%d' );
			$args  = array( 1 );
			if ( 'all' !== $status ) {
				$where[] = 'status = %s';
				$args[]  = $status;
			}
			if ( $search ) {
				$where[] = 'email LIKE %s';
				$args[]  = '%' . $wpdb->esc_like( $search ) . '%';
			}
			$where_sql = implode( ' AND ', $where );
			$offset    = ( $this->get_pagenum() - 1 ) * $per_page;
			// $where_sql only contains placeholders built above; $orderby/$order are allow-listed. The
			// placeholder-count sniff cannot follow array arguments, hence ReplacementsWrongNumber.
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare,WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber
			$total       = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE {$where_sql}", $args ) );
			$this->items = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table} WHERE {$where_sql} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d", array_merge( $args, array( $per_page, $offset ) ) ) );
			// phpcs:enable
			$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
			$this->set_pagination_args(
				array(
					'total_items' => $total,
					'per_page'    => $per_page,
				)
			);
		}
	};
}

/**
 * Handle bulk delete and CSV export before output.
 */
function studentspex_nl_admin_actions() {
	global $wpdb;
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$table = studentspex_nl_table();

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['export'] ) ) {
		check_admin_referer( 'studentspex_nl_export' );
		$rows = $wpdb->get_results( "SELECT id, email, confirmed_at FROM {$table} WHERE status = 'confirmed' ORDER BY confirmed_at ASC" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery
		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=studentspex-nyhetsbrev-' . gmdate( 'Y-m-d' ) . '.csv' );
		$out = fopen( 'php://output', 'w' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		fwrite( $out, "\xEF\xBB\xBF" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite -- UTF-8 BOM so Excel reads å/ä/ö.
		fputcsv( $out, array( 'email', 'confirmed_at', 'unsubscribe_url' ), ';' );
		foreach ( $rows as $row ) {
			fputcsv( $out, array( $row->email, $row->confirmed_at, studentspex_nl_unsubscribe_url( $row->id, $row->email ) ), ';' );
		}
		fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		exit;
	}

	$action = isset( $_POST['action'] ) ? sanitize_key( $_POST['action'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	if ( '-1' === $action || '' === $action ) {
		$action = isset( $_POST['action2'] ) ? sanitize_key( $_POST['action2'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	}
	if ( 'delete' === $action && ! empty( $_POST['ids'] ) ) {
		check_admin_referer( 'bulk-subscribers' );
		$ids = array_map( 'absint', (array) wp_unslash( $_POST['ids'] ) );
		foreach ( $ids as $id ) {
			$wpdb->delete( $table, array( 'id' => $id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		}
		wp_safe_redirect( add_query_arg( 'deleted', count( $ids ), wp_get_referer() ) );
		exit;
	}
}

/**
 * Page output.
 */
function studentspex_nl_admin_page() {
	$table = studentspex_nl_list_table();
	$table->prepare_items();
	$export = wp_nonce_url( admin_url( 'admin.php?page=studentspex-newsletter&export=1' ), 'studentspex_nl_export' );
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Nyhetsbrev', 'studentspex' ); ?></h1>
		<a class="page-title-action" href="<?php echo esc_url( $export ); ?>"><?php esc_html_e( 'Exportera bekräftade (CSV)', 'studentspex' ); ?></a>
		<hr class="wp-header-end">
		<?php if ( isset( $_GET['deleted'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Prenumeranterna är raderade.', 'studentspex' ); ?></p></div>
		<?php endif; ?>
		<p style="max-width:760px"><?php esc_html_e( 'Bara bekräftade adresser (dubbel opt-in) får mejl. När ett nytt spex annonseras: exportera CSV-filen och skicka utskicket som massmejl med mottagarna som dold kopia, eller via koppla dokument. Ta med varje persons avregistreringslänk från kolumnen unsubscribe_url. Obekräftade anmälningar raderas automatiskt efter 30 dagar.', 'studentspex' ); ?></p>
		<?php $table->views(); ?>
		<form method="get">
			<input type="hidden" name="page" value="studentspex-newsletter">
			<?php $table->search_box( __( 'Sök e-post', 'studentspex' ), 'spex-nl' ); ?>
		</form>
		<form method="post">
			<?php $table->display(); ?>
		</form>
	</div>
	<?php
}

/**
 * Suggested privacy policy text (Inställningar → Integritet).
 */
function studentspex_nl_privacy_text() {
	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}
	wp_add_privacy_policy_content(
		__( 'Studentspex nyhetsbrev', 'studentspex' ),
		'<p>' . esc_html__( 'När du prenumererar på vårt nyhetsbrev sparar vi din e-postadress, tidpunkten för registrering och bekräftelse samt texten du gav ditt samtycke till. Adressen används bara för att mejla dig när ett nytt spex annonseras och delas aldrig med någon annan. Obekräftade anmälningar raderas efter 30 dagar. Du kan när som helst avregistrera dig via länken i varje mejl, eller be oss radera dina uppgifter.', 'studentspex' ) . '</p>'
	);
}
add_action( 'admin_init', 'studentspex_nl_privacy_text' );
