<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Transaction;
use EHXDonate\Helpers\Currency;
use EHXDonate\Models\Campaign;
use EHXDonate\Models\Donor;

/**
 * Transaction Controller
 */
class TransactionController extends Controller
{
    public function index(): void
    {
        $data = $this->request;

        $page = 1;
        $limit = 10;
        $search = null;
        $status = null;

        if ($data['page']) {
            $page = intval($data['page']);
        }
        if ($data['limit']) {
            $limit =  intval($data['limit']);
        }
        if ($data['search']) {
            $search = sanitize_text_field($data['search']);
        }

        $res = (new Transaction())->orderBy('created_at', 'desc')->paginate($limit, $page, $search, $status);

        $data = array_map(function ($donor) {
            $donorArray = $donor->with('campaign')->with('donor')->toArray();
            $donorArray['donor_name'] = $donorArray['donor']['first_name'] . ' ' . $donorArray['donor']['last_name'];
            $donorArray['campaign_name'] = $donorArray['campaign']['title'];
            return $donorArray;
        }, $res['data']);

        $generalSettings = get_option('ehx_donate_settings_general', []);

        $this->success([
            'transactions' => $data,
            'generalSettings' => $generalSettings,
            'total' => $res['total'],
            'per_page' => $res['per_page'],
            'current_page' => $res['current_page'],
            'last_page' => $res['last_page'],
        ]);
    }

    public function destroy(int $id): void
    {
        $this->requireAuth();

        $transaction = Transaction::find($id);

        if (!$transaction) {
            $this->error('Transaction not found', 404);
            return;
        }
        if ($transaction->user_id !== $this->getCurrentUserId() && !$this->can('manage_options')) {
            $this->error('Unauthorized', 403);
            return;
        }

        $transaction->delete();

        $this->success([], 'Transaction deleted successfully');
    }

    public function export_transaction_csv()
    {
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=transactions-' . date('Y-m-d-H-i-s') . '.csv');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Create output stream
        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8 encoding
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Add CSV headers
        fputcsv($output, array(
            'ID',
            'Donor',
            'Campaign',
            'Amount',
            'Status',
            'Mode',
            'Date',
        ));

        // Get general settings for currency formatting
        $generalSettings = get_option('ehx_donate_settings_general', []);
        $currency = $generalSettings['currency'] ?? 'GBP';
        $currencySymbols = Currency::getCurrencySymbol('');
        $symbol = $currencySymbols[$currency] ?? $currency;

        // Fetch all transactions
        $transactions = (new Transaction())->orderBy('created_at', 'desc')->get();

        // Process each transaction (same pattern as index method)
        foreach ($transactions as $transaction) {
            // Load relations for this transaction
            $transactionArray = $transaction->with('campaign')->with('donor')->toArray();

            // Get donor name
            $donorName = 'N/A';
            if (!empty($transactionArray['donor'])) {
                $donorName = trim(
                    $transactionArray['donor']['first_name'] . ' ' .
                        $transactionArray['donor']['last_name']
                );
            }

            // Get campaign name
            $campaignName = !empty($transactionArray['campaign'])
                ? $transactionArray['campaign']['title']
                : 'N/A';

            // Format date
            $createdDate = !empty($transaction->created_at)
                ? date('d/m/Y', strtotime($transaction->created_at))
                : 'N/A';

            // Write row to CSV
            fputcsv($output, array(
                $transaction->id,
                $donorName,
                $campaignName,
                $symbol . ' ' . number_format($transaction->payment_total ?? 0, 2),
                ucfirst($transaction->status ?? 'N/A'),
                ucfirst($transaction->payment_mode ?? 'N/A'),
                $createdDate,
            ));
        }

        fclose($output);
        exit();
    }
}
