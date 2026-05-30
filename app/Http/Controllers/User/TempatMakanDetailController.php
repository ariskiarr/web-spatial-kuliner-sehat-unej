<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\kampus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk detail tempat makan
 */
class TempatMakanDetailController extends Controller
{
    /**
     * Show detail tempat makan
     */
    public function show($id)
    {
        $kampus = kampus::first();

        $tempat = DB::table('tempat_makan as t')
            ->select([
                't.*',
                'k.nama_kategori',
                DB::raw('ST_X(t.geom::geometry) as lng'),
                DB::raw('ST_Y(t.geom::geometry) as lat'),
                DB::raw('ST_DistanceSphere(t.geom, (SELECT geom FROM kampus LIMIT 1)) / 1000 as distance_km')
            ])
            ->join('kategori as k', 't.kategori_id', '=', 'k.id')
            ->where('t.id', $id)
            ->first();

        if (!$tempat) {
            return redirect()->route('dashboard.user')->with('error', 'Tempat makan tidak ditemukan');
        }

        // Get menus
        $menus = DB::table('menu_makan')
            ->where('tempat_id', $id)
            ->orderBy('kalori', 'ASC')
            ->get();

        // Get reviews
        $reviews = DB::table('ulasan')
            ->where('tempat_id', $id)
            ->orderBy('tanggal', 'DESC')
            ->limit(10)
            ->get();

        // Calculate average rating
        $avgRating = DB::table('ulasan')
            ->where('tempat_id', $id)
            ->avg('rating') ?? 0;

        $totalReviews = DB::table('ulasan')
            ->where('tempat_id', $id)
            ->count();

        // Check if current user has favorited this place
        $isFavorite = false;
        if (Auth::check()) {
            $isFavorite = DB::table('user_favorites')
                ->where('users_id', Auth::id())
                ->where('tempat_id', $id)
                ->exists();
        }

        return view('User.detail-tempat', compact(
            'tempat',
            'menus',
            'reviews',
            'avgRating',
            'totalReviews',
            'isFavorite'
        ));
    }

    /**
     * Get detail tempat makan dengan menu (API)
     */
    public function getDetail($id)
    {
        $tempat = DB::table('tempat_makan as t')
            ->select([
                't.*',
                'k.nama_kategori',
                DB::raw('ST_X(t.geom::geometry) as longitude'),
                DB::raw('ST_Y(t.geom::geometry) as latitude')
            ])
            ->join('kategori as k', 't.kategori_id', '=', 'k.id')
            ->where('t.id', $id)
            ->first();

        $menus = DB::table('menu_makan')
            ->where('tempat_id', $id)
            ->orderBy('kalori', 'ASC')
            ->get();

        $reviews = DB::table('ulasan')
            ->where('tempat_id', $id)
            ->orderBy('tanggal', 'DESC')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'tempat' => $tempat,
            'menus' => $menus,
            'reviews' => $reviews
        ]);
    }
}
