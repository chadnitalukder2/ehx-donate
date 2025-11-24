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
        
 //donation routes
            $router->post('/donationSubmission', 'DonationController@store');
        // Campaign routes
        $router->group(['prefix' => '/api'], function($router) {
            // Public campaign routes
            $router->get('/getAllCampaigns', 'CampaignController@index'); 
            $router->get('/campaigns/{id}', 'CampaignController@show');
            $router->post('/campaigns', 'CampaignController@store', ['auth']);  
            $router->put('/campaigns/{id}', 'CampaignController@update', ['auth']); 
            $router->delete('/deleteCampaign/{id}', 'CampaignController@destroy', ['auth']);
            $router->post('/updateCampaignStatus/{id}', 'CampaignController@updateCampaignStatus', ['auth']);
            
            $router->get('/settings/{key}', 'SettingsController@getSettings');
            $router->post('/settings/{key}', 'SettingsController@updateSettings');
        });
    }
}
