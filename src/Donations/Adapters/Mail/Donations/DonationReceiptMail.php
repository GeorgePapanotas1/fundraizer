<?php

namespace Fundraiser\Donations\Adapters\Mail\Donations;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly Campaign $campaign,
        public readonly int $amountCents,
        public readonly string $currency,
        public readonly ?string $reference,
    ) {}

    public function envelope(): Envelope
    {
        $subject = __('Donation receipt for ":title"', ['title' => $this->campaign->title]);

        return new Envelope(
            to: [new Address($this->user->email, $this->user->name)],
            subject: $subject
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.donations.receipt',
            with: [
                'user' => $this->user,
                'campaign' => $this->campaign,
                'amount' => round($this->amountCents / 100, 2),
                'currency' => $this->currency,
                'reference' => $this->reference,
            ],
        );
    }
}
