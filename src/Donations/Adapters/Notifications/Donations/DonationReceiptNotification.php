<?php

namespace Fundraiser\Donations\Adapters\Notifications\Donations;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Donations\Adapters\Mail\Donations\DonationReceiptMail;
use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DonationReceiptNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected readonly User $user,
        protected readonly Campaign $campaign,
        protected readonly int $amountCents,
        protected readonly string $currency,
        protected readonly ?string $reference,
    ) {}

    /**
     * @return string[]
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): DonationReceiptMail
    {
        return new DonationReceiptMail(
            user: $this->user,
            campaign: $this->campaign,
            amountCents: $this->amountCents,
            currency: $this->currency,
            reference: $this->reference,
        );
    }
}
