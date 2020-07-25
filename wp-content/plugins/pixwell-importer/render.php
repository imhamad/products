<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RB_RENDER_IMPORTER' ) ) {
	class RB_RENDER_IMPORTER {

		private static $instance = null;

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		private $creds = array();
		public $demos_path;
		private $filesystem = array();
		public $base_url;
		public $list_demos;

		public function __construct() {

			if ( ! is_admin() ) {
				return;
			}
			$base             = 'theme.php?page=ruby-importer';
			$this->base_url   = wp_nonce_url( $base );
			$this->demos_path = apply_filters( 'rb_importer_demos_path', trailingslashit( plugin_dir_path( __FILE__ ) . 'demos' ) );
			$this->demos_url  = apply_filters( 'rb_importer_demos_url', trailingslashit( plugin_dir_url( __FILE__ ) . 'demos' ) );

			$this->init_filesystem( $this->base_url );
			global $wp_filesystem;
			$this->filesystem = $wp_filesystem;
			$this->create_index();

			$this->list_demos = $this->filesystem->dirlist( $this->demos_path, false, false );
			if ( is_array( $this->list_demos ) ) {
				foreach ( $this->list_demos as $id => $data ) {
					if ( $data['type'] != 'd' ) {
						unset( $this->list_demos[ $id ] );
					}
				}
				uksort( $this->list_demos, 'strcasecmp' );
			}

			$this->render_demos();
		}

		/** filesystem init */
		public function init_filesystem( $url, $method = '', $context = false, $fields = null ) {

			if ( ! empty( $this->creds ) ) {
				return true;
			}

			/** load files */
			require_once ABSPATH . '/wp-admin/includes/template.php';
			require_once ABSPATH . '/wp-includes/pluggable.php';
			require_once ABSPATH . '/wp-admin/includes/file.php';

			if ( false === ( $this->creds = request_filesystem_credentials( $url, '', false, $context, null ) ) ) {
				return;
			}

			if ( ! WP_Filesystem( $this->creds ) ) {
				request_filesystem_credentials( $url, '', true, $context, null );

				return;
			}

			return true;
		}

		/** create index */
		public function create_index() {
			$index_path = trailingslashit( $this->demos_path ) . 'index.php';
			if ( ! file_exists( $index_path ) ) {
				$this->filesystem->put_contents( $index_path, '<?php' . PHP_EOL . '// Silence is golden.', FS_CHMOD_FILE );
			}
		}

		/** get import info */
		public function get_import_info( $directory ) {
			$imported          = get_option( 'rb_imported_demo' );
			$data              = array();
			$data['directory'] = $directory;
			$data['preview']   = $this->demos_url . $directory . '/preview.jpg';
			$data['name']      = apply_filters( 'rb_importer_demo_name', $directory );
			$data['plugins']   = apply_filters( 'rb_importer_demo_plugins', $directory );

			if ( is_array( $imported ) && ! empty( $imported[ $directory ] ) ) {
				$data['imported'] = $imported[ $directory ];
			} else {
				$data['imported'] = 'none';
			}

			return $data;

		}

		/** render */
		public function render_demos() {

			if ( ! current_user_can( 'install_plugins' ) ) {
				wp_die( esc_html__( 'Sorry, you are not allowed to install demos on this site.', 'pixwell-importer' ) );
			}

			if ( empty( $this->list_demos ) || ! is_array( $this->list_demos ) ) {
				wp_die( esc_html__( 'No Demo Data Provided', 'pixwell-importer' ) );
			}

			/** @var $nonce */
			$nonce = wp_create_nonce( 'ruby-nonce' );
			echo '<div class="rb-demos-wrap">';

			do_action( 'rb_importer_header' );

			echo '<div class="rb-demos">';
			foreach ( $this->list_demos as $directory => $info ) :
				$data = $this->get_import_info( $directory );
				if ( ! empty( $data['imported'] ) && is_array( $data['imported'] ) ) {
					$imported       = true;
					$item_classes   = 'rb-demo-item active is-imported';
					$import_message = esc_html__( 'Already Imported', 'pixwell-importer' );
				} else {
					$item_classes   = 'rb-demo-item not-imported';
					$imported       = false;
					$import_message = esc_html__( 'Import Demo', 'pixwell-importer' );
				} ?>
				<div class="<?php echo esc_attr( $item_classes ); ?>" data-directory="<?php echo $directory; ?>" data-nonce="<?php echo $nonce ?>" data-action="rb_importer">
					<div class="inner-item">
						<div class="demo-preview">
							<div class="demo-process-bar"><span class="process-percent"></span></div>
							<img class="demo-image" src="<?php echo esc_html( $data['preview'] ); ?>" alt="<?php esc_attr( $data['name'] ); ?>"/>
							<span class="demo-status"><?php echo esc_html( $import_message ); ?></span>
							<span class="process-count">0%</span>
						</div>
						<div class="demo-content">
							<h3 class="demo-name"><?php echo $data['name']; ?></h3>
							<?php if ( is_array( $data['plugins'] ) ) : ?>
								<div class="demo-plugins">
									<h4><?php esc_html_e( 'Recommended Plugins', 'pixwell-importer' ) ?></h4>
									<?php $this->recommended_plugins( $data['plugins'], $nonce ); ?>
								</div>
							<?php endif;
							$this->data_select( $directory );
							?>
							<div class="import-actions">
								<?php if ( false == $imported ) : ?>
									<div class="rb-importer-btn-wrap">
										<span class="rb-wait"><?php esc_html_e( 'Please Wait...', 'pixwell-importer' ); ?></span>
										<span class="rb-do-import rb-importer-btn rb-disabled"><?php esc_html_e( 'Import Demo', 'pixwell-importer' ) ?></span>
										<span class="rb-importer-completed"><?php esc_html_e( 'Import Complete', 'pixwell-importer' ); ?></span>
									</div>
								<?php else : ?>
									<div class="rb-importer-btn-wrap">
										<span class="rb-wait"><?php esc_html_e( 'Please Wait...', 'pixwell-importer' ); ?></span>
										<span class="rb-do-reimport rb-importer-btn rb-disabled"><?php esc_html_e( 'Re-Import', 'pixwell-importer' ); ?></span>
										<span class="rb-importer-completed"><?php esc_html_e( 'Import Complete', 'pixwell-importer' ); ?></span>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php
			endforeach;
			echo '</div>';
			echo '</div>';
		}

		/** get recommended plugin */
		public function recommended_plugins( $plugins, $nonce = '' ) {

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$site_plugins = get_plugins();

			foreach ( $plugins as $plugin ) {

				if ( empty( $plugin['name'] ) || empty( $plugin['slug'] ) ) {
					continue;
				}

				$classname = 'plugin-el';
				if ( ! empty( $plugin['class'] ) ) {
					$classname .= ' ' . $plugin['class'];
				}

				if ( empty( $plugin['file'] ) ) {
					$plugin_plug = $plugin['slug'] . '/' . $plugin['slug'] . '.php';
				} else {
					$plugin_plug = $plugin['slug'] . '/' . $plugin['file'] . '.php';
				}

				if ( array_key_exists( $plugin_plug, $site_plugins ) ) {

					/** plugin installed */
					echo '<div class="' . esc_attr( $classname ) . ' installed">';
					echo '<span class="name">' . esc_html( $plugin['name'] );
					if ( ! empty( $plugin['info'] ) ) {
						echo '<span class="info">(' . esc_html( $plugin['info'] ) . ')</span>';
					}
					echo '</span>';
					if ( is_plugin_active( $plugin_plug ) ) {
						echo '<span class="activate-info activated">' . esc_html__( 'Activated', 'pixwell-importer' ) . '</span>';
					} else {
						$active_link = wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $plugin_plug ), 'activate-plugin_' . $plugin_plug );
						echo '<a href="' . $active_link . '" class="activate-info activate rb-activate-plugin">' . esc_html__( 'Activate', 'pixwell-importer' ) . '</a>';
					}
					echo '</div>';
				} else {
					/** plugin not install */
					if ( ! empty( $plugin['source'] ) ) {
						$this->install_package( $plugin, $nonce );
					} else {
						$install_link = wp_nonce_url(
							add_query_arg(
								array(
									'action' => 'install-plugin',
									'plugin' => $plugin['slug']
								),
								admin_url( 'update.php' )
							),
							'install-plugin' . '_' . $plugin['slug']
						);

						echo '<div class="' . esc_attr( $classname ) . ' install">';
						echo '<span class="name">' . esc_html( $plugin['name'] );
						if ( ! empty( $plugin['info'] ) ) {
							echo '<span class="info">(' . esc_html( $plugin['info'] ) . ')</span>';
						}
						echo '</span>';
						echo '</span>';
						echo '<a href="' . $install_link . '" class="activate-info activate rb-activate-plugin">' . esc_html__( 'Install', 'pixwell-importer' ) . '</a>';
						echo '</div>';
					}
				}
			}
		}

		/** install package plugin */
		public function install_package( $plugin = array(), $nonce = '' ) {

			$classname = 'plugin-el';
			if ( ! empty( $plugin['class'] ) ) {
				$classname .= ' ' . $plugin['class'];
			}
			echo '<div class="' . esc_attr( $classname ) . ' rb-repackage-plugin">';
			echo '<span class="name">' . esc_html( $plugin['name'] );
			if ( ! empty( $plugin['info'] ) ) {
				echo '<span class="info">(' . esc_html( $plugin['info'] ) . ')</span>';
			}
			echo '</span>';
			echo '</span>';
			echo '<a href="#" class="activate-info activate rb-install-package" data-slug="' . $plugin['slug'] . '" data-action="rb_install_package" data-package="' . base64_encode( $plugin['source'] ) . '" data-nonce="' . $nonce . '">' . esc_html__( 'Install Package', 'pixwell-importer' ) . '</a>';
			echo '</div>';
		}

		/** render data select */
		public function data_select( $directory ) {

			echo '<div class="data-select-wrap">';
			echo '<h3 class="rb-import-header">' . esc_html__( 'Select Content', 'pixwell-importer' ) . '</h3>';
			echo '<div class="data-select">';
			echo '<div class="data-select-el">';
			echo '<a href="#" id="rb-all-' . esc_attr( $directory ) . '" data-title="rb_import_all" class="rb-importer-checkbox rb_import_all" data-checked="0"><span class="import-label">' . esc_html__( 'All Demo Content', 'pixwell-importer' ) . '</span></a>';
			echo '</div>';
			echo '<div class="rb-import-divider"></div>';
			echo '<div class="data-select-el">';
			echo '<a href="#" id="rb-content-' . esc_attr( $directory ) . '" data-title="rb_import_content" class="rb-importer-checkbox rb_import_content" data-checked="0"><span class="import-label">' . esc_html__( 'Content (Posts, Pages &amp; Media)', 'pixwell-importer' ) . '</span></a>';
			echo '</div>';
			echo '<div class="data-select-el">';
			echo '<a href="#" id="rb-page-' . esc_attr( $directory ) . '" data-title="rb_import_pages" class="rb-importer-checkbox rb_import_pages" data-checked="0"><span class="import-label">' . esc_html__( 'Only Pages', 'pixwell-importer' ) . '</span></a>';
			echo '</div>';
			echo '<div class="data-select-el">';
			echo '<a href="#" id="rb-tops-' . esc_attr( $directory ) . '" data-title="rb_import_tops" class="rb-importer-checkbox rb_import_tops" data-checked="0"><span class="import-label">' . esc_html__( 'Theme Options', 'pixwell-importer' ) . '</span></a>';
			echo '</div>';
			echo '<div class="data-select-el">';
			echo '<a href="#" id="rb-widgets-' . esc_attr( $directory ) . '" data-title="rb_import_widgets" class="rb-importer-checkbox rb_import_widgets" data-checked="0"><span class="import-label">' . esc_html__( 'Widgets', 'pixwell-importer' ) . '</span></a>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}
}
