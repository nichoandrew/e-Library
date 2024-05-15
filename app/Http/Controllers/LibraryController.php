<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\mahasiswa;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index_admin () {
        $bukus = Buku::all();
        $mahasiswas = mahasiswa::all();
        $transaksis = Transaction::all();
        return view('index_admin', compact('bukus', 'mahasiswas', 'transaksis'));
    }

    public function index_mahasiswa () {
        $bukus = Buku::all();
        $mahasiswa = Auth::guard('mahasiswa')->user(); 
    
        return view('index_mahasiswa', compact('bukus', 'mahasiswa'));
    }

    public function login () {
        return view('login');
    }

    public function register () {
        return view('register');
    }

    public function profil () {
        return view('profil');
    }


    public function delete_buku($id) {
        $buku = Buku::findOrFail($id);
        $buku->delete();
        return redirect()->route('dashboard_admin')->with('success', 'Deleted!');
    }

    public function create_buku(Request $request) {
        $datas = $request->validate([
            'judul'=>'required|string',
            'penerbit'=>'required|string',
            'pengarang'=>'required|string',
            'stok_buku'=>'required|string'
        ]);

        Buku::create($datas);
        return redirect()->route('dashboard_admin')->with('success', 'Book Added!');
    }

    public function edit_buku(Request $request, $id) {
        $datas = $request->validate([
            'judul'=>'required|string',
            'penerbit'=>'required|string',
            'pengarang'=>'required|string',
            'stok_buku'=>'required|string'
        ]);

        $buku = Buku::find($id);
        $buku->update($datas);
        return redirect()->route('dashboard_admin')->with('success', 'Book Edited!');
    }


    public function delete_mahasiswa($id) {
        $mahasiswa = mahasiswa::findOrFail($id);
        $mahasiswa->delete();
        return redirect()->route('dashboard_admin')->with('success', 'Deleted!');
    }

    public function create_mahasiswa(Request $request) {
        $datas = $request->validate([
            'name'=>'required|string',
            'kelas'=>'required|string',
            'email'=>'required|string',
            'password'=>'required|string',
            'role_status'=>'required|string'
        ]);

        mahasiswa::create($datas);
        return redirect()->route('dashboard_admin')->with('success', 'Student Added!');
    }

    public function edit_mahasiswa(Request $request, $id) {
        $datas = $request->validate([
            'name'=>'required|string',
            'kelas'=>'required|string',
            'email'=>'required|string',
            'password'=>'required|string',
            'role_status'=>'required|string'
        ]);

        $mahasiswa = mahasiswa::find($id);
        $mahasiswa->update($datas);
        return redirect()->route('dashboard_admin')->with('success', 'Student Edited!');
    }

    public function borrow_book(Request $request, $mahasiswaId, $namamahasiswa, $kelasmahasiswa, $bukuId, $judulBuku, $mahasiswaEmail)
    {

        $buku = Buku::find($bukuId);

        if ($buku && $buku->stok_buku > 0) {
            // Create a transaction
            Transaction::create([
                'mahasiswa_id' => $mahasiswaId,
                'nama_mahasiswa' => $namamahasiswa,
                'kelas_mahasiswa' => $kelasmahasiswa,
                'buku_id' => $bukuId,
                'judul_buku' => $judulBuku,
                'mahasiswa_email' => $mahasiswaEmail,
            ]);

            // Update the book stock
            $buku->stok_buku -= 1;
            $buku->save();

            return redirect()->route('dashboard_mahasiswa')->with('success', 'Book Borrowed!');
        } else {
            return redirect()->route('dashboard_mahasiswa')->with('error', 'Book is not available for borrowing.');
        }
    }
    public function return_book($mahasiswaId, $bukuId)
{
    $transaction = Transaction::where('mahasiswa_id', $mahasiswaId)
        ->where('buku_id', $bukuId)
        ->whereNull('returned_at')
        ->first();

    if ($transaction) {
        // Mark the transaction as returned
        $transaction->update(['returned_at' => now()]);

        // Increase the book stock
        $buku = Buku::find($bukuId);
        $buku->stok_buku += 1;
        $buku->save();

        // Delete the transaction record
        $transaction->delete();

        return redirect()->route('dashboard_mahasiswa')->with('success', 'Book Returned!');
    } else {
        return redirect()->route('dashboard_mahasiswa')->with('error', 'Book could not be returned.');
    }
}

}
