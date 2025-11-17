<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Shop;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shops = Shop::with('user')->get();

        return view('admin.shops.index', ['shops' => $shops]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->role == 'vendor' || $user->shop()->exists()) {
             return redirect('/')->with('error', 'Anda sudah memiliki toko.');
        }
        
        return view('user.shops.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 3. Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // 4. Tambahkan ID user yang sedang login
        $validatedData['user_id'] = Auth::id();

        // 5. Simpan ke database
        Shop::create($validatedData);

        // 6. Redirect ke dashboard
        return redirect()->route('dashboard')
             ->with('success', 'Permohonan toko Anda telah terkirim. Mohon tunggu persetujuan Admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        //
    }

    public function update(Request $request, Shop $shop)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Cek jika user adalah admin DAN request-nya berisi 'status'
        if ($user->role == 'admin' && $request->has('status')) {
            
            $request->validate([
                'status' => 'required|in:approved,rejected',
            ]);

            if ($request->status == 'approved' && $shop->status == 'pending') {
                // Ambil user pemilik toko
                $owner = $shop->user; 
                
                // Ubah role user tersebut menjadi 'vendor'
                $owner->update(['role' => 'vendor']); 
            }

            $shop->update([
                'status' => $request->status
            ]);

            return redirect()->route('admin.shops.index')->with('success', 'Status toko berhasil diperbarui.');
        }

        // Cek jika user adalah pemilik toko ini
        if ($user->id == $shop->user_id) {
            
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $shop->update($validatedData); 

            return redirect('/')->with('success', 'Detail toko berhasil diperbarui.');
        }

        return redirect('/')->with('error', 'Anda tidak memiliki izin untuk melakukan aksi ini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        //
    }
}
