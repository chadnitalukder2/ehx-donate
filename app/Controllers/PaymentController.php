<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Donation;
use EHXDonate\Models\Transaction;
use EHXDonate\Services\Payment\Stripe;
use EHXDonate\Models\Campaign;

/**
 * Payment Controller
 */
class PaymentController extends Controller
{
    public function stripeSuccess()
    {
        global $wpdb;

        $session_id      = sanitize_text_field($_GET['session_id'] ?? '');
        $donation_id     = intval($_GET['donation_id'] ?? 0);

        if (!$session_id || !$donation_id) {
            return wp_send_json_error(['message' => 'Invalid request'], 400);
        }

        $donation = Donation::find($donation_id);
        if (!$donation) {
            return wp_send_json_error(['message' => 'Donation not found'], 404);
        }

        $stripe = new Stripe();
        $session = $stripe->getCheckoutSession($session_id);

        // create transaction
        $transaction = Transaction::create([
            'campaign_id' => $donation->campaign_id,
            'vendor_charge_id' => $session_id,
            'user_id' => $donation->user_id,
            'donation_id' => $donation->id,
            'payment_method' => 'stripe',
            'payment_method_type' => $session['payment_method_types'][0] ?? 'card',
            'rate' => 1,
            'card_last_4' => $session['payment_method_details']['card']['last4'] ?? '',
            'card_brand' => $session['payment_method_details']['card']['brand'] ?? '',
            'payment_total' => $donation->total_payment,
            'status' => 'completed',
            'currency' => $donation->currency,
            'payment_mode' => $donation->payment_mode,
            'reporting_total' => $donation->total_payment,
            'reporting_currency' => $donation->currency,
            'reporting_exchange_rate' => 1,
        ]);

        $donation->payment_status = 'completed';
        $donation->transaction_id = $transaction->id;
        $donation->save();

        self::redirectToCampaignPage($donation_id);
    }   

    public function stripeCancel()
    {
        $donation_id = intval($_GET['donation_id'] ?? 0);
        if (!$donation_id) {
            return wp_send_json_error(['message' => 'Invalid request'], 400);
        }

        $donation = Donation::find($donation_id);
        
        $donation['payment_status'] = 'failed';
        $donation->save();

        self::redirectToCampaignPage($donation_id);
    }

    public static function redirectToCampaignPage($donation_id)
    {
        $donation = Donation::find($donation_id);
        $campaign = Campaign::find($donation->campaign_id);
        if (!$campaign) {
            return wp_send_json_error(['message' => 'Campaign not found'], 404);
        }
        wp_redirect(get_permalink($campaign->post_id));
        exit();
    }
}