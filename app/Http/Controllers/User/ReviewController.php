<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk fitur review/ulasan
 */
class ReviewController extends Controller
{
    /**
     * Store review
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:500'
        ]);

        DB::table('ulasan')->insert([
            'tempat_id' => $id,
            'penulis' => Auth::user()->name,
            'rating' => $request->rating,
            'ulasan' => $request->komentar,
            'tanggal' => now(),
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil ditambahkan!');
    }
}
