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
        $router->group(['prefix' => '/api'], function ($router) {
            // Public campaign routes
            $router->get('/getAllCampaigns', 'CampaignController@index');
            $router->get('/campaigns/{id}', 'CampaignController@show');
            $router->post('/campaigns', 'CampaignController@store', ['auth']);
            $router->put('/campaigns/{id}', 'CampaignController@update', ['auth']);
            $router->delete('/deleteCampaign/{id}', 'CampaignController@destroy', ['auth']);
            $router->post('/updateCampaignStatus/{id}', 'CampaignController@updateCampaignStatus', ['auth']);
             $router->get('/export-campaigns', 'CampaignController@export_campaigns_csv', ['auth']);

            //Donor routes
            $router->get('/getAllDonors', 'DonorController@index', ['auth']);
            $router->delete('/deleteDonor/{id}', 'DonorController@destroy', ['auth']);
            $router->get('/export-donor', 'DonorController@export_donor_csv', ['auth']);

            //Donation routes
            $router->get('/getAllDonations', 'DonationController@index', ['auth']);
            $router->get('/donations/{id}', 'DonationController@show', ['auth']);
            $router->delete('/deleteDonation/{id}', 'DonationController@destroy', ['auth']);
            $router->get('/export-donation', 'DonationController@export_donation_csv', ['auth']);

            // Settings routes
            $router->get('/settings/{key}', 'SettingsController@getSettings');
            $router->post('/settings/{key}', 'SettingsController@updateSettings');
        });

        $router->group(['prefix' => '/payment'], function ($router) {
            $router->get('/stripe/success', 'PaymentController@stripeSuccess', [], ['public' => true]);
            $router->get('/stripe/cancel', 'PaymentController@stripeCancel', [], ['public' => true]);
        });
        $router->get('/receipt/download/{donation_id}', 'ReceiptController@downloadReceipt', [], ['public' => true]);
    }
}
