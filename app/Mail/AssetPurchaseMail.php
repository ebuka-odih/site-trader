<?php

namespace App\Mail;

use App\Models\HoldingTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssetPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $portfolioSummary;

    /**
     * Create a new message instance.
     */
    public function __construct(HoldingTransaction $transaction, $portfolioSummary = null)
    {
        $this->transaction = $transaction;
        $this->portfolioSummary = $portfolioSummary;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Asset Purchase Confirmation - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.asset-purchase',
            with: [
                'transaction' => $this->transaction,
                'portfolioSummary' => $this->portfolioSummary,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
