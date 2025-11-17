<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <h1 class="text-2xl font-semibold text-th-foreground mb-6">Manajemen Persetujuan Toko</h1>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-500/10 p-4 text-sm text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-500/10 p-4 text-sm text-red-300">
                {{ session('error') }}
            </div>
        @endif

        @if ($shops->isEmpty())
            <div class="border border-th-border rounded-lg p-6 text-center">
                <p class="text-th-muted">Belum ada toko yang perlu persetujuan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-th-border border border-th-border rounded-lg">
                    <thead class="bg-th-border/20">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Nama Toko</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Pemilik (Vendor)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Kota</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-th-background divide-y divide-th-border">
                        @foreach ($shops as $shop)
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-500/10 text-yellow-300',
                                    'approved' => 'bg-green-500/10 text-green-300',
                                    'rejected' => 'bg-red-500/10 text-red-300',
                                ];
                            @endphp
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-th-foreground">{{ $shop->name }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-th-foreground">{{ $shop->user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-th-muted">{{ $shop->city }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClasses[$shop->status] ?? 'bg-th-border text-th-muted' }}">
                                        {{ strtoupper($shop->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    @if ($shop->status == 'pending')
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <form action="{{ route('admin.shops.update', $shop->id) }}" 
                                                  method="POST" 
                                                  class="m-0"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menyetujui toko ini?');">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" 
                                                        class="bg-green-500/10 text-green-300 font-medium rounded-lg px-3 py-1.5 text-xs hover:bg-green-500/20 transition-colors">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.shops.update', $shop->id) }}" 
                                                  method="POST" 
                                                  class="m-0"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menolak toko ini?');">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" 
                                                        class="bg-red-500/10 text-red-400 font-medium rounded-lg px-3 py-1.5 text-xs hover:bg-red-500/20 transition-colors">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-th-muted text-xs">Tidak ada aksi</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</x-app-layout>