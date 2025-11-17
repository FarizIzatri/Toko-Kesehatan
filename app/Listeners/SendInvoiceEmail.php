<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Mail\InvoiceMail;
use App\Models\Report;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SendInvoiceEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(OrderCompleted $event): void
    {
        $order = $event->order;

        // 1. Load semua relasi yang dibutuhkan
        $order->load(['user', 'orderItems.product']);

        // 2. Buat PDF dari view
        $pdf = Pdf::loadView('pdf.invoice', [
            'order' => $order
        ]);

        // 3. Buat nama file unik dan simpan ke storage
        $path = 'invoices/order-' . $order->id . '_' . time() . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        // 4. Kirim email DAN berikan path PDF-nya
        try {
            Mail::to($order->user->email)->send(new InvoiceMail($order, $path));
            
            // 5. Jika berhasil, catat ke tabel 'reports'
            Report::create([
                'order_id' => $order->id,
                'file_path' => $path,
                'email_delivery_status' => 'sent',
            ]);

        } catch (\Exception $e) {
            
            // 6. Jika gagal, catat juga
            Report::create([
                'order_id' => $order->id,
                'file_path' => $path,
                'email_delivery_status' => 'failed',
            ]);
        }
    }
}