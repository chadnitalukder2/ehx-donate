<?php

namespace EHXDonate\Routes;

use EHXDonate\Core\Plugin;

class WebRoute
{
    public function register()
    {
        add_action('init', [$this, 'addRoutes']);
        add_filter('query_vars', [$this, 'registerQueryVars']);
        add_action('template_redirect', [$this, 'handleRoutes']);
    }

    public function addRoutes()
    {
        // SUCCESS route with ID
        add_rewrite_rule(
            '^ehxdo-success/([0-9]+)/?$',
            'index.php?ehxdo_success=1&ehxdo_id=$matches[1]',
            'top'
        );

        // FAILED route with ID
        add_rewrite_rule(
            '^ehxdo-failed/([0-9]+)/?$',
            'index.php?ehxdo_failed=1&ehxdo_id=$matches[1]',
            'top'
        );
    }

    public function registerQueryVars($vars)
    {
        $vars[] = 'ehxdo_success';
        $vars[] = 'ehxdo_failed';
        $vars[] = 'ehxdo_id'; // <-- dynamic ID
        return $vars;
    }

    public function handleRoutes()
    {
        if (get_query_var('ehxdo_success')) {
            $this->renderSuccess();
            exit;
        }

        if (get_query_var('ehxdo_failed')) {
            $this->renderFailed();
            exit;
        }
    }

    private function renderSuccess()
    {
        $id = get_query_var('ehxdo_id'); // get dynamic ID

        status_header(200);
        echo do_shortcode('[ehxdo_receipt id="' . intval($id) . '"]');
    }

    private function renderFailed()
    {
        $id = get_query_var('ehxdo_id');

        status_header(200);
        echo do_shortcode('[ehxdo_failed_reciept id="' . intval($id) . '"]');
    }
}
