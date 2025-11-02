<?php

namespace EHXDonate\Routes;

use EHXDonate\Core\Plugin;

/**
 * API Routes
 */
class ApiRoutes
{
    /**
     * Register API routes
     */
    public static function register(): void
    {
        $plugin = Plugin::getInstance();
        $router = $plugin->getRouter();
        
        // Trip routes
        $router->group(['prefix' => '/api'], function($router) {
            // Public trip routes
            $router->get('/trips', 'TripController@index');
            $router->get('/trips/upcoming', 'TripController@upcoming');
            $router->get('/trips/past', 'TripController@past');
            $router->get('/trips/status/{status}', 'TripController@byStatus');
            $router->get('/trips/{id}', 'TripController@show');
            
            // Protected trip routes
            $router->post('/trips', 'TripController@store', ['auth']);
            $router->put('/trips/{id}', 'TripController@update', ['auth']);
            $router->delete('/trips/{id}', 'TripController@destroy', ['auth']);
            $router->get('/my-trips', 'TripController@myTrips', ['auth']);
        });

        // Campaign routes
        $router->group(['prefix' => '/api'], function($router) {
            // Public campaign routes
            $router->get('/getAllCampaigns', 'CampaignController@index');
            $router->get('/getActiveCampaigns', 'CampaignController@active');
            $router->get('/getCompletedCampaigns', 'CampaignController@completed');
            $router->get('/getCampaign/{id}', 'CampaignController@show');
            
            // Protected campaign routes
            $router->post('/campaigns', 'CampaignController@store', ['auth']);
            $router->put('/campaigns/{id}', 'CampaignController@update', ['auth']);
            $router->delete('/campaigns/{id}', 'CampaignController@destroy', ['auth']);
        });
    }
}
