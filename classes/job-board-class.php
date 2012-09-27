<?php

class JobBoardClass {

	/**
	*Variables
	*/
	const version = '0.1';
	const nspace = 'job-board';
	const pname = 'Job Board';
	const post_type = 'job_board_job';
	const taxonomy = 'job-type';

	protected $_plugin_file;
	protected $_plugin_dir;
	protected $_plugin_path;
	protected $_plugin_url;

	/**
	*Constructor
	*
	*@return void
	*@since 0.1
	*/
	function __construct() {}

	/**
        *Init function
        *
        *@return void
        *@since 0.1
        */
	function init() {

		// activation

		register_activation_hook( $this->get_plugin_file(), array( &$this,'activate' ) );
		
		// post types
		
		add_action( 'init', array( &$this, 'setup_post_types' ), 10 );
		add_filter( 'single_template', array( &$this, 'get_custom_post_type_template' ) );
		add_filter( 'archive_template', array( &$this, 'get_custom_post_type_template' ) );
		add_filter( 'search_template', array( &$this, 'get_custom_post_type_template' ) );

		if ( is_admin() ) {

			// add and save meta boxes

			add_action( 'admin_menu', array( &$this, 'setup_meta_boxes' ), 2 );
			add_filter( 'save_post', array( &$this, 'save_meta_boxes' ) );
		}

		// CSS and javascript
		
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );	
	}

	/**
	*Function executed when plugin is activated
	*
	*@return void
	*@since 1.0
	*/
	function activate(){
		$this->setup_post_types();
		flush_rewrite_rules();
	}

	/**
	*Set plugin file
	*
	*@return void
	*@since 0.1
	*/
	function set_plugin_file( $plugin_file ) {
		$this->_plugin_file = $plugin_file;	
	}

	/**
	*Get plugin file
	*
	*@return string
	*@since 0.1
	*/
	function get_plugin_file() {
		return $this->_plugin_file;
	}
	
	/**
	*Set plugin directory
	*
	*@return void
	*@since 0.1
	*/
	function set_plugin_dir( $plugin_dir ) {
		$this->_plugin_dir = $plugin_dir;	
	}
	
	/**
	*Get plugin directory
	*
	*@return string
	*@since 0.1
	*/
	function get_plugin_dir() {
		return $this->_plugin_dir;	
	}
	
	/**
	*Set plugin file path
	*
	*@return void
	*@since 0.1
	*/
	function set_plugin_path( $plugin_path ) {
		$this->_plugin_path = $plugin_path;	
	}
	
	/**
	*Get plugin file path
	*
	*@return string
	*@since 0.1
	*/
	function get_plugin_path() {
		return $this->_plugin_path;	
	}
	
	/**
	*Set plugin URL
	*
	*@return void
	*@since 0.1
	*/
	function set_plugin_url( $plugin_url ) {
		$this->_plugin_url = $plugin_url;	
	}
	
	/**
	*Get plugin URL
	*
	*@return string
	*@since 0.1
	*/
	function get_plugin_url() {
		return $this->_plugin_url;	
	}
	
	/**
	*Enqueue JS
	*
	*@return void
	*@since 0.1
	*/
	function enqueue_scripts() {
		wp_register_script( self::nspace . '-script', $this->get_plugin_url() . 'js/script.js', array( 'jquery' ), self::version, true );
		wp_enqueue_script( self::nspace . '-script' );
		wp_register_style( self::nspace . '-style', $this->get_plugin_url() . 'css/style.css' );
		wp_enqueue_style( self::nspace . '-style' );
	}

	/**
	*Set up post type and taxonomy
	*
	*@return void
	*@since 1.0
	*/
	function setup_post_types(){
		register_post_type(self::post_type,array(
			'labels' => array(
				'name' => _x('Jobs','post type general name',self::nspace),
				'singular_name' => _x('Job','post type singular name',self::nspace),
				'add_new' => _x('Add New Job','Job',self::nspace),
				'add_new_item' => __('Add New Job',self::nspace),
				'edit_item' => __('Edit Job',self::nspace),
				'new_item' => __('New Job',self::nspace),
				'view_item' => __('View Job',self::nspace),
				'search_items' => __('Search jobs',self::nspace),
				'not_found' => __('No jobs found',self::nspace),
				'not_found_in_trash' => __('No jobs found in Trash',self::nspace),
			),
			'public' => true,
			'show_ui' => true,
			'menu_position' => 20,
			'publicly_queryable'=> true,
			'hierarchical' => false,
			'revisions' => true,
			'show_in_nav_menus' => false,
			'rewrite' => array( 'slug' => 'jobs' ),
			'has_archive' => true,
			'supports' => array('title','editor','author','revisions'),
			'taxonomies' => array( self::taxonomy )
		) );
		register_taxonomy( self::taxonomy, array(self::post_type), array(
			'labels' => array(
				'name' => _x( 'Job Types', 'taxonomy general name',self::nspace),
				'singular_name' => _x( 'Job Type', 'taxonomy singular name',self::nspace),
				'search_items' => __( 'Search Job Types',self::nspace),
				'all_items' => __( 'All Job Types',self::nspace),
				'parent_item' => __( 'Parent Job Type',self::nspace),
				'parent_item_colon' => __( 'Parent Job Type:',self::nspace),
				'edit_item' => __( 'Edit Job Type',self::nspace),
				'update_item' => __( 'Update Job Type',self::nspace),
				'add_new_item' => __( 'Add New Job Type',self::nspace),
				'new_item_name' => __( 'New Job Type',self::nspace),
				'menu_name' => __( 'Job Types',self::nspace),
			),
			'show_ui' => true,
			'hierarchical' => true,
			'show_in_nav_menus' => true,
			'rewrite' => array( 'slug' => self::taxonomy, 'with_front' => false ),
		) );
	}
	
	/**
	*Add meta boxes
	*
	*@return void
	*@since 1.0
	*/
	function setup_meta_boxes(){   
		add_meta_box ( self::nspace . "_job_details_box", __( 'Job Details', self::nspace ), array( &$this, "job_board_options" ), self::post_type, "normal", "high" );
	}
	
	/**
	*Get values of custom fields
	*
	*@return void
	*@since 1.0
	*/
	function get_field_value ( $key, $default = '' ) {
		global $post;
		$value = get_post_meta( $post->ID, $key, true );
		if ( !@strlen( $value ) ) {
			$value = $default;
		}
		return htmlspecialchars( $value );
	}
	
	/**
	*Job Details metabox
	*
	*@return void
	*@since 1.0
	*/
	function job_board_options ( ) {
		$job_location = $this->get_field_value( self::nspace . '_location' );
		$job_hiring = $this->get_field_value( self::nspace . '_hiring' );
		$job_apply = $this->get_field_value( self::nspace . '_apply' );
		$job_application_link = $this->get_field_value( self::nspace . '_application_link' );
?>
		<div class="<?php echo self::nspace; ?>-meta-box" id="<?php echo self::nspace; ?>-meta-box">
			<p>
			<label for="<?php echo self::nspace; ?>_location"><?php _e( 'Location', self::nspace ); ?></label><br />
			<input class="large-text" type="text" id="<?php echo self::nspace; ?>_location" name="<?php echo self::nspace; ?>_location" value="<?php echo $job_location; ?>" />
			</p>
			<p>
			<label for="<?php echo self::nspace; ?>_hiring"><?php _e( "Who's Hiring", self::nspace ); ?></label><br />
			<input class="large-text" type="text" id="<?php echo self::nspace; ?>_hiring" name="<?php echo self::nspace; ?>_hiring" value="<?php echo $job_hiring; ?>" />
			</p>
			<p>
			<label for="<?php echo self::nspace; ?>_apply"><?php _e( 'How do people apply for this job?', self::nspace ); ?></label><br />
			<textarea class="large-text code" id="<?php echo self::nspace; ?>_apply" name="<?php echo self::nspace; ?>_apply" cols="40" rows="4"><?php echo $job_apply; ?></textarea>
			</p>
			<p>
			<label for="<?php echo self::nspace; ?>_application_link"><?php _e( 'Online Application URL', self::nspace ); ?></label><br />
			<input class="large-text" type="text" id="<?php echo self::nspace; ?>_application_link" name="<?php echo self::nspace; ?>_application_link" value="<?php echo $job_application_link; ?>" />
			</p>
		</div>
<?php
	}
	
	/**
	*Save metaboxes
	*
	*@return void
	*@since 1.0
	*/
	function save_meta_boxes ( $post_ID ) {
		global $post, $wpdb;
		if ( $post->post_type == self::post_type ) {
			foreach( $_POST as $key => $val ) {
				if ( strstr( $key, self::nspace ) ) {
					update_post_meta( $post_ID, $key, $val );
				}
			}
		}
	}
	
	/**
	*Job Page Title
	*
	*@return string
	*@since 1.0
	*/
	function job_board_pagetitle( $echo = true) {
		if ( $echo == true ) {
			if ( is_search() ) {
				$search_query = get_search_query();
				print __( "Jobs with keyword '" . $search_query . "'", self::nspace );
			} else if ( is_tax( 'job-type' ) ) {
				$term = get_queried_object();
				$tax = get_taxonomy( $term->taxonomy );
				$title = single_term_title( $tax->labels->name, false );
				print __( $title . ' Jobs', self::nspace );
			} else {
				print __( 'Jobs', self::nspace );
			}
		}
		else return $title;	
	}
	
	/**
	*Job Type Dropdown
	*
	*@return string
	*@since 1.0
	*/
	function get_job_type_dropdown( $taxonomy = 'job-type', $args = '' ) {
		$myterms = get_terms( $taxonomy, $args );
		$term_query = get_query_var( 'job-type' );
		$output = '<select class="job-select-dropdown" name="' . $taxonomy . '" />';
		if ( is_tax( 'job-type' ) ) {
			$output .= '<option value="' . get_post_type_archive_link( 'job_board_job' ) . '">' . __( 'Show All Jobs', self::nspace ) . '</option>';
		} else {
			$output .= '<option value="' . get_post_type_archive_link( 'job_board_job' ) . '">' . __( 'Job Category', self::nspace ) . '</option>';
		}
		foreach( $myterms as $term ){
			$root_url = get_bloginfo('url');
			$term_taxonomy = $term->taxonomy;
			$term_slug = $term->slug;
			$term_name = $term->name;
			$link = $root_url . '/' . $term_taxonomy . '/' . $term_slug;
			$selected = '';
			if ( $term_query == $term_slug ) $selected = ' selected="selected"';
			$output .= '<option value="' . $link . '"' . $selected . '>' . $term_name . '</option>';
		}
		$output .='</select>';
		return $output;
	}
	
	/**
	*Get template
	*
	*@return string
	*@since 1.0
	*/
	function get_custom_post_type_template( $template ) {
		global $post, $wp_query;
		$query_types = $wp_query->query_vars['post_type'];
		if ( is_search() && in_array( self::post_type, $query_types ) ) {
			$template = $this->get_plugin_path() . '/views/job-list.php';
		} else if ( is_tax( 'job-type' ) || $post->post_type == self::post_type && is_post_type_archive( self::post_type ) ) {
			$template = $this->get_plugin_path() . '/views/job-list.php';
		} else if ( $post->post_type == self::post_type && is_singular( self::post_type ) ) {
			$template = $this->get_plugin_path() . '/views/job-single.php';
		}
		return $template;		
	}

	/**
        *Get post type
        *
        *@return string
        *@since 1.0
        */
        function get_post_type() {
		return self::post_type;
	}

	/**
        *Get namespace
        *
        *@return string
        *@since 1.0
        */
        function get_name_space() {
		return self::nspace;
	}

	/**
        *Get taxonomy
        *
        *@return string
        *@since 1.0
        */
        function get_taxonomy() {
		return self::taxonomy;
	}
}

?>
