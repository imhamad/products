<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** RUBY COMPOSER */
if ( ! class_exists( 'RUBY_COMPOSER' ) ) {
	class RUBY_COMPOSER {

		protected static $instance = null;
		private static $blocks = array();
		private static $tabs = array();

		public function __construct() {
			define( 'RUBY_COMPOSER_VERSION', '1.0' );
			define( 'RUBY_COMPOSER_URL', plugin_dir_url( __FILE__ ) );
			define( 'RUBY_COMPOSER_PATH', plugin_dir_path( __FILE__ ) );

			include_once RUBY_COMPOSER_PATH . 'shortcodes.php';
			include_once RUBY_COMPOSER_PATH . 'templ.php';

			add_action( 'activated_plugin', 'rbc_load_default_templates' );
			add_action( 'init', array( $this, 'load_language' ), 20 );
			add_action( 'add_meta_boxes', array( $this, 'register_composer_meta' ), 0 );
			add_action( 'current_screen', array( $this, 'init' ) );
			add_filter( 'template_include', array( $this, 'frontend_redirect' ), 99 );
			add_filter( 'theme_page_templates', array( $this, 'frontend_template' ), 20 );
			add_filter( 'redirect_canonical', array( $this, 'pagination_redirect' ), 10 );
			add_action( 'save_post', array( $this, 'save_composer' ), 10 );
			add_action( 'save_post', array( $this, 'save_post_content' ), 20, 2 );
			add_action( 'wp_ajax_rbc_submit_template', array( $this, 'submit_template' ) );
			add_action( 'wp_ajax_rbc_delete_template', array( $this, 'delete_template' ) );
			add_action( 'wp_ajax_rbc_load_template', array( $this, 'load_template' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend' ), 10 );
			add_filter( 'display_post_states', array( $this, 'show_composer_state' ) );
		}

		static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function init() {

			global $pagenow;
			if ( ( 'post.php' == $pagenow || 'post-new.php' == $pagenow ) && get_current_screen()->post_type == 'page' ) {

				/** do not load on Elementor page */
				if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'elementor' ) {
					return;
				}

				$this->blocks_loader();
				$this->tab_loader();

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style( 'ruby-composer-style', RUBY_COMPOSER_URL . 'assets/main.css', array(
					'wp-color-picker'
				), RUBY_COMPOSER_VERSION, 'all' );
				wp_register_script( 'ruby-composer-main', RUBY_COMPOSER_URL . 'assets/main.js', array(
					'jquery',
					'wp-color-picker',
					'wp-util'
				), RUBY_COMPOSER_VERSION, true );
				add_action( 'admin_footer', array( $this, 'footer_loader' ), 9999 );
			}
		}

		/** load language */
		public function load_language() {
			load_plugin_textdomain( 'rbc', false, RUBY_COMPOSER_PATH . 'languages/' );
		}

		/** register metabox */
		public function register_composer_meta() {
			$section = array(
				'title'      => esc_html__( 'RUBY COMPOSER', 'rbc' ),
				'context'    => 'normal',
				'post_types' => array( 'page' ),
				'priority'   => 'high',
			);

			add_meta_box( 'rbc-global-meta', $section['title'], array(
				$this,
				'rbc_render_global_panel'
			), $section['post_types'], $section['context'], $section['priority'], $section );
		}

		/** global panel */
		public function rbc_render_global_panel() {
			wp_nonce_field( basename( __FILE__ ), 'rbc_meta_nonce' );
		}


		/** register frontend */
		public function register_frontend() {

			if ( ! is_admin() && is_page_template( 'rbc-frontend.php' ) ) {
				if ( wp_style_is( 'pixwell-core' ) ) {
					wp_add_inline_style( 'pixwell-core', stripslashes( get_post_meta( get_the_ID(), 'rbc_dynamic_style', true ) ) );
				} else {
					wp_enqueue_style( 'rbc-frontend', RUBY_COMPOSER_URL . 'assets/frontend.css', array(), RUBY_COMPOSER_VERSION, 'all' );
					wp_add_inline_style( 'rbc-frontend', stripslashes( get_post_meta( get_the_ID(), 'rbc_dynamic_style', true ) ) );
				}
			}
		}

		/** footer loader */
		public function footer_loader() {

			ob_start();
			include_once RUBY_COMPOSER_PATH . 'templates.php';
			echo ob_get_clean();

			$configs = array(
				'homeURL'       => home_url(),
				'locale'        => get_locale(),
				'pageID'        => get_the_ID(),
				'setupSections' => $this->register_sections(),
				'setupBlocks'   => $this->blocks_filter( self::$blocks ),
				'setupTabs'     => self::$tabs,
				'rbcContent'    => $this->get_raw_data(),
				'templateList'  => $this->get_templates(),
				'confirmDS'     => esc_html__( 'Are you sure want to delete this section?', 'rbc' ),
				'confirmDB'     => esc_html__( 'Are you sure want to delete this block?', 'rbc' ),
				'confirmDT'     => esc_html__( 'Are you sure want to delete this template?', 'rbc' ),
				'confirmAT'     => esc_html__( 'Are you sure want to add this template?', 'rbc' ),
			);

			wp_localize_script( 'ruby-composer-main', 'rbcParams', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			wp_localize_script( 'ruby-composer-main', 'rbcConfigs', wp_json_encode( $configs ) );
			wp_enqueue_script( 'ruby-composer-main' );
		}


		/** get composer data */
		public function get_raw_data() {
			global $post;
			$composer_data = array();

			if ( isset( $post->ID ) && 'rbc-frontend.php' == get_post_meta( $post->ID, '_wp_page_template', true ) ) {
				$composer_data = get_post_meta( $post->ID, 'ruby_composer_data', true );

				/** decode data */
				if ( ! empty( $composer_data ) && is_array( $composer_data ) ) {
					foreach ( $composer_data as $section_id => $section ) {
						if ( ! empty( $section['blocks'] ) && is_array( $section['blocks'] ) ) {
							foreach ( $section['blocks'] as $block_id => $block ) {
								if ( ! empty( $block ) && is_array( $block ) ) {
									foreach ( $block as $field_id => $value ) {
										if ( 'html_' == substr( $field_id, 0, 5 ) || 'shortcode' == $field_id || 'raw_html' == $field_id || 'ad_script' == $field_id ) {
											$value_decode = base64_decode( $value, true );
											if ( $value_decode ) {
												$composer_data[ $section_id ]['blocks'][ $block_id ][ $field_id ] = $value_decode;
											}
										}
									}
								}
							}
						}
					}
				}

				$composer_data = stripslashes_deep( $composer_data );
			}

			return $composer_data;
		}

		/** frontend template */
		public function frontend_redirect( $template ) {

			global $post;
			if ( ! $post ) {
				return $template;
			}

			if ( 'rbc-frontend.php' == get_post_meta( $post->ID, '_wp_page_template', true ) ) {
				$template = RUBY_COMPOSER_PATH . 'rbc-frontend.php';
			}

			return $template;
		}

		/** pagination redirect */
		function pagination_redirect( $redirect_url ) {
			global $wp_query;

			if ( is_page() && ! is_feed() && isset( $wp_query->queried_object ) && get_query_var( 'page' ) && 'rbc-frontend.php' == get_page_template_slug( $wp_query->queried_object->ID ) ) {
				return false;
			}

			return $redirect_url;
		}


		/** register frontend template */
		public function frontend_template( $page_templates ) {
			$page_templates = array_merge( $page_templates, array(
				'rbc-frontend.php' => 'Ruby Composer',
			) );

			return $page_templates;
		}

		/** composer states */
		public function show_composer_state( $states ) {
			global $post;
			global $pagenow;
			if ( 'edit.php' == $pagenow && get_current_screen()->post_type == 'page' ) {
				if ( 'page' == get_post_type( $post->ID ) && 'rbc-frontend.php' == get_post_meta( $post->ID, '_wp_page_template', true ) ) {
					$states[] = esc_html__( 'Ruby Composer', 'rbc' );
				}
			}

			return $states;
		}

		/** register blocks */
		public function blocks_loader() {
			self::$blocks = apply_filters( 'rbc_add_block', self::$blocks );
		}


		/** filter blocks */
		public function blocks_filter( $blocks ) {
			$filter_data = array();

			if ( ! is_array( $blocks ) ) {
				return false;
			}
			foreach ( $blocks as $block ) {
				$block = $this->tabs_filter( $block );
				if ( ! empty( $block['section'] ) ) {
					if ( is_array( $block['section'] ) ) {
						foreach ( $block['section'] as $section ) {
							if ( empty( $filter_data[ $section ] ) ) {
								$filter_data[ $section ] = array();
							}
							$filter_data[ $section ][ $block['name'] ] = $block;
						}
					} else {
						if ( empty( $filter_data[ $block['section'] ] ) ) {
							$filter_data[ $block['section'] ] = array();
						}
						$filter_data[ $block['section'] ][ $block['name'] ] = $block;
					}
				}
			}

			return $filter_data;
		}

		/** filter tabs */
		public function tabs_filter( $block ) {
			$block['tabs'] = array();
			if ( ! empty( $block['inputs'] ) && is_array( $block['inputs'] ) ) {
				foreach ( $block['inputs'] as $field ) {
					if ( ! empty( $field['tab'] ) ) {
						array_push( $block['tabs'], $field['tab'] );
					}
				}
			}

			$block['tabs'] = array_unique( $block['tabs'] );

			return $block;
		}

		/** register tabs */
		public function tab_loader() {

			self::$tabs = array(
				'filter'     => esc_html__( 'Query Filters', 'rbc' ),
				'general'    => esc_html__( 'General', 'rbc' ),
				'header'     => esc_html__( 'Block Header', 'rbc' ),
				'pagination' => esc_html__( 'Pagination', 'rbc' ),
				'design'     => esc_html__( 'Layouts', 'rbc' ),
				'content'    => esc_html__( 'Content', 'rbc' ),
				'advert'     => esc_html__( 'Advertising', 'rbc' ),
				'subsection' => esc_html__( 'Sub Section', 'rbc' ),
			);
			self::$tabs = apply_filters( 'rbc_add_tab', self::$tabs );
		}


		/** register section */
		public function register_sections() {
			return array(
				'fullwidth' => array(
					'type'        => 'fullwidth',
					'title'       => esc_html__( 'FullWidth', 'rbc' ),
					'img'         => RUBY_COMPOSER_URL . 'assets/s-fullwidth.png',
					'description' => esc_html__( 'Display content without sidebar', 'rbc' ),
					'inputs'      => array(
						array(
							'name'        => 'layout',
							'type'        => 'select',
							'title'       => esc_html__( 'Section Layout', 'rbc' ),
							'description' => esc_html__( 'Select layout for this section', 'rbc' ),
							'options'     => array(
								'full'      => esc_html__( 'FullWidth (Inner Content: 1200px)', 'rbc' ),
								'wrapper'   => esc_html__( 'Wrapper (Boxed Section and Inner Content: 1200px )', 'rbc' ),
								'stretched' => esc_html__( 'FullWidth Stretched (Full-Wide Content: 100%)', 'rbc' ),
							),
							'default'     => 'full'
						),
						array(
							'name'        => 'margin',
							'type'        => 'dimension',
							'title'       => esc_html__( 'Margin', 'rbc' ),
							'description' => esc_html__( 'Select margin top and bottom values (in px) for this section, default is 50px', 'rbc' ),
							'default'     => array(
								'top'    => 0,
								'bottom' => 50,
							),
						),
						array(
							'name'        => 'padding',
							'type'        => 'dimension',
							'title'       => esc_html__( 'Padding', 'rbc' ),
							'description' => esc_html__( 'Select padding value (in px) for this section, default is 0', 'rbc' ),
							'default'     => array(
								'top'    => 0,
								'right'  => 0,
								'bottom' => 0,
								'left'   => 0
							),
						),
						array(
							'name'        => 'mobile_margin',
							'type'        => 'dimension',
							'title'       => esc_html__( 'Mobile - Margin', 'rbc' ),
							'description' => esc_html__( 'Select margin top and bottom values (in px) for this section in mobile devices, default is 35px', 'rbc' ),
							'default'     => array(
								'top'    => 0,
								'bottom' => 35,
							),
						),
						array(
							'name'        => 'mobile_padding',
							'type'        => 'dimension',
							'title'       => esc_html__( 'Mobile - Padding', 'rbc' ),
							'description' => esc_html__( 'Select padding value (in px) for this section in mobile devices, default is 0', 'rbc' ),
							'default'     => array(
								'top'    => 0,
								'right'  => 0,
								'bottom' => 0,
								'left'   => 0
							),
						),
						array(
							'name'        => 'bg_color',
							'type'        => 'color',
							'title'       => esc_html__( 'Background Color', 'rbc' ),
							'description' => esc_html__( 'Select background color for this section', 'rbc' ),
							'default'     => '',
						),
						array(
							'name'        => 'bg_image',
							'type'        => 'text',
							'title'       => esc_html__( 'Background Image', 'rbc' ),
							'description' => esc_html__( 'Select background image for this section, allow attachment image URL (.png or .jpg at the end)', 'rbc' ),
							'default'     => '',
						),
						array(
							'name'        => 'bg_display',
							'type'        => 'select',
							'title'       => esc_html__( 'Background Image Display', 'rbc' ),
							'description' => esc_html__( 'Select background display for this section (if you use the background image)', 'rbc' ),
							'options'     => array(
								'cover'   => esc_html__( 'Cover', 'rbc' ),
								'pattern' => esc_html__( 'Pattern', 'rbc' ),
							),
							'default'     => 'cover'
						),
						array(
							'name'        => 'bg_position',
							'type'        => 'select',
							'title'       => esc_html__( 'Background Image Position', 'rbc' ),
							'description' => esc_html__( 'Select background position for this section (if you use the background image)', 'rbc' ),
							'options'     => array(
								'center' => esc_html__( 'Center', 'rbc' ),
								'top'    => esc_html__( 'Top', 'rbc' ),
								'bottom' => esc_html__( 'Bottom', 'rbc' )
							),
							'default'     => 'center'
						),
					),
				),
				'content'   => array(
					'type'        => 'content',
					'title'       => esc_html__( 'Content with Sidebar', 'rbc' ),
					'img'         => RUBY_COMPOSER_URL . 'assets/s-content.png',
					'description' => esc_html__( 'Display section content with sidebar', 'rbc' ),
					'inputs'      => array(
						array(
							'name'        => 'margin',
							'type'        => 'dimension',
							'title'       => esc_html__( 'Margin', 'rbc' ),
							'description' => esc_html__( 'Select margin top and bottom values (in px) for this section, default is 50px', 'rbc' ),
							'default'     => array(
								'top'    => 0,
								'bottom' => 50,
							),
						),
						array(
							'name'        => 'padding',
							'type'        => 'dimension',
							'title'       => esc_html__( 'Padding', 'rbc' ),
							'description' => esc_html__( 'Select padding value (in px) for this section, default is 0', 'rbc' ),
							'default'     => array(
								'top'    => 0,
								'right'  => 0,
								'bottom' => 0,
								'left'   => 0
							),
						),
						array(
							'name'        => 'mobile_margin',
							'type'        => 'dimension',
							'title'       => esc_html__( 'Mobile - Margin', 'rbc' ),
							'description' => esc_html__( 'Select margin top and bottom values (in px) for this section in mobile devices, default is 35px', 'rbc' ),
							'default'     => array(
								'top'    => 0,
								'bottom' => 35,
							),
						),
						array(
							'name'        => 'mobile_padding',
							'type'        => 'dimension',
							'title'       => esc_html__( 'Mobile - Padding', 'rbc' ),
							'description' => esc_html__( 'Select padding value (in px) for this section in mobile devices, default is 0', 'rbc' ),
							'default'     => array(
								'top'    => 0,
								'right'  => 0,
								'bottom' => 0,
								'left'   => 0
							)
						),
						array(
							'name'        => 'bg_color',
							'type'        => 'color',
							'title'       => esc_html__( 'Background Color', 'rbc' ),
							'description' => esc_html__( 'Select background color for this section', 'rbc' ),
							'default'     => '',
						),
						array(
							'name'        => 'bg_image',
							'type'        => 'text',
							'title'       => esc_html__( 'Background Image', 'rbc' ),
							'description' => esc_html__( 'Select background image for this section, allow attachment image URL (.png or .jpg at the end)', 'rbc' ),
							'default'     => '',
						),
						array(
							'name'        => 'bg_display',
							'type'        => 'select',
							'title'       => esc_html__( 'Background Image Display', 'rbc' ),
							'description' => esc_html__( 'Select background display for this section (if you use the background image)', 'rbc' ),
							'options'     => array(
								'cover'   => esc_html__( 'Cover', 'rbc' ),
								'pattern' => esc_html__( 'Pattern', 'rbc' ),
							),
							'default'     => 'cover'
						),
						array(
							'name'        => 'bg_position',
							'type'        => 'select',
							'title'       => esc_html__( 'Background Image Position', 'rbc' ),
							'description' => esc_html__( 'Select background position for this section (if you use the background image)', 'rbc' ),
							'options'     => array(
								'center' => esc_html__( 'Center', 'rbc' ),
								'top'    => esc_html__( 'Top', 'rbc' ),
								'bottom' => esc_html__( 'Bottom', 'rbc' )
							),
							'default'     => 'center'
						),
					),
				),
			);
		}


		/**
		 * @return bool
		 * save_composer
		 */
		public function save_composer() {

			if ( empty( $_POST['post_ID'] ) ) {
				return false;
			}

			$post_id     = esc_attr( $_POST['post_ID'] );
			$is_autosave = wp_is_post_autosave( $post_id );
			$is_revision = wp_is_post_revision( $post_id );

			if ( ! empty( $_POST['rbc_meta_nonce'] ) && wp_verify_nonce( $_POST['rbc_meta_nonce'], basename( __FILE__ ) ) ) {
				$is_valid_nonce = true;
			} else {
				$is_valid_nonce = false;
			}

			if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
				return false;
			}

			$composer_data = $this->save_composer_meta();
			$this->generate_style( $post_id, $composer_data );

			return false;
		}

		/**
		 * @return bool
		 * save composer
		 */
		public function save_composer_meta() {

			if ( empty( $_POST['post_type'] ) || 'page' != $_POST['post_type'] || ( ! empty( $_POST['action'] ) && 'inline-save' == $_POST['action'] ) ) {
				return false;
			}

			if ( empty( $_POST['rbc_js_loaded'] ) ) {
				return false;
			}

			$page_id = esc_attr( $_POST['post_ID'] );
			if ( ! isset( $_POST['rbc_section_order'] ) && 'rbc-frontend.php' == get_post_meta( $page_id, '_wp_page_template', true ) ) {
				delete_post_meta( $page_id, 'ruby_composer_data' );

				return false;
			};

			if ( ! isset( $_POST['rbc_section_order'] ) || ! is_array( $_POST['rbc_section_order'] ) ) {
				return false;
			}

			$composer_data = $this->process_data( $_POST );
			update_post_meta( $page_id, 'ruby_composer_data', $composer_data );

			return $composer_data;
		}


		/**
		 * @param        $value
		 * @param string $field_id
		 *
		 * @return array|string
		 * sanitize_input
		 */
		public function sanitize_input( $value, $field_id = null ) {

			if ( 'html_' == substr( $field_id, 0, 5 ) || 'shortcode' == $field_id || 'raw_html' == $field_id || 'ad_script' == $field_id ) {
				$value = stripslashes( $value );

				return base64_encode( $value );
			} else {
				if ( is_array( $value ) ) {
					return array_map( 'sanitize_text_field', $value );
				} else {
					return sanitize_text_field( $value );
				}
			}
		}


		/**
		 * @param $input_data
		 *
		 * @return array
		 * handle input data
		 */
		public function process_data( $input_data ) {

			$composer_data = array();

			$sections = $this->sanitize_input( $input_data['rbc_section_order'] );
			foreach ( $sections as $section_id ) {

				$composer_data[ $section_id ]           = array();
				$composer_data[ $section_id ]['uuid']   = $section_id;
				$composer_data[ $section_id ]['blocks'] = array();

				if ( ! empty( $input_data['rbc_section'][ $section_id ]['type'] ) ) {
					$composer_data[ $section_id ]['type'] = $this->sanitize_input( $input_data['rbc_section'][ $section_id ]['type'] );
				}

				if ( is_array( $input_data['rbc_section'][ $section_id ] ) ) {
					foreach ( $input_data['rbc_section'][ $section_id ] as $name => $val ) {
						$name                                  = esc_attr( $name );
						$val                                   = $this->sanitize_input( $val );
						$composer_data[ $section_id ][ $name ] = $val;
					}
				}

				if ( ! empty( $input_data['rbc_sidebar'][ $section_id ] ) ) {
					$composer_data[ $section_id ]['sidebar_name']   = $this->sanitize_input( $input_data['rbc_sidebar'][ $section_id ]['name'] );
					$composer_data[ $section_id ]['sidebar_pos']    = $this->sanitize_input( $input_data['rbc_sidebar'][ $section_id ]['position'] );
					$composer_data[ $section_id ]['sidebar_sticky'] = $this->sanitize_input( $input_data['rbc_sidebar'][ $section_id ]['sticky'] );
				}

				if ( ! empty( $input_data['rbc_block_order'][ $section_id ] ) && is_array( $input_data['rbc_block_order'][ $section_id ] ) ) {
					$blocks = $this->sanitize_input( $input_data['rbc_block_order'][ $section_id ] );

					foreach ( $blocks as $block_id ) {
						$composer_data[ $section_id ]['blocks'][ $block_id ]         = array();
						$composer_data[ $section_id ]['blocks'][ $block_id ]['uuid'] = $block_id;

						if ( ! empty( $input_data['rbc_block_name'][ $block_id ] ) ) {
							$composer_data[ $section_id ]['blocks'][ $block_id ]['name'] = $this->sanitize_input( $input_data['rbc_block_name'][ $block_id ] );
						}
						if ( ! empty( $input_data['rbc_option'][ $block_id ] ) && is_array( $input_data['rbc_option'][ $block_id ] ) ) {

							$options = $input_data['rbc_option'][ $block_id ];
							foreach ( $options as $name => $val ) {
								$composer_data[ $section_id ]['blocks'][ $block_id ][ esc_attr( $name ) ] = $this->sanitize_input( $val, $name );
							}
						}
					}
				}
			}

			return $composer_data;
		}


		/**
		 * submit template
		 */
		public function submit_template() {

			if ( empty( $_POST['rbc_template_name'] ) ) {
				return false;
			}

			$name         = esc_attr( trim( $_POST['rbc_template_name'] ) );
			$db_templates = get_option( '_rbc_templates', array() );
			if ( empty( $db_templates ) || ! is_array( $db_templates ) ) {
				$db_templates = array();
			}
			$db_templates[ $name ] = $this->process_data( $_POST );
			update_option( '_rbc_templates', $db_templates );

			wp_send_json( array_keys( $db_templates ) );
		}


		/**
		 * delete template
		 */
		public function delete_template() {

			if ( empty( $_POST['name'] ) ) {
				return false;
			}

			$name         = esc_attr( $_POST['name'] );
			$db_templates = get_option( '_rbc_templates' );
			if ( isset( $db_templates[ $name ] ) ) {
				unset ( $db_templates[ $name ] );
			}
			update_option( '_rbc_templates', $db_templates );

			wp_send_json( array_keys( $db_templates ) );
		}

		/** get db templates */
		public function get_templates() {
			$db_templates = get_option( '_rbc_templates' );
			if ( is_array( $db_templates ) ) {
				return array_keys( $db_templates );
			} else {
				return false;
			}
		}


		/** load template */
		public function load_template() {

			if ( empty( $_POST['name'] ) ) {
				return false;
			}

			$name         = esc_attr( $_POST['name'] );
			$db_templates = get_option( '_rbc_templates' );

			if ( isset( $db_templates[ $name ] ) ) {
				$composer_data = $db_templates[ $name ];

				if ( ! empty( $composer_data ) && is_array( $composer_data ) ) {
					foreach ( $composer_data as $section_id => $section ) {
						if ( ! empty( $section['blocks'] ) && is_array( $section['blocks'] ) ) {
							foreach ( $section['blocks'] as $block_id => $block ) {
								if ( ! empty( $block ) && is_array( $block ) ) {
									foreach ( $block as $field_id => $value ) {
										if ( 'html_' == substr( $field_id, 0, 5 ) || 'shortcode' == $field_id || 'raw_html' == $field_id || 'ad_script' == $field_id ) {
											$value_decode = base64_decode( $value, true );
											if ( $value_decode ) {
												$composer_data[ $section_id ]['blocks'][ $block_id ][ $field_id ] = $value_decode;
											}
										}
									}
								}
							}
						}
					}
				}

				wp_send_json( $composer_data );
			}

			wp_send_json( '' );
			die;
		}


		/**
		 * @param $data
		 *
		 * @return bool|string
		 * generate shortcode
		 */
		static function generate_shortcodes( $data = null ) {

			if ( ! isset( $data ) ) {
				$data = get_post_meta( get_the_ID(), 'ruby_composer_data', true );
			}

			$composer_shortcode = '';
			if ( ! is_array( $data ) ) {
				return false;
			}

			foreach ( $data as $section ) {

				$blocks_shortcodes = '';
				$section_params    = '';

				if ( empty( $section['type'] ) || ! is_array( $section ) ) {
					continue;
				}

				$name_shortcode = 'rbc_sec_' . trim( $section['type'] );
				unset( $section['type'] );

				unset( $section['margin']['top'] );
				unset( $section['margin']['right'] );
				unset( $section['margin']['bottom'] );
				unset( $section['margin']['left'] );
				unset( $section['padding']['top'] );
				unset( $section['padding']['right'] );
				unset( $section['padding']['bottom'] );
				unset( $section['padding']['left'] );
				unset( $section['mobile_margin']['top'] );
				unset( $section['mobile_margin']['right'] );
				unset( $section['mobile_margin']['bottom'] );
				unset( $section['mobile_margin']['left'] );
				unset( $section['mobile_padding']['top'] );
				unset( $section['mobile_padding']['right'] );
				unset( $section['mobile_padding']['bottom'] );
				unset( $section['mobile_padding']['left'] );
				unset( $section['bg_color'] );
				unset( $section['bg_display'] );
				unset( $section['bg_position'] );
				unset( $section['bg_image'] );

				if ( ! empty( $section['blocks'] ) ) {
					foreach ( $section['blocks'] as $block ) {
						$blocks_shortcodes .= self::generate_block_shortcode( $block );
					}
					unset( $section['blocks'] );
				}

				foreach ( $section as $key => $data ) {
					if ( is_array( $data ) ) {
						foreach ( $data as $data_key => $data_val ) {
							$section_params .= ' ' . $key . '_' . $data_key . '="' . $data_val . '"';
						}
					} else {
						$section_params .= ' ' . $key . '="' . $data . '"';
					}
				}

				$composer_shortcode .= '[' . $name_shortcode . $section_params . ']' . $blocks_shortcodes . '[/' . $name_shortcode . ']';

			}

			return $composer_shortcode;
		}

		/** generate shortcodes */
		static function generate_block_shortcode( $block ) {

			if ( empty( $block['name'] ) ) {
				return false;
			}

			$block_params   = '';
			$shortcode_name = 'rbc_' . $block['name'];
			unset( $block['name'] );

			unset( $block['margin']['top'] );
			unset( $block['margin']['right'] );
			unset( $block['margin']['bottom'] );
			unset( $block['margin']['left'] );

			unset( $block['mobile_margin']['top'] );
			unset( $block['mobile_margin']['right'] );
			unset( $block['mobile_margin']['bottom'] );
			unset( $block['mobile_margin']['left'] );

			unset( $block['padding']['top'] );
			unset( $block['padding']['right'] );
			unset( $block['padding']['bottom'] );
			unset( $block['padding']['left'] );
			unset( $block['mobile_padding']['top'] );
			unset( $block['mobile_padding']['right'] );
			unset( $block['mobile_padding']['bottom'] );
			unset( $block['mobile_padding']['left'] );
			unset( $block['bg_color'] );
			unset( $block['bg_image'] );
			unset( $block['bg_display'] );
			unset( $block['bg_position'] );

			if ( ! empty( $block['raw_html'] ) ) {
				$content = $block['raw_html'];
				unset ( $block['raw_html'] );
			}

			foreach ( $block as $key => $data ) {
				if ( is_array( $data ) ) {
					$key_type = substr( $key, 0, 10 );
					switch ( $key_type ) {
						case 'categories' :
							$block_params .= ' ' . $key . '="' . implode( ',', $data ) . '"';
							break;
						default :
							foreach ( $data as $data_key => $data_val ) {
								$block_params .= ' ' . $key . '_' . $data_key . '="' . $data_val . '"';
							}
					}
					continue;
				}

				$block_params .= ' ' . $key . '="' . $data . '"';
			}

			if ( ! empty( $content ) ) {
				$block_shortcode = '[' . $shortcode_name . $block_params . ']' . $content . '[/' . $shortcode_name . ']';
			} else {
				$block_shortcode = '[' . $shortcode_name . $block_params . ']';
			}

			return $block_shortcode;
		}

		/** post content */
		public function save_post_content( $page_id, $post ) {

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return false;
			}

			$post_type = get_post_type_object( $post->post_type );

			if ( 'page' != $post->post_type || ! current_user_can( $post_type->cap->edit_post, $page_id ) || 'rbc-frontend.php' != get_post_meta( $page_id, '_wp_page_template', true ) ) {
				return false;
			}

			$composer_data = get_post_meta( $page_id, 'ruby_composer_data', true );
			$this_post     = array(
				'ID'           => $page_id,
				'post_content' => sanitize_post_field( 'post_content', self::generate_shortcodes( $composer_data ), $page_id, 'db' ),
			);

			remove_action( 'save_post', array( $this, 'save_post_content' ), 20 );
			wp_update_post( $this_post );
			add_action( 'save_post', array( $this, 'save_post_content' ), 20, 2 );

			return false;
		}


		/** generate css */
		public function generate_style( $page_id, $data ) {

			$str = '';
			if ( empty( $data ) || ! is_array( $data ) ) {
				return false;
			}

			foreach ( $data as $section ) {

				if ( empty( $section['uuid'] ) ) {
					continue;
				}

				$str .= '#' . $section['uuid'] . '{';
				if ( isset( $section['margin']['top'] ) && '' !== $section['margin']['top'] ) {
					$str .= 'margin-top:' . intval( $section['margin']['top'] ) . 'px;';
				}
				if ( isset( $section['margin']['bottom'] ) && '' !== $section['margin']['bottom'] ) {
					$str .= 'margin-bottom:' . intval( $section['margin']['bottom'] ) . 'px;';
				}
				if ( isset( $section['padding']['top'] ) && '' !== $section['padding']['top'] ) {
					$str .= 'padding-top:' . intval( $section['padding']['top'] ) . 'px;';
				}
				if ( isset( $section['padding']['right'] ) && '' !== $section['padding']['right'] ) {
					$str .= 'padding-right:' . intval( $section['padding']['right'] ) . 'px;';
				}
				if ( isset( $section['padding']['bottom'] ) && '' !== $section['padding']['bottom'] ) {
					$str .= 'padding-bottom:' . intval( $section['padding']['bottom'] ) . 'px;';
				}
				if ( isset( $section['padding']['left'] ) && '' !== $section['padding']['left'] ) {
					$str .= 'padding-left:' . intval( $section['padding']['left'] ) . 'px;';
				}

				if ( ! empty( $section['bg_color'] ) ) {
					$str .= 'background-color:' . $section['bg_color'] . ';';
				}
				if ( ! empty( $section['bg_image'] ) ) {
					$str .= 'background-image: url(' . $section['bg_image'] . ');';

					if ( ! empty( $section['bg_display'] ) && $section['bg_display'] == 'cover' ) {
						$str .= 'background-size: cover;';
						$str .= 'background-repeat: no-repeat;';
					} else {
						$str .= 'background-size: contain;';
						$str .= 'background-repeat: repeat;';
					}
					if ( ! empty( $section['bg_position'] ) ) {
						switch ( $section['bg_position'] ) {
							case 'top' :
								$str .= 'background-position: top center;';
								break;
							case 'bottom' :
								$str .= 'background-position: bottom center;';
								break;
							default :
								$str .= 'background-position: center center;';
						}
					}
				}

				$str .= '}';
				$str .= '@media only screen and (max-width: 991px) {';
				$str .= '#' . $section['uuid'] . '{';

				if ( isset( $section['mobile_margin']['top'] ) && '' !== $section['mobile_margin']['top'] ) {
					$str .= 'margin-top:' . intval( $section['mobile_margin']['top'] ) . 'px;';
				}
				if ( isset( $section['mobile_margin']['bottom'] ) && '' !== $section['mobile_margin']['bottom'] ) {
					$str .= 'margin-bottom:' . intval( $section['mobile_margin']['bottom'] ) . 'px;';
				}
				if ( isset( $section['mobile_padding']['top'] ) && '' !== $section['mobile_padding']['top'] ) {
					$str .= 'padding-top:' . intval( $section['mobile_padding']['top'] ) . 'px;';
				}
				if ( isset( $section['mobile_padding']['right'] ) && '' !== $section['mobile_padding']['right'] ) {
					$str .= 'padding-right:' . intval( $section['mobile_padding']['right'] ) . 'px;';
				}
				if ( isset( $section['mobile_padding']['bottom'] ) && '' !== $section['mobile_padding']['bottom'] ) {
					$str .= 'padding-bottom:' . intval( $section['mobile_padding']['bottom'] ) . 'px;';
				}
				if ( isset( $section['mobile_padding']['left'] ) && '' !== $section['mobile_padding']['left'] ) {
					$str .= 'padding-left:' . intval( $section['mobile_padding']['left'] ) . 'px;';
				}
				$str .= '}}';

				if ( ! empty( $section['blocks'] ) && is_array( $section['blocks'] ) ) {
					foreach ( $section['blocks'] as $block ) {
						if ( empty( $block['uuid'] ) ) {
							continue;
						}
						$str .= '#' . $block['uuid'] . '{';
						if ( isset( $block['margin']['top'] ) && '' !== $block['margin']['top'] ) {
							$str .= 'margin-top:' . intval( $block['margin']['top'] ) . 'px;';
						}
						if ( isset( $block['margin']['right'] ) && '' !== $block['margin']['right'] ) {
							$str .= 'margin-right:' . intval( $block['margin']['right'] ) . 'px;';
						}
						if ( isset( $block['margin']['bottom'] ) && '' !== $block['margin']['bottom'] ) {
							$str .= 'margin-bottom:' . intval( $block['margin']['bottom'] ) . 'px;';
						}
						if ( isset( $block['margin']['left'] ) && '' !== $block['margin']['left'] ) {
							$str .= 'margin-left:' . intval( $block['margin']['left'] ) . 'px;';
						}
						if ( isset( $block['padding']['top'] ) && '' !== $block['padding']['top'] ) {
							$str .= 'padding-top:' . intval( $block['padding']['top'] ) . 'px;';
						}
						if ( isset( $block['padding']['right'] ) && '' !== $block['padding']['right'] ) {
							$str .= 'padding-right:' . intval( $block['padding']['right'] ) . 'px;';
						}
						if ( isset( $block['padding']['bottom'] ) && '' !== $block['padding']['bottom'] ) {
							$str .= 'padding-bottom:' . intval( $block['padding']['bottom'] ) . 'px;';
						}
						if ( isset( $block['padding']['left'] ) && '' !== $block['padding']['left'] ) {
							$str .= 'padding-left:' . intval( $block['padding']['left'] ) . 'px;';
						}
						if ( ! empty( $block['bg_color'] ) ) {
							$str .= 'background-color:' . $block['bg_color'] . ';';
						}
						if ( ! empty( $block['bg_image'] ) ) {
							$str .= 'background-image: url(' . $block['bg_image'] . ');';

							if ( ! empty( $block['bg_display'] ) && $block['bg_display'] == 'cover' ) {
								$str .= 'background-size: cover;';
								$str .= 'background-repeat: no-repeat;';
							} else {
								$str .= 'background-size: contain;';
								$str .= 'background-repeat: repeat;';
							}
							if ( ! empty( $section['bg_position'] ) ) {
								switch ( $section['bg_position'] ) {
									case 'top' :
										$str .= 'background-position: top center;';
										break;
									case 'bottom' :
										$str .= 'background-position: bottom center;';
										break;
									default :
										$str .= 'background-position: center center;';
								}
							}
						}
						$str .= '}';

						if ( ! empty( $block['header_color'] ) ) {
							$str .= '.block-header-4 #' . $block['uuid'] . ' .block-title,';
							$str .= '.block-header-3 #' . $block['uuid'] . ' .block-title:before,';
							$str .= '.block-header-5 #' . $block['uuid'] . ' .block-title:before,';
							$str .= '.block-header-5 #' . $block['uuid'] . ' .block-title:after {';
							$str .= 'background-color: ' . esc_attr( $block['header_color'] ) . ';';
							$str .= '}';

							$str .= '.block-header-dot #' . $block['uuid'] . ' .block-title,';
							$str .= '.block-header-1 #' . $block['uuid'] . ' .block-title,';
							$str .= '.block-header-2 #' . $block['uuid'] . ' .block-title, ';
							$str .= '.block-header-7 #' . $block['uuid'] . ' .block-title:first-letter {';
							$str .= 'color: ' . esc_attr( $block['header_color'] ) . '; }';

							$str .= '.block-header-6 #' . $block['uuid'] . ' .block-title:before {';
							$str .= 'color: ' . esc_attr( $block['header_color'] ) . '; opacity: .5 }';
						}

						if ( ! empty( $block['sub_header_color'] ) ) {
							$str .= ' #' . $block['uuid'] . ' .sub-header';
							$str .= '{ color: ' . esc_attr( $block['sub_header_color'] ) . '; }';
						}

						if ( ! empty( $block['form_bg_color'] ) ) {
							$str .= '#' . $block['uuid'] . '.is-bg-style .sbox-form input[type="search"]{';
							$str .= 'background: ' . esc_attr( $block['form_bg_color'] ) . ';';
							$str .= '}';
						}

						if ( ! empty( $block['form_text_color'] ) ) {
							$str .= '#' . $block['uuid'] . '.is-bg-style .sbox-form {';
							$str .= 'color: ' . esc_attr( $block['form_text_color'] ) . ';';
							$str .= '}';
						}

						if ( ! empty( $block['form_border_radius'] ) ) {
							$str .= '#' . $block['uuid'] . ' .sbox-form input[type="search"]{';
							$str .= 'border-radius: ' . esc_attr( $block['form_border_radius'] ) . 'px;';
							$str .= '-webkit-border-radius: ' . esc_attr( $block['form_border_radius'] ) . 'px;';
							$str .= '}';
						}

						if ( ! empty( $block['btn_1_color'] ) ) {
							$str .= '#' . esc_attr( $block['uuid'] ) . ' .cta-btn-1 {';
							$str .= 'color: ' . esc_attr( $block['btn_1_color'] ) . ';';
							$str .= 'border-color: ' . esc_attr( $block['btn_1_color'] ) . ';';
							$str .= '}';
						}
						if ( ! empty( $block['btn_2_color'] ) ) {
							$str .= '#' . esc_attr( $block['uuid'] ) . ' .cta-btn-2 {';
							$str .= 'color: ' . esc_attr( $block['btn_2_color'] ) . ';';
							$str .= 'border-color: ' . esc_attr( $block['btn_2_color'] ) . ';';
							$str .= '}';
						}
						if ( ! empty( $block['btn_1_bg'] ) ) {
							$str .= '#' . esc_attr( $block['uuid'] ) . ' .cta-btn-1 {';
							$str .= 'background: ' . esc_attr( $block['btn_1_bg'] ) . ';';
							$str .= '}';
						}
						if ( ! empty( $block['btn_2_bg'] ) ) {
							$str .= '#' . esc_attr( $block['uuid'] ) . ' .cta-btn-2 {';
							$str .= 'background: ' . esc_attr( $block['btn_2_bg'] ) . ';';
							$str .= '}';
						}
						if ( ! empty( $block['btn_1_hover_color'] ) ) {
							$str .= '#' . esc_attr( $block['uuid'] ) . ' .cta-btn-1:hover {';
							$str .= 'color: ' . esc_attr( $block['btn_1_hover_color'] ) . ';';
							$str .= '}';
						}
						if ( ! empty( $block['btn_2_hover_color'] ) ) {
							$str .= '#' . esc_attr( $block['uuid'] ) . ' .cta-btn-2:hover {';
							$str .= 'color: ' . esc_attr( $block['btn_2_hover_color'] ) . ';';
							$str .= '}';
						}
						if ( ! empty( $block['btn_1_hover_bg'] ) ) {
							$str .= '#' . esc_attr( $block['uuid'] ) . ' .cta-btn-1:hover {';
							$str .= 'background: ' . esc_attr( $block['btn_1_hover_bg'] ) . ';';
							$str .= 'border-color: ' . esc_attr( $block['btn_1_hover_bg'] ) . ';';
							$str .= '}';
						}
						if ( ! empty( $block['btn_2_hover_bg'] ) ) {
							$str .= '#' . esc_attr( $block['uuid'] ) . ' .cta-btn-2:hover {';
							$str .= 'background: ' . esc_attr( $block['btn_2_hover_bg'] ) . ';';
							$str .= 'border-color: ' . esc_attr( $block['btn_2_hover_bg'] ) . ';';
							$str .= '}';
						}

						$str .= '@media only screen and (max-width: 991px) {';
						$str .= '#' . $block['uuid'] . '{';
						if ( isset( $block['mobile_margin']['top'] ) && '' !== $block['mobile_margin']['top'] ) {
							$str .= 'margin-top:' . intval( $block['mobile_margin']['top'] ) . 'px;';
						}

						if ( isset( $block['mobile_margin']['right'] ) && '' !== $block['mobile_margin']['right'] ) {
							$str .= 'margin-right:' . intval( $block['mobile_margin']['right'] ) . 'px;';
						}
						if ( isset( $block['mobile_margin']['bottom'] ) && '' !== $block['mobile_margin']['bottom'] ) {
							$str .= 'margin-bottom:' . intval( $block['mobile_margin']['bottom'] ) . 'px;';
						}
						if ( isset( $block['mobile_margin']['left'] ) && '' !== $block['mobile_margin']['left'] ) {
							$str .= 'margin-left:' . intval( $block['mobile_margin']['left'] ) . 'px;';
						}
						if ( isset( $block['mobile_padding']['top'] ) && '' !== $block['mobile_padding']['top'] ) {
							$str .= 'padding-top:' . intval( $block['mobile_padding']['top'] ) . 'px;';
						}
						if ( isset( $block['mobile_padding']['right'] ) && '' !== $block['mobile_padding']['right'] ) {
							$str .= 'padding-right:' . intval( $block['mobile_padding']['right'] ) . 'px;';
						}
						if ( isset( $block['mobile_padding']['bottom'] ) && '' !== $block['mobile_padding']['bottom'] ) {
							$str .= 'padding-bottom:' . intval( $block['mobile_padding']['bottom'] ) . 'px;';
						}
						if ( isset( $block['mobile_padding']['left'] ) && '' !== $block['mobile_padding']['left'] ) {
							$str .= 'padding-left:' . intval( $block['mobile_padding']['left'] ) . 'px;';
						}
						$str .= '}}';
					}
				}
			}

			update_post_meta( $page_id, 'rbc_dynamic_style', addslashes( $str ) );
			return false;
		}

	}
}

/** init RUBY COMPOSER */
RUBY_COMPOSER::get_instance();