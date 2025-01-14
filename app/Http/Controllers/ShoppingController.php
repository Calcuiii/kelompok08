<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class ShoppingController extends Controller
{
    // Menampilkan halaman belanja dengan daftar produk yang tersedia
    public function shopping()
    {
        $pageTitle = 'Shopping';
        // Ambil produk yang memiliki stok lebih dari 0
        $products = Product::where('stock', '>', 0)->get();
        
        // Mengembalikan tampilan halaman belanja dengan produk yang tersedia
        return view('shopping.index', compact('pageTitle', 'products'));
    }

    // Proses pembelian produk
    public function buy(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'product_id' => 'required|exists:products,id',  // Pastikan produk ada
            'quantity' => 'required|integer|min:1',         // Pastikan quantity adalah integer dan minimal 1
        ]);

        // Ambil produk berdasarkan ID yang diberikan
        $product = Product::find($request->product_id);

        // Cek jika stok produk mencukupi
        if ($product->stock < $request->quantity) {
            return redirect()->back()->withErrors(['error' => 'Stok produk tidak mencukupi.']);
        }

        // Simpan data pemesanan ke dalam tabel Order
        $order = Order::create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'total_price' => $product->price * $request->quantity,
        ]);

        // Kurangi stok produk sesuai dengan quantity yang dibeli
        $product->stock -= $request->quantity;
        $product->save();

        // Setelah pemesanan berhasil, alihkan pengguna ke halaman struk
        return redirect()->route('shopping.receipt', ['id' => $order->id]);
    }

    // Menghasilkan struk pembelian dalam format PDF
    public function generateReceipt($id)
    {
        // Ambil data pemesanan berdasarkan ID
        $order = Order::findOrFail($id);
        $product = $order->product; // Ambil data produk yang dipesan

        // Menggunakan dompdf untuk memuat tampilan dan menghasilkan PDF
       // $pdf = PDF::loadView('shopping.receipt', compact('order', 'product'));

        // Download PDF sebagai file dengan nama 'receipt.pdf'
       // return $pdf->download('receipt.pdf');
    }
}