<x-mail::message>
# Invoice Pesanan #{{ $order->id }}

Halo {{ $order->user->name }},

Pesanan Anda telah diproses dan sedang dalam pengiriman (atau telah selesai).
Terima kasih telah berbelanja di {{ config('app.name') }}.

Berikut adalah detail pesanan Anda:
**Total:** Rp {{ number_format($order->total_amount, 0, ',', '.') }}
**Alamat Kirim:** {{ $order->shipping_address }}

Invoice PDF terlampir dalam email ini.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>