<?php
/**
 * Plugin class.
 *
 * @package    PRC\Platform\Page_Like_Types
 */

namespace PRC\Platform\Page_Like_Types;

/**
 * Plugin class.
 *
 * @package    PRC\Platform\Page_Like_Types
 */
class Plugin {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The registry.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Registry    $registry    The registry.
	 */
	protected $registry;

	/**
	 * Define the core functionality of the platform as initialized by hooks.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->version     = '1.0.0';
		$this->plugin_name = 'prc-page-like-types';

		$this->load_dependencies();
	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		// Load plugin loading class.
		require_once plugin_dir_path( __DIR__ ) . '/includes/class-loader.php';

		// Initialize the loader.
		$this->loader = new Loader();

		// Load dependencies.
		require_once plugin_dir_path( __DIR__ ) . '/includes/class-registry.php';
		require_once plugin_dir_path( __DIR__ ) . '/includes/class-admin-dataview-lists.php';

		// Initialize the dependencies.
		$this->init_dependencies();
	}

	/**
	 * Initialize the dependencies.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function init_dependencies() {
		$this->registry = new Registry( $this->get_loader() );
		new Admin_Dataview_Lists( $this->get_loader() );

		// Courses.
		$this->registry->register(
			// Basic registration args, covering high level details like the post type slug, the label prefix for singular and plural labels and the description.
			array(
				'slug'        => 'mini-course',
				'singular'    => 'Course',
				'plural'      => 'Courses',
				'description' => 'Educational courses and training materials designed to help journalists, researchers, and professionals understand research methodologies, data analysis, and survey techniques. Includes self-paced modules, workshop materials, and methodological resources from Pew Research Center experts.',
			),
			// Post type args, covering more specific details like the rewrite slug.
			array(
				'rewrite'   => array(
					'slug' => 'course',
				),
				'menu_icon' => 'dashicons-welcome-learn-more',
			),
			array( 'datasets', 'collections', 'bylines' )
		);

		// Events.
		$this->registry->register(
			array(
				'slug'        => 'events',
				'singular'    => 'Event',
				'plural'      => 'Events',
				'description' => 'Events including research briefings, panel discussions, expert presentations, and virtual seminars where Pew Research Center shares findings, facilitates policy discussions, and engages with stakeholders, researchers, and the public.',
			),
			array(
				'rewrite'   => array(
					'slug' => 'event',
				),
				'menu_icon' => 'dashicons-calendar-alt',
			),
		);

		// Fact Sheets.
		$this->registry->register(
			array(
				'slug'        => 'fact-sheet',
				'singular'    => 'Fact Sheet',
				'plural'      => 'Fact Sheets',
				'description' => 'Concise, data-driven summaries that present key findings and trends on specific topics, featuring essential statistics, charts, and research highlights from Pew Research Center. Fact sheets serve as authoritative quick-reference resources for journalists, policymakers, and the public seeking verified data on social issues, public opinion, and demographic trends.',
				'pub_listing' => true,
			),
			array(
				'rewrite' => array(
					'slug' => 'fact-sheet',
				),
			),
			array( 'datasets', 'collections', 'bylines' )
		);

		// Add custom fields to the standard page post type.
		$this->loader->add_action( 'init', $this, 'add_custom_fields_to_page' );
	}

	/**
	 * Add custom fields to the standard page post type.
	 *
	 * @hook init
	 *
	 * @return void
	 */
	public function add_custom_fields_to_page() {
		add_post_type_support( 'page', 'custom-fields' );
		add_post_type_support( 'page', 'comments' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    PRC\Platform\Page_Like_Types\Loader
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
