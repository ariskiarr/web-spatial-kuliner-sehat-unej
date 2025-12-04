<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\tempat_makan;
use App\Models\kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TempatMakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tempatMakan = DB::table('tempat_makan')
            ->join('kategori', 'tempat_makan.kategori_id', '=', 'kategori.id')
            ->select(
                'tempat_makan.*',
                'kategori.nama_kategori',
                DB::raw('ST_X(geom) as longitude'),
                DB::raw('ST_Y(geom) as latitude')
            )
            ->paginate(15);

        return view('admin.tempat-makan.index-new', compact('tempatMakan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $kategori = kategori::all();
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');

        return view('admin.tempat-makan.create-new', compact('kategori', 'latitude', 'longitude'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tempat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'alamat' => 'required|string',
            'jam_operasional' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        DB::table('tempat_makan')->insert([
            'nama_tempat' => $validated['nama_tempat'],
            'kategori_id' => $validated['kategori_id'],
            'alamat' => $validated['alamat'],
            'jam_operasional' => $validated['jam_operasional'],
            'geom' => DB::raw("ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326)")
        ]);

        return redirect()
            ->route('admin.tempat-makan.index')
            ->with('success', 'Tempat makan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tempatMakan = DB::table('tempat_makan')
            ->select(
                'tempat_makan.*',
                DB::raw('ST_X(geom) as longitude'),
                DB::raw('ST_Y(geom) as latitude')
            )
            ->where('id', $id)
            ->first();

        if (!$tempatMakan) {
            abort(404);
        }

        return view('admin.tempat-makan.show', compact('tempatMakan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tempatMakan = DB::table('tempat_makan')
            ->select(
                'tempat_makan.*',
                DB::raw('ST_X(geom) as longitude'),
                DB::raw('ST_Y(geom) as latitude')
            )
            ->where('id', $id)
            ->first();

        if (!$tempatMakan) {
            abort(404);
        }

        $kategori = kategori::all();

        return view('admin.tempat-makan.edit-new', compact('tempatMakan', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_tempat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'alamat' => 'required|string',
            'jam_operasional' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        DB::table('tempat_makan')
            ->where('id', $id)
            ->update([
                'nama_tempat' => $validated['nama_tempat'],
                'kategori_id' => $validated['kategori_id'],
                'alamat' => $validated['alamat'],
                'jam_operasional' => $validated['jam_operasional'],
                'geom' => DB::raw("ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326)")
            ]);

        return redirect()
            ->route('admin.tempat-makan.index')
            ->with('success', 'Tempat makan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('tempat_makan')->where('id', $id)->delete();

        return redirect()
            ->route('admin.tempat-makan.index')
            ->with('success', 'Tempat makan berhasil dihapus!');
    }
}
