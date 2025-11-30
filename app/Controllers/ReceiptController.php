<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Donation;
use EHXDonate\Models\Campaign;
use EHXDonate\View;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Receipt Controller
 */
class ReceiptController extends Controller
{
    public function downloadReceipt($donation_id)
    {
        $donation = Donation::find($donation_id);
        if (!$donation) {
            $this->error('Donation not found', 404);
            return;
        }

        $campaign = Campaign::find($donation->campaign_id);
        if (!$campaign) {
            $this->error('Campaign not found', 404);
            return;
        }
        $general_settings = get_option('ehx_donate_settings_general', []);

        $html = View::make('donation-receipt', [
            'donation' => $donation->toArray(),
            'campaign' => $campaign->toArray(),
            'general_settings' => $general_settings,
        ]);
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('debugKeepTemp', true); // For debugging
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $output = $dompdf->output();
        // Validate PDF output
        if (empty($output)) {
            return response()->json(['error' => 'PDF generation failed'], 500);
        }
        
        // Check if output starts with PDF header
        if (substr($output, 0, 4) !== '%PDF') {
            return response()->json(['error' => 'Invalid PDF generated'], 500);
        }

        return wp_send_json([
            'pdf' => base64_encode($output),
            'filename' => 'donation_receipt_' . $donation_id . '.pdf'
        ]);
    }
}