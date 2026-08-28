<?php

namespace App\Notifications\User;

use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WalletTransactionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public WalletTransaction $transaction) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $isCredit = $this->transaction->type === WalletTransaction::TYPE_CREDIT;

        // Translation keys + interpolation data, not a hardcoded English
        // string — the frontend renders this via its i18n system
        // (t('notifications.wallet_credit.title'), etc., see
        // FRONTEND_LOCALIZATION.md), and the API also includes a
        // server-rendered message in the recipient's current locale so
        // clients that haven't wired up the key-based lookup still get
        // something readable immediately.
        $titleKey = $isCredit ? 'notifications.wallet_credit.title' : 'notifications.wallet_debit.title';
        $bodyKey = $isCredit ? 'notifications.wallet_credit.body' : 'notifications.wallet_debit.body';
        $params = ['amount' => number_format((float) $this->transaction->amount, 2)];

        return [
            'event' => $isCredit ? 'wallet_credit' : 'wallet_debit',
            'title_key' => $titleKey,
            'body_key' => $bodyKey,
            'params' => $params,
            'title' => __($titleKey, $params),
            'body' => __($bodyKey, $params),
            'data' => [
                'transaction_id' => $this->transaction->id,
                'wallet_id' => $this->transaction->wallet_id,
                'amount' => (float) $this->transaction->amount,
                'order_id' => $this->transaction->order_id,
            ],
        ];
    }
}
