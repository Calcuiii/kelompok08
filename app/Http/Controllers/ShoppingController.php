<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Dompdf\Dompdf;

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

    // Menambahkan produk ke dalam cart
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $product = Product::find($productId);

        // Ambil cart dari session, jika tidak ada maka buat array kosong
        $cart = $request->session()->get('cart', []);

        // Cek apakah produk sudah ada di dalam cart
        if (isset($cart[$productId])) {
            // Jika produk sudah ada, update quantity
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // Jika produk belum ada, tambahkan produk baru ke cart
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
            ];
        }

        // Simpan cart yang sudah diperbarui ke dalam session
        $request->session()->put('cart', $cart);

        // Redirect kembali ke halaman shopping dan beri pesan sukses
        return redirect()->route('shopping')->with('success', 'Product added to cart');
    }

    // Proses pembelian produk dan menyimpan order
    public function buy(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'product_id' => 'required|exists:products,id',  // Pastikan produk ada
            'quantity' => 'required|integer|min:1',         // Pastikan quantity adalah integer dan minimal 1
        ]);

        // Ambil produk berdasarkan ID yang diberikan
        $product = Product::find($request->product_id);

        // Cek apakah produk ada
        if (!$product) {
            return redirect()->back()->withErrors(['error' => 'Produk tidak ditemukan.']);
        }

        // Cek jika stok produk mencukupi
        if ($product->stock < $request->quantity) {
            return redirect()->back()->withErrors(['error' => 'Stok produk tidak mencukupi.']);
        }

        // Simpan data pemesanan ke dalam tabel Order
        try {
            $order = Order::create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $product->price * $request->quantity,
            ]);
        } catch (\Exception $e) {
            // Tangani jika ada error saat penyimpanan order
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memproses pesanan.']);
        }

        // Kurangi stok produk sesuai dengan quantity yang dibeli
        $product->stock -= $request->quantity;
        $product->save();

        // Setelah pemesanan berhasil, alihkan pengguna untuk mendownload struk
        return redirect()->route('shopping.receipt', ['id' => $order->id]);
    }

    // Menghasilkan struk pembelian dalam format PDF
    public function generateReceipt($id, Request $request)
    {
        // Ambil data pemesanan berdasarkan ID
        $order = Order::findOrFail($id);
        $product = $order->product; // Ambil data produk yang dipesan

        // Membuat instance Dompdf
        $dompdf = new Dompdf();

        // Mengatur opsi Dompdf jika diperlukan (misalnya ukuran kertas atau orientasi)
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf->setOptions($options);

        // Memuat view untuk struk dan mengonversinya ke HTML
        $html = view('shopping.receipt', compact('order', 'product'))->render();

        // Memuat HTML ke dalam Dompdf
        $dompdf->loadHtml($html);

        // (Opsional) Menentukan ukuran dan orientasi kertas
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF dari HTML
        $dompdf->render();

        // Menyediakan file PDF untuk diunduh
        return $dompdf->stream('receipt.pdf');
    }

    // Mengunduh PDF dari cart yang ada di session
    public function downloadPdf(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shopping')->with('error', 'Keranjang Anda kosong.');
        }

        // Hitung total harga
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Render HTML untuk tampilan PDF
        $html = view('shopping.pdf', compact('cart', 'total'))->render();

        // Buat instance Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // (Optional) Mengatur ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Download PDF
        return $dompdf->stream('cart.pdf');
    }
}
