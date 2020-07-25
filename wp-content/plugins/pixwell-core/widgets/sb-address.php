<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_address' ) ) :
	class pixwell_widget_address extends WP_Widget {
	
		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-address',
				'description' => esc_html__( '[Sidebar Widget] Display address information in the sidebar section.', 'pixwell-core' )
			);
			parent::__construct( 'address', esc_html__( '- [Sidebar] Address Info -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			echo $args['before_widget'];
			$instance = wp_parse_args( $instance, array(
				'title'            => '',
				'address_title'    => '',
				'address'          => '',
				'phone_title'      => '',
				'phone'            => '',
				'tel'              => '',
				'email'            => '',
				'additional_title' => '',
				'additional'       => '',
			) );

			$instance['title'] = apply_filters( 'widget_title', $instance['title'] );
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . $instance['title'] . $args['after_title'];
			} ?>
			<div class="address-info">
				<?php
				if ( ! empty( $instance['address_title'] ) ) : ?>
					<h5 class="office-address-title h4"><?php echo esc_html( $instance['address_title'] ); ?></h5>
				<?php endif;
				if ( ! empty( $instance['address'] ) ) : ?>
					<div class="office-address"><?php echo html_entity_decode( esc_html( $instance['address'] ) ); ?></div>
				<?php endif;
				if ( ! empty( $instance['phone_title'] ) ) : ?>
					<h5 class="phone-title h4"><?php echo esc_html( $instance['phone_title'] ); ?></h5>
				<?php endif;
				if ( ! empty( $instance['phone'] ) ) : ?>
					<div class="phone"><?php echo html_entity_decode(esc_html( $instance['phone'] )); ?></div>
				<?php endif;
				if ( ! empty( $instance['tel'] ) ) : ?>
					<div class="tel"><?php echo html_entity_decode(esc_html( $instance['tel'] )); ?></div>
				<?php endif;
				if ( ! empty( $instance['email'] ) ) : ?>
					<div class="email"><?php echo html_entity_decode(esc_html( $instance['email'] )); ?></div>
				<?php endif;
				if ( ! empty( $instance['additional_title'] ) ) : ?>
					<h5 class="additional-title h4"><?php echo esc_html( $instance['additional_title'] ); ?></h5>
				<?php endif;
				if ( ! empty( $instance['additional'] ) ) : ?>
					<div class="additional"><?php echo html_entity_decode( esc_html( $instance['additional'] ) ); ?></div>
				<?php endif; ?>
			</div>
			<?php echo $args['after_widget'];
		}
		
		function update( $new_instance, $old_instance ) {

			$instance                     = $old_instance;
			$instance['title']            = strip_tags( $new_instance['title'] );
			$instance['address_title']    = esc_html( $new_instance['address_title'] );
			$instance['address']          = esc_html( $new_instance['address'] );
			$instance['phone_title']      = esc_html( $new_instance['phone_title'] );
			$instance['phone']            = esc_html( $new_instance['phone'] );
			$instance['tel']              = esc_html( $new_instance['tel'] );
			$instance['email']            = esc_html( $new_instance['email'] );
			$instance['additional_title'] = esc_html( $new_instance['additional_title'] );
			$instance['additional']       = esc_html( $new_instance['additional'] );

			return $instance;
		}


		function form( $instance ) {
			$defaults = array(
				'title'            => '',
				'address_title'    => esc_html__( 'Office Address', 'pixwell-core' ),
				'address'          => '',
				'phone_title'      => esc_html__( 'Phone Information', 'pixwell-core' ),
				'phone'            => '',
				'tel'              => '',
				'email'            => '',
				'additional_title' => '',
				'additional'       => '',
			);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p><?php esc_html_e('Allow HTML input.', 'pixwell-core'); ?></p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><strong><?php esc_attr_e('Title :','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php if (!empty($instance['title'])) echo esc_attr($instance['title']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('address_title')); ?>"><strong><?php esc_attr_e('Address Title:','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('address_title')); ?>" name="<?php echo esc_attr($this->get_field_name('address_title')); ?>" value="<?php if (!empty($instance['address_title'])) echo esc_html($instance['address_title']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('address')); ?>"><strong><?php esc_attr_e('Office Address','pixwell-core') ?></strong></label>
				<textarea rows="10" cols="50" id="<?php echo esc_attr($this->get_field_id( 'address' )); ?>" name="<?php echo esc_attr($this->get_field_name('address')); ?>" class="widefat"><?php esc_html_e($instance['address']); ?></textarea>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('phone_title')); ?>"><strong><?php esc_attr_e('Phone/Tel Title:','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('phone_title')); ?>" name="<?php echo esc_attr($this->get_field_name('phone_title')); ?>" value="<?php if (!empty($instance['phone_title'])) echo esc_html($instance['phone_title']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><strong><?php esc_attr_e('Phone Number:','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" value="<?php if (!empty($instance['phone'])) echo esc_html($instance['phone']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('tel')); ?>"><strong><?php esc_attr_e('Tel Number:','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('tel')); ?>" name="<?php echo esc_attr($this->get_field_name('tel')); ?>" value="<?php if (!empty($instance['tel'])) echo esc_html($instance['tel']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><strong><?php esc_attr_e('Email Address:','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" value="<?php if (!empty($instance['email'])) echo esc_html($instance['email']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('additional_title')); ?>"><strong><?php esc_attr_e('Additional Title:','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('additional_title')); ?>" name="<?php echo esc_attr($this->get_field_name('additional_title')); ?>" value="<?php if (!empty($instance['additional_title'])) echo esc_html($instance['additional_title']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('additional')); ?>"><strong><?php esc_attr_e('Additional Info','pixwell-core') ?></strong></label>
				<textarea rows="10" cols="50" id="<?php echo esc_attr($this->get_field_id( 'additional' )); ?>" name="<?php echo esc_attr($this->get_field_name('additional')); ?>" class="widefat"><?php esc_html_e($instance['additional']); ?></textarea>
			</p>
		<?php
		}
	}
endif;