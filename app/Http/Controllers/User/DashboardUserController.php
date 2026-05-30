<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\SpatialService;
use App\Services\KaloriService;
use App\Models\kampus;
use App\Models\kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Dashboard User Controller
 * Menampilkan peta interaktif dengan filter kalori dan jarak
 */
class DashboardUserController extends Controller
{
    private SpatialService $spatialService;
    private KaloriService $kaloriService;

    /**
     * Constructor - Dependency Injection (OOP)
     */
    public function __construct(SpatialService $spatialService, KaloriService $kaloriService)
    {
        $this->spatialService = $spatialService;
        $this->kaloriService = $kaloriService;
    }

    /**
     * Display dashboard with map
     */
    public function index(Request $request)
    {
        // Get kampus data (hanya 1 kampus)
        $kampus = kampus::select([
            'id',
            'alamat',
            DB::raw('ST_X(geom::geometry) as longitude'),
            DB::raw('ST_Y(geom::geometry) as latitude')
        ])->first();

        if (!$kampus) {
            return redirect()->back()->with('error', 'Data kampus belum tersedia');
        }

        // Get filter parameters
        $maxCalories = $request->input('max_calories', 500);
        $radius = $request->input('radius', 5); // km
        $kategoriId = $request->input('kategori_id');

        // Get kategori list for filter
        $kategoris = kategori::all();

        // Get tempat makan with low calories near kampus
        $tempatMakans = $this->kaloriService->getTempatMakanLowCaloriesNearKampus(
            $kampus->latitude,
            $kampus->longitude,
            $maxCalories,
            $radius
        );

        // Filter by kategori if selected
        if ($kategoriId) {
            $tempatMakans = collect($tempatMakans)->where('kategori_id', $kategoriId)->values()->all();
        }

        // Create buffer for visualization
        $buffer = $this->spatialService->createBuffer(
            $kampus->latitude,
            $kampus->longitude,
            $radius
        );

        return view('User.dashboard', compact(
            'kampus',
            'tempatMakans',
            'kategoris',
            'maxCalories',
            'radius',
            'kategoriId',
            'buffer'
        ));
    }

    /**
     * API endpoint untuk get tempat makan (AJAX)
     */
    public function getTempatMakan(Request $request)
    {
        $kampus = kampus::select([
            'id',
            DB::raw('ST_X(geom::geometry) as longitude'),
            DB::raw('ST_Y(geom::geometry) as latitude')
        ])->first();

        $maxCalories = $request->input('max_calories', 500);
        $radius = $request->input('radius', 5);
        $kategoriId = $request->input('kategori_id');

        $tempatMakans = $this->kaloriService->getTempatMakanLowCaloriesNearKampus(
            $kampus->latitude,
            $kampus->longitude,
            $maxCalories,
            $radius
        );

        // Filter by kategori if selected
        if ($kategoriId) {
            $tempatMakans = collect($tempatMakans)->where('kategori_id', $kategoriId)->values()->all();
        }

        return response()->json([
            'success' => true,
            'data' => $tempatMakans,
            'kampus' => $kampus
        ]);
    }
}
