<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param string $post_type
 *
 * @return array
 * cat select
 */
function rbc_cat_dropdown_select( $post_type = 'post' ) {

	$data       = array();
	$categories = get_categories( array(
		'hide_empty' => 0,
		'type'       => $post_type,
	) );

	$array_walker = new rbc_cat_walker;
	$array_walker->walk( $categories, 4 );
	$buffer = $array_walker->cat_array;
	foreach ( $buffer as $name => $id ) {
		$data[ $name ] = $id;
	}

	return $data;
}


/**
 * Class rcbCatWalker
 * rewrite output
 */
class rbc_cat_walker extends Walker {

	var $tree_type = 'category';
	var $cat_array = array();
	var $db_fields = array(
		'id'     => 'term_id',
		'parent' => 'parent'
	);

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
	}

	public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$this->cat_array[ str_repeat( ' - ', $depth ) . $object->name . ' - [ ID: ' . $object->term_id . ' / Posts: ' . $object->category_count . ' ]' ] = $object->term_id;
	}

	public function end_el( &$output, $object, $depth = 0, $args = array() ) {
	}
}

/**
 * @return mixed|void
 * sidebar default
 */
function rbc_sidebar_default() {
	$default = 'rbc_sidebar_default';
	return apply_filters( 'rbc_default_sidebar', $default );
}

/** sidebar dropdown select */
function rbc_sidebar_dropdown_select() {
	$sidebars = $GLOBALS['wp_registered_sidebars'];
	$sidebars = apply_filters('rbc_remove_sidebars', $sidebars);

	if ( is_array( $sidebars ) ) {

		foreach ( $sidebars as $sidebar ) :
			if ( ! empty( $sidebar['id'] ) && ! empty( $sidebar['name'] ) ) : ?>
				<# var selected = (<?php echo '"' . $sidebar['id'] . '"'; ?> == data.sidebar_name) ? 'selected' : ''; #>
				<option {{selected}} value="<?php echo esc_attr( $sidebar['id'] ); ?>"><?php echo esc_html($sidebar['name']); ?></option>
			<?php endif;
		endforeach;
	}
} ?>

<script type="text/html" id="tmpl-rbc-switch-mode">
	<div class="rbc-switch-mode">
		<a id="rbc-switch-mode-btn" title="switch to Ruby Composer" class="rbc-switch-mode-btn" href="#"><i class="dashicons dashicons-migrate"></i>
			<?php esc_html_e( 'Ruby Composer', 'rbc' ) ?>
		</a></div>
</script>

<script type="text/html" id="tmpl-rbc-panel-composer">
	 <div id="rbc-editor" class="rbc-editor-wrap is-hidden">
    <div class="rbc-editor-header">
	    <div class="rbc-editor-header-inner">
		    <h3 class="rbc-editor-heading"><i class="dashicons dashicons-art"></i><?php esc_html_e( 'Ruby Composer', 'rbc' ); ?></h3>
		    <div class="rbc-template-panel-outer">
			    <a href="#" id="rbc-main-template-btn" title="templates"><i class="dashicons dashicons-download"></i><?php esc_html_e( 'Templates', 'rbc' ); ?></a>
			    <div class="rbc-template-panel">
				    <a href="#" id="rbc-template-save-btn" title="save template"><i class="dashicons dashicons-cloud"></i><?php esc_html_e( 'Save Content as Template', 'rbc' ); ?></a>
				    <div id="rbc-template-submit-panel">
					    <h3 class="rbc-template-title"><?php esc_html_e( 'Template Name/ID', 'rbc' ); ?></h3>
					    <input id="rbc-template-name" class="rbc-field rbc-text" autocomplete="off" type="text" name="rbc_template_name" placeholder="<?php esc_attr_e('Template ID/Name...', 'rbc'); ?>">
					    <em class="rbc-input-notice"><?php esc_html_e('(*)Please input name/ID','rbc'); ?></em>
					    <span><?php esc_html_e( 'Input a unique name/ID without special charsets and white spacing.', 'rbc' ); ?></span>
					    <a href="#" id="rbc-template-submit-btn"><?php esc_html_e( 'Save', 'rbc' ); ?></a>
					    <span class="saving-load"><?php esc_html_e( 'saving...', 'rbc' ) ?></span>
				    </div>
				    <h4><?php esc_html_e('Load Template', 'rbc'); ?></h4>
				    <div class="rbc-template-loader-outer">
					    <div id="rbc-template-loaded-panel" class="rbc-template-loaded-panel"></div>
					    <span class="rbc-template-empty"><?php esc_html_e('No template saved yet!', 'rbc'); ?></span>
					 </div>
			    </div>
		    </div>
	    </div>
	    <div class="rbc-panel-section">
	        <h3 id="rbc-section-list-header" class="rbc-section-list-header"><?php esc_html_e( 'Select a Section', 'rbc' ); ?></h3>
	        <div id="rbc-section-list" class="rbc-section-list clearfix"></div>
	    </div>
    </div>
    <div id="rbc-stack-sections" class="rbc-stack-sections">
    <span class="rbc-empty rbc-section-empty"><?php esc_html_e( 'Click on SECTION image to create a new section', 'rbc' ); ?></span>
    </div>
    <div class="rbc-loader"></div>
	</div>
</script>

<!-- section list item -->
<script type="text/html" id="tmpl-rbc-section-item">
	<div class="rbc-section-list-item">
		<a href="#" class="rbc-section-target" data-type="{{{data.type}}}"><img alt="{{{data.type}}}" src="{{{data.img}}}"><span>{{data.title}}</span></a>
	</div>
</script>

<!-- section fullwidth -->
<script type="text/html" id="tmpl-rbc-section-fullwidth">
	<div id="rbc-section-{{data.uuid}}" class="rbc-section rbc-section-fullwidth" data-uuid="{{{data.uuid}}}">
		<input type="hidden" class="rbc-field rbc-section-order rbc-order" name="rbc_section_order[]" value="{{{data.uuid}}}">
		<input type="hidden" class="rbc-field rbc-section-type" name="rbc_section[{{{data.uuid}}}][type]" value="{{{data.type}}}">
		<div class="rbc-section-header">
			<div class="rbc-section-bar">
                <a class="rbc-section-move" href="#" title="move section"><i class="dashicons dashicons-move"></i></a>
				<# if ( data.title ) { #>
					<h3 class="section-label">{{data.title}} <span class="rb-index">Position: {{data.rbIndex}}</span></h3>
					<# } #>
					<a class="rbc-section-expand" href="#" title="expand collapse section"><i class="dashicons dashicons-sort"></i></a>
					<a class="rbc-section-clone" href="#" title="clone section"><i class="dashicons dashicons-admin-page"></i></a>
					<a class="rbc-section-delete" href="#" title="delete section"><i class="dashicons dashicons-trash"></i></a>
			</div>
			<div class="rbc-panel-block clearfix">
				<a href="#" class="rbc-panel-block-expand" title="expand collapse panel"><i class="dashicons dashicons-sort"></i></a>
				<div class="rbc-panel-block-header">
                    <h3 class="rbc-block-list-title is-nav-active rbc-panel-nav-target" data-target="list"><i class="dashicons dashicons-archive"></i><?php esc_html_e( 'Blocks', 'rbc' ); ?></h3>
                    <h3 class="rbc-section-settings-title rbc-panel-nav-target" data-target="settings"><i class="dashicons dashicons-admin-generic"></i><?php esc_html_e( 'Section Settings', 'rbc'); ?></h3>
                </div>
				<div class="rbc-panel-block-content">
					<div class="rbc-block-list rbc-panel-tab rbc-panel-tab-list is-panel-active"></div>
					<div class="rbc-section-settings rbc-panel-tab rbc-panel-tab-settings"></div>
				</div>
			</div>
		</div>
		<div class="rbc-stack-blocks rbc-stack-blocks-fullwidth">
			<div class="rbc-empty"><?php esc_html_e( 'Click on a BLOCK image to create a new block', 'rbc' ); ?></div>
		</div>
	</div>
</script>

<!-- section content -->
<script type="text/html" id="tmpl-rbc-section-content">
	<div id="rbc-section-{{data.uuid}}" class="rbc-section rbc-section-content" data-uuid="{{{data.uuid}}}">
		<input type="hidden" class="rbc-field rbc-section-order rbc-order" name="rbc_section_order[]" value="{{{data.uuid}}}">
		<input type="hidden" class="rbc-field rbc-section-type" name="rbc_section[{{{data.uuid}}}][type]" value="{{{data.type}}}">
		<div class="rbc-section-header">
			<div class="rbc-section-bar">
				<a class="rbc-section-move" href="#" title="move section"><i class="dashicons dashicons-move"></i></a>
				<# if ( data.title ) { #>
					<h3 class="section-label">{{data.title}} <span class="rb-index">Position: {{data.rbIndex}}</span></h3>
					<# } #>
						<a class="rbc-section-expand" href="#" title="expand collapse section"><i class="dashicons dashicons-sort"></i></a>
						<a class="rbc-section-clone" href="#" title="clone section"><i class="dashicons dashicons-admin-page"></i></a>
						<a class="rbc-section-delete" href="#" title="delete section"><i class="dashicons dashicons-trash"></i></a>
			</div>
			<div class="rbc-panel-block clearfix">
				<a href="#" class="rbc-panel-block-expand" title="expand collapse panel"><i class="dashicons dashicons-sort"></i></a>
				<div class="rbc-panel-block-header">
					<h3 class="rbc-block-list-title is-nav-active rbc-panel-nav-target" data-target="list"><i class="dashicons dashicons-archive"></i><?php esc_html_e( 'Blocks', 'rbc' ); ?></h3>
					<h3 class="rbc-block-sidebar-title rbc-panel-nav-target" data-target="sidebar"><i class="dashicons dashicons-align-left"></i><?php esc_html_e( 'Sidebar Settings', 'rbc' ); ?></h3>
					<h3 class="rbc-section-settings-title rbc-panel-nav-target" data-target="settings"><i class="dashicons dashicons-admin-generic"></i><?php esc_html_e( 'Section Settings', 'rbc'); ?></h3>
				</div>
				<div class="rbc-panel-block-content">
					<div class="rbc-block-list rbc-panel-tab rbc-panel-tab-list is-panel-active"></div>
					<div class="rbc-panel-sidebar rbc-panel-tab rbc-panel-tab-sidebar">
						<div class="rbc-sidebar-el">
							<span class="sidebar-label"><?php esc_html_e( 'Sidebar Name:', 'rbc' ); ?></span>
							<# if( 'undefined' == typeof data.sidebar_name) { data.sidebar_name = '<?php echo rbc_sidebar_default(); ?>' } #>
							<select class="rbc-field rbc-sidebar-name" name="rbc_sidebar[{{{data.uuid}}}][name]">
							<?php rbc_sidebar_dropdown_select(); ?>
							</select>
						</div>
						<div class="rbc-sidebar-el">
							<span class="sidebar-label"><?php esc_html_e( 'Sidebar Position:', 'rbc' ); ?></span>
							<select class="rbc-field rbc-sidebar-position" name="rbc_sidebar[{{{data.uuid}}}][position]">
								<# if ( 'undefined' == typeof data.sidebar_pos || 'right' == data.sidebar_pos) { #>
								<option selected value="right"><?php esc_html_e( 'Right', 'rbc' ); ?></option>
								<option value="left"><?php esc_html_e( 'Left', 'rbc' ); ?></option>
								<#  } else { #>
								<option value="right"><?php esc_html_e( 'Right', 'rbc' ); ?></option>
								<option selected value="left"><?php esc_html_e( 'Left', 'rbc' ); ?></option>
								<# } #>
							</select>
						</div>
						<div class="rbc-sidebar-el">
							<span class="sidebar-label"><?php esc_html_e( 'Sticky:', 'rbc' ); ?></span>
							<select class="rbc-field rbc-sidebar-sticky" name="rbc_sidebar[{{{data.uuid}}}][sticky]">
								<# if ( 'undefined' == typeof data.sidebar_sticky) { data.sidebar_sticky = 'default' } #>
								<?php $sticky = array(
								'default' => esc_html__( 'Default from Global Setting', 'rbc' ),
								'sticky'   => esc_html__( 'Sticky Sidebar', 'rbc' ),
								'none'    => esc_html__( 'None', 'rbc' ),
								);
								foreach ($sticky as $val => $name) : ?>
								<# var selected = ( <?php echo '"' . esc_attr( $val ) . '"'; ?> == data.sidebar_sticky ) ? 'selected' : ''; #>
								<option {{selected}} value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $name ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="rbc-section-settings rbc-panel-tab rbc-panel-tab-settings"></div>
				</div>
			</div>
		</div>
		<div class="rbc-stack-blocks rbc-stack-blocks-content">
			<div class="rbc-empty"><?php esc_html_e( 'Click on a BLOCK image to create a new block', 'rbc' ); ?></div>
		</div>
	</div>
</script>

<!-- section dimension -->
<script type="text/html" id="tmpl-rbc-section-dimension">
		<# if( 'undefined' !== typeof data.value && 'undefined' !== typeof data.value.top) { #>
			<span class="rbc-dimension">
			<span class="rbc-dimension-label"><?php esc_html_e('Top', 'rbc'); ?></span>
			<input class="rbc-field rbc-dimension-top" autocomplete="off" type="number" name="rbc_section[{{{data.uuid}}}][{{{data.name}}}][top]" value="{{{data.value.top}}}">
			</span>
		<# } #>
		<# if( 'undefined' !== typeof data.value && 'undefined' !== typeof data.value.right) { #>
			<span class="rbc-dimension">
			<span class="rbc-dimension-label"><?php esc_html_e('Right', 'rbc'); ?></span>
			<input class="rbc-field rbc-dimension-right" autocomplete="off" type="number" name="rbc_section[{{{data.uuid}}}][{{{data.name}}}][right]" value="{{{data.value.right}}}">
			</span>
		<# } #>
		<# if( 'undefined' !== typeof data.value && 'undefined' !== typeof data.value.bottom) { #>
			<span class="rbc-dimension">
			<span class="rbc-dimension-label"><?php esc_html_e('Bottom', 'rbc'); ?></span>
			<input class="rbc-field rbc-dimension-bottom" autocomplete="off" type="number" name="rbc_section[{{{data.uuid}}}][{{{data.name}}}][bottom]" value="{{{data.value.bottom}}}">
			</span>
		<# } #>
		<# if( 'undefined' !== typeof data.value && 'undefined' !== typeof data.value.left) { #>
			<span class="rbc-dimension">
			<span class="rbc-dimension-label"><?php esc_html_e('Left', 'rbc'); ?></span>
			<input class="rbc-field rbc-dimension-left" autocomplete="off" type="number" name="rbc_section[{{{data.uuid}}}][{{{data.name}}}][left]" value="{{{data.value.left}}}">
			</span>
		<# } #>
</script>
<script type="text/html" id="tmpl-rbc-section-option">
	<div class="rbc-section-option">
		<div class="rbc-option-header">
			<label class="rbc-option-label">{{data.title}}</label>
			<div class="rbc-option-description">{{data.description}}</div>
		</div>
		<div class="rbc-option-input"></div>
	</div>
</script>
<script type="text/html" id="tmpl-rbc-section-select">
	<select class="rbc-field rbc-field-select" name="rbc_section[{{{data.uuid}}}][{{{data.name}}}]">
	<# _.each( data.options, function (option, value) { #>
		<# var selected = ( value == data.value ) ? 'selected' : ''; #>
		<option {{selected}} value="{{{value}}}">{{ option }}</option>
	<# } ) #>
	</select>
</script>
<script type="text/html" id="tmpl-rbc-section-color">
	<input class="rbc-field rbc-color" type="text" name="rbc_section[{{{data.uuid}}}][{{{data.name}}}]" value="{{{data.value}}}">
</script>
<script type="text/html" id="tmpl-rbc-section-text">
	<input class="rbc-field rbc-text" autocomplete="off" type="text" name="rbc_section[{{{data.uuid}}}][{{{data.name}}}]" value="{{{data.value}}}">
</script>
<script type="text/html" id="tmpl-rbc-block-item">
	<div class="rbc-block-list-item">
		<a href="#" class="rbc-block-target" data-name="{{{data.name}}}"><img alt="{{{data.name}}}" src="{{{data.img}}}"><span>{{data.title}}<# if ( data.tagline ) { #><span class="tagline">{{data.tagline}}</span><# } #></span></a>
	</div>
</script>
<script type="text/html" id="tmpl-rbc-block">
	<div id="rbc-block-{{{data.uuid}}}" class="rbc-block" data-uuid="{{{data.uuid}}}">
		<input type="hidden" class="rbc-field rbc-block-order rbc-order" name="rbc_block_order[{{{data.sectionID}}}][]" value="{{{data.uuid}}}">
		<input type="hidden" class="rbc-field rbc-block-name" name="rbc_block_name[{{{data.uuid}}}]" value="{{{data.name}}}">
		<div class="rbc-block-header">
			<div class="rbc-block-bar">
				<a class="rbc-block-move" href="#" title="move block"><i class="dashicons dashicons-move"></i></a>
				<h3 class="block-label">
					<# if ( data.title ) { #>{{data.title}}<# } #><# if ( data.header ) { #> : {{data.header}}<# } #>
				</h3>
				<a class="rbc-block-clone" href="#" title="clone block"><i class="dashicons dashicons-admin-page"></i></a>
				<a class="rbc-block-expand" href="#" title="expand collapse block"><i class="dashicons dashicons-sort"></i></a>
				<a class="rbc-block-delete" href="#" title="delete block"><i class="dashicons dashicons-trash"></i></a>
			</div>
		</div>
		<div class="rbc-options-wrap is-hidden">
				<div class="rbc-block-description">
					<# if ( data.description ) { #>
					<div class="description">{{data.description}}</div>
					<# } #>
					<# if ( data.tips ) { #>
					<div class="tips"><i class="dashicons dashicons-info"></i>{{data.tips}}</div>
					<# } #>
				</div>

                <div class="rbc-tabs-nav"></div>
                <div class="rbc-stack-options"></div>
		</div>
	</div>
</script>
<script type="text/html" id="tmpl-rbc-block-options">
	<div class="rbc-block-option">
		<div class="rbc-option-header">
			<label class="rbc-option-label">{{data.title}}</label>
			<div class="rbc-option-description">{{data.description}}</div>
		</div>
		<div class="rbc-option-input"></div>
	</div>
</script>
<script type="text/html" id="tmpl-rbc-input-text">
	<input class="rbc-field rbc-text" autocomplete="off" type="text" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}]" value="{{{data.value}}}" placeholder="{{{data.placeholder}}}">
</script>
<script type="text/html" id="tmpl-rbc-input-number">
    <# var min = ('undefined' == typeof data.min) ? '0' : data.min;  #>
    <input class="rbc-field rbc-number" autocomplete="off" type="number" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}]" value="{{{data.value}}}" min="{{{min}}}">
</script>
<script type="text/html" id="tmpl-rbc-input-textarea">
	<# var textRow = ( undefined == data.value || ! data.row ) ? '9' : data.row ;  #>
	<textarea class="rbc-field rbc-textarea" rows="{{textRow}}" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}]">{{data.value}}</textarea>
</script>
<script type="text/html" id="tmpl-rbc-input-category">
	<# var allSelected = ( ! data.value ) ? 'selected' : '';  #>
		<select class="rbc-field rbc-field-select rbc-field-category" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}]">
			<?php $categories = rbc_cat_dropdown_select(); ?>
			<option value="0" {{allSelected}}><?php esc_html_e( '-- All categories --', 'rbc' ); ?></option>
			<?php foreach ( $categories as $name => $id ) : ?>
            <# var selected = ( <?php echo '"' . esc_attr( $id ) . '"'; ?> == data.value ) ? 'selected' : '';  #>
			<option {{selected}} value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select>
</script>
<script type="text/html" id="tmpl-rbc-input-category_select">
	<# var noneSelected = ( ! data.value ) ? 'selected' : '';  #>
		<select class="rbc-field rbc-field-select rbc-field-category" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}]">
			<?php $categories = rbc_cat_dropdown_select(); ?>
			<option class="option-light" value="0" {{noneSelected}}><?php esc_html_e( '-- Select a Category --', 'rbc' ); ?></option>
			<?php foreach ( $categories as $name => $id ) : ?>
			<# var selected = ( <?php echo '"' . esc_attr( $id ) . '"'; ?> == data.value ) ? 'selected' : '';  #>
				<option {{selected}} value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
		</select>
</script>
<script type="text/html" id="tmpl-rbc-input-categories">
	<# var noneSelected = ( undefined == data.value || !data.value || data.value.length == 0 ) ? 'selected' : ''; #>
		<select class="rbc-field rbc-field-select rbc-field-categories" multiple="multiple" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}][]">
			<option value="0" {{noneSelected}}><?php esc_html_e( '-- Disable --', 'rbc' ); ?></option>
			<?php $categories = rbc_cat_dropdown_select();  ?>
			<?php foreach ( $categories as $name => $id ) : ?>
			<# var selected = ( -1 !== jQuery.inArray( <?php echo '"' . esc_attr( $id ) . '"'; ?>, data.value )) ? 'selected' : ''; #>
			<option {{selected}} value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select>
</script>
<script type="text/html" id="tmpl-rbc-input-format">
	<?php $data = array(
		'0'       => esc_html__( '-- All --', 'rbc' ),
		'default' => esc_html__( 'Default', 'rbc' ),
		'gallery' => esc_html__( 'Gallery', 'rbc' ),
		'video'   => esc_html__( 'Video', 'rbc' ),
		'audio'   => esc_html__( 'Audio', 'rbc' ),
	); ?>
	<select class="rbc-field rbc-field-select rbc-field-post-format" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}]">
		<?php foreach ($data as $value => $title) : ?>
		<# var selected = ( <?php echo '"' . esc_attr( $value ) . '"'; ?> == data.value ) ? 'selected' : '';  #>
			<option {{selected}} value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $title ); ?></option>
			<?php endforeach; ?>
	</select>
</script>
<script type="text/html" id="tmpl-rbc-input-author">
	<# var allSelected = ( ! data.value ) ? 'selected' : '';  #>
	<?php $blogusers = get_users( array(
		'role__not_in' => array( 'subscriber' ),
		'fields'       => array( 'ID', 'display_name' )
	) ); ?>
	<select class="rbc-field rbc-field-select rbc-field-author" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}]">
		<option {{allSelected}} value="0"><?php echo esc_html__('--All Authors--','rbc'); ?></option>
		<?php foreach ( $blogusers as $user ) : ?>
			<# var selected = ( <?php echo '"' . esc_attr( $user->ID ) . '"'; ?> == data.value ) ? 'selected' : '';  #>
			<option {{selected}} value="<?php echo esc_attr( $user->ID ); ?>"><?php echo esc_attr( $user->display_name ); ?></option>
		<?php endforeach ?>
	</select>
</script>
<script type="text/html" id="tmpl-rbc-input-select">
	<select class="rbc-field rbc-field-select" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}]">
		<# _.each( data.options, function (option, value) { #>
            <# var selected = ( value == data.value ) ? 'selected' : ''; #>
            <option {{selected}} value="{{{value}}}">{{ option }}</option>
		<# } ) #>
	</select>
</script>
<script type="text/html" id="tmpl-rbc-input-order">
	<select class="rbc-field rbc-field-select rbc-field-order" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}]">
        <# if ( 'undefined' == typeof data.value ) { data.value = 'date_post' ; } #>
		<?php $data = array(
			'date_post'               => esc_html__( 'Latest Post', 'rbc' ),
			'update'                  => esc_html__( 'Last Updated', 'rbc' ),
			'comment_count'           => esc_html__( 'Popular Comment', 'rbc' ),
			'popular'                 => esc_html__( 'Popular (Require Post Views Plugin)', 'rbc' ),
			'popular_m'               => esc_html__( 'Popular Last 30 Days (Require Post Views Plugin)', 'rbc' ),
			'popular_w'               => esc_html__( 'Popular Last 7 Days (Require Post Views Plugin)', 'rbc' ),
			'top_review'              => esc_html__( 'Top Review', 'rbc' ),
			'last_review'             => esc_html__( 'Latest Review', 'rbc' ),
			'post_type'               => esc_html__( 'Post Type', 'rbc' ),
			'rand'                    => esc_html__( 'Random', 'rbc' ),
			'author'                  => esc_html__( 'Author', 'rbc' ),
			'alphabetical_order_decs' => esc_html__( 'Title DECS', 'rbc' ),
			'alphabetical_order_asc'  => esc_html__( 'Title ACS', 'rbc' ),
			'by_input'                => esc_html__( 'by input IDs Data (Work with Post IDs filter)', 'rbc' )
		); ?>
		<?php foreach ( $data as $value => $title ) : ?>
            <# var selected = ( <?php echo '"' . esc_attr($value) . '"'; ?> == data.value ) ? 'selected' : '';  #>
			<option {{selected}} value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $title ); ?></option>
		<?php endforeach; ?>
	</select>
</script>
<script type="text/html" id="tmpl-rbc-input-color">
    <input class="rbc-field rbc-color" type="text" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}]" value="{{{data.value}}}">
</script>
<script type="text/html" id="tmpl-rbc-input-dimension">
	<# if( 'undefined' !== typeof data.value && 'undefined' !== typeof data.value.top) { #>
	<span class="rbc-dimension">
          <span class="rbc-dimension-label"><?php esc_html_e('Top', 'rbc'); ?></span>
          <input class="rbc-field rbc-dimension-top" autocomplete="off" type="number" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}][top]" value="{{{data.value.top}}}">
    </span>
	<# } #>
    <# if( 'undefined' !== typeof data.value && 'undefined' !== typeof data.value.right) { #>
    <span class="rbc-dimension">
          <span class="rbc-dimension-label"><?php esc_html_e('Right', 'rbc'); ?></span>
          <input class="rbc-field rbc-dimension-right" autocomplete="off" type="number" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}][right]" value="{{{data.value.right}}}">
    </span>
    <# } #>
    <# if( 'undefined' !== typeof data.value && 'undefined' !== typeof data.value.bottom) { #>
    <span class="rbc-dimension">
          <span class="rbc-dimension-label"><?php esc_html_e('Bottom', 'rbc'); ?></span>
          <input class="rbc-field rbc-dimension-bottom" autocomplete="off" type="number" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}][bottom]" value="{{{data.value.bottom}}}">
    </span>
    <# } #>
    <# if( 'undefined' !== typeof data.value && 'undefined' !== typeof data.value.left) { #>
    <span class="rbc-dimension">
          <span class="rbc-dimension-label"><?php esc_html_e('Left', 'rbc'); ?></span>
          <input class="rbc-field rbc-dimension-left" autocomplete="off" type="number" name="rbc_option[{{{data.uuid}}}][{{{data.name}}}][left]" value="{{{data.value.left}}}">
    </span>
    <# } #>
</script>
<script type="text/html" id="tmpl-rbc-tab-nav">
	<a href="#" class="rbc-tab-nav-target" data-tab-filter="{{{data.tab}}}">{{data.title}}</a>
</script>
<script type="text/html" id="tmpl-rbc-tab">
	<div class="rbc-tab-wrap rbc-tab-{{{data.tab}}}" data-tab="{{{data.tab}}}"></div>
</script>
<script type="text/html" id="tmpl-rbc-js-loaded">
	<input type="hidden" class="rbc-field" name="rbc_js_loaded" value="1">
</script>
<script type="text/html" id="tmpl-rbc-template-list">
		<# if( 'recipe' == data.name || 'technology' == data.name || 'fashion' == data.name || 'travel' == data.name || 'lifestyle' == data.name ||  'baby' == data.name || 'blogger' == data.name || 'work' == data.name || 'food' == data.name || 'gadget' == data.name || 'review' == data.name || 'fdeal' == data.name || 'sport' == data.name || 'freebie' == data.name || 'decor' == data.name || 'beauty' == data.name || 'medical' == data.name || 'yoga' == data.name || 'marketing' == data.name ) { #>
			<div class="rbc-template-list-wrap list-default">
			<a href="#" class="rbc-template-list-add default-template" data-name="{{{data.name}}}" title="load this template">{{data.name}}</a>
			</div>
		<# } else { #>
			<div class="rbc-template-list-wrap">
			<a href="#" class="rbc-templte-list-add" data-name="{{{data.name}}}" title="load this template">{{data.name}}</a>
			<a href="#" class="rbc-template-list-delete" data-name="{{{data.name}}}" title="delete template"><i class="dashicons dashicons-dismiss"></i></a>
			</div>
		<#  } #>
</script>
