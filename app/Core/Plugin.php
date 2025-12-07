<?php

namespace EHXDonate\Core;

use EHXDonate\Routes\Router;
use EHXDonate\Database\Database;
use EHXDonate\Services\ServiceContainer;
use EHXDonate\Core\Action;
use EHXDonate\Helpers\Currency;
use EHXDonate\Helpers\Country;
use EHXDonate\Helpers\Font;
use EHXDonate\Routes\WebRoute;

/**
 * Main Plugin class - Bootstrap and service container
 */
class Plugin
{
    /**
     * Plugin instance
     */
    protected static $instance;

    /**
     * Service container
     */
    protected $container;

    /**
     * Router instance
     */
    protected $router;

    /**
     * Database instance
     */
    protected $database;

    /**
     * Plugin version
     */
    protected $version;

    /**
     * Action instance
     */
    protected $action;

    /**
     * Constructor
     */
    protected function __construct()
    {
        $this->version = EHXDonate_VERSION;
        $this->container = new ServiceContainer();
        $this->router = new Router();
        $this->database = new Database();
        (new Action())->register();
        
        $this->registerServices();
        $this->init();
        add_action('plugin_loaded',  function () {
            (new WebRoute())->register();
        });
    }

    /**
     * Get plugin instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    /**
     * Register services in the container
     */
    protected function registerServices(): void
    {
        // Register core services
        $this->container->singleton('router', function() {
            return $this->router;
        });
        
        $this->container->singleton('database', function() {
            return $this->database;
        });
        
        $this->container->singleton('plugin', function() {
            return $this;
        });
    }

    /**
     * Initialize the plugin
     */
    protected function init(): void
    {
        // Load text domain
        add_action('init', [$this, 'loadTextDomain']);
        
        // Register routes
        add_action('init', [$this, 'registerRoutes']);
        
        // Register admin menu
        add_action('admin_menu', [$this, 'registerAdminMenu']);
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueuePublicAssets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);
        
        // Register AJAX handlers
        add_action('wp_ajax_exh_donate_action', [$this, 'handleAjaxRequest']);
        add_action('wp_ajax_nopriv_exh_donate_action', [$this, 'handleAjaxRequest']);
        
        // Initialize components
        $this->initializeComponents();
        
        // Register shortcodes
        add_action('init', ['EHXDonate\\Controllers\\ShortcodeController', 'register']);
    }

    /**
     * Load text domain for translations
     */
    public function loadTextDomain(): void
    {
        load_plugin_textdomain(
            'ehx-donate',
            false,
            dirname(EHXDonate_BASENAME) . '/languages'
        );
    }

    /**
     * Register routes
     */
    public function registerRoutes(): void
    {
        // Register API routes
        \EHXDonate\Routes\ApiRoutes::register();
        
        // Register REST API routes
        $this->router->registerRestRoutes('ehx-donate/v1');
        
        // Register admin routes
        $this->router->registerAdminRoutes();
    }

    /**
     * Register admin menu
     */
    public function registerAdminMenu(): void
    {
        global $submenu;
        add_menu_page(
            'ehx-donate',
            __('EHx Donate', 'ehx-donate'),
            'manage_options',
            'ehx-donate.php',
            array($this, 'renderAdminPage'),
            'dashicons-groups',
            24
        );

        $submenu['ehx-donate.php']['dashboard'] = array(
            __('Dashboard', 'ehx-donate'),
            'manage_options',
            'admin.php?page=ehx-donate.php#/dashboard',
        );
    }

    /**
     * Render admin page
     */
    public function renderAdminPage(): void
    {
        echo '<div id="ehx-donate-admin"></div>';
    }

    /**
     * Render settings page
     */
    public function renderSettingsPage(): void
    {
        echo '<div class="wrap">';
        echo '<h1>' . __('My Plugin Settings', 'ehx-donate') . '</h1>';
        echo '<div id="ehx-donate-settings"></div>';
        echo '</div>';
    }

    /**
     * Enqueue public assets
     */
    public function enqueuePublicAssets(): void
    {
        wp_enqueue_style(
            'ehx-donate-public',
            EHXDonate_URL . 'public/css/public.css',
            [],
            $this->version
        );
        
        wp_enqueue_script(
            'ehx-donate-public',
            EHXDonate_URL . 'public/js/public.js',
            ['jquery'],
            $this->version,
            true
        );
    }

    /**
     * Enqueue admin assets
     */
    public function enqueueAdminAssets(string $hook): void
    {
        // Only load on our admin pages
        if (strpos($hook, 'ehx-donate') === false) {
            return;
        }
        
        wp_enqueue_style(
            'ehx-donate-admin',
            EHXDonate_URL . 'assets/css/admin.css',
            [],
            $this->version
        );

        // wp media enqueue
        wp_enqueue_media();
        wp_enqueue_script('wp-tinymce');
        wp_enqueue_editor();

        wp_enqueue_script(
            'ehx-donate-admin',
            EHXDonate_URL . 'assets/js/admin.js',
            ['wp-api-fetch', 'wp-blocks', 'wp-editor', 'wp-components', 'wp-element', 'wp-data', 'wp-i18n', 'jquery'],
            $this->version,
            true
        );
         wp_enqueue_media();
        // Localize script with data
        wp_localize_script('ehx-donate-admin', 'EHXDonate', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url('ehx-donate/v1/'),
            'restNonce' => wp_create_nonce('wp_rest'),
            'has_recurring_donation' => defined('EHXRD_VERSION') ? true : false,

            // 'nonce' => wp_create_nonce('exh_donate_nonce'),
            'strings' => [
                'loading' => __('Loading...', 'ehx-donate'),
                'error' => __('An error occurred', 'ehx-donate'),
                'success' => __('Success!', 'ehx-donate'),
            ],
            // get all categories from database
            'categories' => get_terms([
                'taxonomy' => 'category',
                'hide_empty' => false,
            ]),
            'tags' => get_terms([
                'taxonomy' => 'post_tag',
                'hide_empty' => false,
            ]),
            'currencies' => Currency::getAll(),
            'currencySymbols' => Currency::getCurrencySymbol(''),
            'countries' => Country::getAll(),
            'fonts' => Font::getAll(),
        ]);
    }

    /**
     * Handle AJAX requests
     */
    public function handleAjaxRequest(): void
    {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'exh_donate_nonce')) {
            wp_die('Security check failed');
        }
        
        $action = $_POST['action'] ?? '';
        $method = $_POST['method'] ?? 'GET';
        
        // Route to appropriate handler
        $this->router->handle($method, '/ajax/' . $action);
    }

    /**
     * Initialize components
     */
    protected function initializeComponents(): void
    {
        // Initialize services
        $services = [
            'EHXDonate\\Services\\TripService',
            'EHXDonate\\Services\\UserService',
        ];
        
        foreach ($services as $service) {
            if (class_exists($service)) {
                $this->container->singleton($service, function() use ($service) {
                    return new $service();
                });
            }
        }
    }

    /**
     * Get service from container
     */
    public function get(string $service)
    {
        return $this->container->get($service);
    }

    /**
     * Get router instance
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * Get database instance
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * Get plugin version
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Plugin activation
     */
    public static function activate(): void
    {
        // Create database tables
        $database = new Database();
        
        // Run migrations
        $migrations = [
            'EHXDonate\\Database\\Migrations\\CreateTripsTable',
            'EHXDonate\\Database\\Migrations\\CreateActivityLogTable',
            'EHXDonate\\Database\\Migrations\\CreateCampaignTable',
            // 'EHXDonate\\Database\\Migrations\\CreateCurrencyTable',
            'EHXDonate\\Database\\Migrations\\CreateDonationTable',
            'EHXDonate\\Database\\Migrations\\CreateDonationItemTable',
            'EHXDonate\\Database\\Migrations\\CreateDonorTable',
            'EHXDonate\\Database\\Migrations\\CreateMetaTable',
            'EHXDonate\\Database\\Migrations\\CreateSubscriptionTable',
            'EHXDonate\\Database\\Migrations\\CreateTransactionTable',
        ];
        
        foreach ($migrations as $migration) {
            if (class_exists($migration)) {
                $migrationInstance = new $migration();
                $migrationInstance->up();
            }
        }
        
        // Set activation flag
        update_option('exh_donate_activated', true);
        update_option('exh_donate_version', EHXDonate_VERSION);
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public static function deactivate(): void
    {
        // Remove activation flag
        delete_option('exh_donate_activated');
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin uninstall
     */
    public static function uninstall(): void
    {
        // Drop database tables
        $database = new Database();
        
        $tables = [
            'ehxdo_trips',
            'ehxdo_activity_log',
        ];
        
        foreach ($tables as $table) {
            $database->dropTable($table);
        }
        
        // Remove all plugin options
        delete_option('exh_donate_version');
        delete_option('exh_donate_settings');
    }
}
