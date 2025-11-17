<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdfPath;

    // Terima Order DAN path PDF
    public function __construct(Order $order, string $pdfPath)
    {
        $this->order = $order;
        $this->pdfPath = $pdfPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice untuk Pesanan #' . $this->order->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.invoice',
        );
    }

    // Lampirkan PDF dari storage
    public function attachments(): array
    {
        return [
            Attachment::fromPath(Storage::disk('public')->path($this->pdfPath))
                ->as('Invoice-' . $this->order->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}