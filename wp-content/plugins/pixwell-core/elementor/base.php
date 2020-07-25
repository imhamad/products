<?php
namespace PixwellElementorElement;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Support elementor */
if ( ! function_exists( 'Ruby_Plugin' ) ) {
	class Ruby_Plugin {
		private static $instance = null;

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function __construct() {
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
			add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		}

		private function load_files() {
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-1.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-2.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-3.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-4.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-5.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-6.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-7.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-8.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-9.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-10.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-11.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-12.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-13.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-14.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-feat-15.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-grid-1.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-grid-2.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-grid-3.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-grid-4.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-list-1.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-list-2.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-masonry-1.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/ct-grid-1.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/ct-grid-2.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/ct-list.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/ct-classic.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/ct-masonry-1.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-mix-1.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-mix-2.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-newsletter.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/about.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-banner.php' );
			require_once( PIXWELL_CORE_PATH . '/elementor/fw-search.php' );
		}

		private function load_controls() {
			require_once( PIXWELL_CORE_PATH . '/elementor/control.php' );
		}

		/** register category  */
		public function register_category( $elements_manager ) {
			$elements_manager->add_category(
				'pixwell-fw', array(
					'title' => esc_html__( 'Pixwell - FullWidth (Boxed) Section', 'pixwell-core' ),
					'icon'  => 'eicon-section',
				)
			);

			$elements_manager->add_category(
				'pixwell-wide', array(
					'title' => esc_html__( 'Pixwell - FullWide Section', 'pixwell-core' ),
					'icon'  => 'eicon-section',
				)
			);

			$elements_manager->add_category(
				'pixwell-ct', array(
					'title' => esc_html__( 'Pixwell Content Section', 'pixwell-core' ),
					'icon'  => 'eicon-section',
				)
			);
		}


		public function register_widgets() {
			$this->load_controls();
			$this->load_files();

			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_1() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_2() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_3() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_4() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_5() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_6() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_7() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_8() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_9() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_10() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_11() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_12() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_13() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_14() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Feat_15() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Grid_1() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Grid_2() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Grid_3() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Grid_4() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_List_1() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_List_2() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Masonry_1() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Ct_Grid_1() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Ct_Grid_2() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Ct_List_1() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Ct_Classic_1() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Ct_Masonry_1() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Mix_1() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Mix_2() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Ruby_Newsletter() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Ruby_About_Me() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Ruby_Banner() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Fw_Search_Box() );
		}
	}

}

/** load plugin */
Ruby_Plugin::get_instance();