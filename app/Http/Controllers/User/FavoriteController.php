<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk fitur favorit
 */
class FavoriteController extends Controller
{
    /**
     * Toggle favorite tempat makan
     */
    public function toggle($id)
    {
        $userId = Auth::id();

        $existing = DB::table('user_favorites')
            ->where('users_id', $userId)
            ->where('tempat_id', $id)
            ->first();

        if ($existing) {
            DB::table('user_favorites')
                ->where('users_id', $userId)
                ->where('tempat_id', $id)
                ->delete();
            $message = 'Dihapus dari favorit';
        } else {
            DB::table('user_favorites')->insert([
                'users_id' => $userId,
                'tempat_id' => $id
            ]);
            $message = 'Ditambahkan ke favorit';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Show user's favorite places
     */
    public function index()
    {
        $favorites = DB::table('user_favorites as uf')
            ->select([
                't.id as tempat_id',
                't.nama_tempat',
                't.alamat',
                't.kategori_id',
                'k.nama_kategori',
                DB::raw('ST_X(t.geom::geometry) as lng'),
                DB::raw('ST_Y(t.geom::geometry) as lat'),
                DB::raw('ST_DistanceSphere(t.geom, (SELECT geom FROM kampus LIMIT 1)) / 1000 as distance_km'),
                DB::raw('AVG(u.rating) as avg_rating'),
                DB::raw('(SELECT AVG(m.kalori) FROM menu_makan m WHERE m.tempat_id = t.id) as avg_kalori')
            ])
            ->join('tempat_makan as t', 'uf.tempat_id', '=', 't.id')
            ->join('kategori as k', 't.kategori_id', '=', 'k.id')
            ->leftJoin('ulasan as u', 't.id', '=', 'u.tempat_id')
            ->where('uf.users_id', Auth::id())
            ->groupBy('t.id', 't.nama_tempat', 't.alamat', 't.kategori_id', 't.geom', 'k.nama_kategori')
            ->orderBy('t.nama_tempat', 'ASC')
            ->get();

        return view('User.favorites', compact('favorites'));
    }
}
