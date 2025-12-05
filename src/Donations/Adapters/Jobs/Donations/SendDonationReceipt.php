<?php

namespace Fundraiser\Donations\Adapters\Jobs\Donations;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Donations\Adapters\Notifications\Donations\DonationReceiptNotification;
use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDonationReceipt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly Campaign $campaign,
        public readonly int $amountCents,
        public readonly string $currency,
        public readonly ?string $reference,
    ) {}

    public function handle(): void
    {
        $this->user->notify(new DonationReceiptNotification(
            user: $this->user,
            campaign: $this->campaign,
            amountCents: $this->amountCents,
            currency: $this->currency,
            reference: $this->reference,
        ));
    }
}
