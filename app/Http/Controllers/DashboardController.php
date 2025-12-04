<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\kategori;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with map
     */
    public function index()
    {
        // Get all tempat_makan with coordinates and jam operasional
        $tempatMakan = DB::table('tempat_makan')
            ->join('kategori', 'tempat_makan.kategori_id', '=', 'kategori.id')
            ->select(
                'tempat_makan.id',
                'tempat_makan.nama_tempat',
                'tempat_makan.alamat',
                'tempat_makan.jam_operasional',
                'tempat_makan.kategori_id',
                'kategori.nama_kategori',
                DB::raw('ST_X(geom) as longitude'),
                DB::raw('ST_Y(geom) as latitude')
            )
            ->whereNotNull('geom')
            ->get();

        // Get all categories for filter
        $categories = kategori::all();

        return view('dashboard-new', compact('tempatMakan', 'categories'));
    }
}
