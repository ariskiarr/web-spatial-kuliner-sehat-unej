<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\menu_makan;
use App\Models\tempat_makan;
use Illuminate\Http\Request;

class MenuMakanController extends Controller
{
    /**
     * Display a listing of menus for a specific restaurant.
     */
    public function index(string $tempat_id)
    {
        $tempatMakan = tempat_makan::findOrFail($tempat_id);
        $menus = menu_makan::where('tempat_id', $tempat_id)->get();

        return view('admin.menu-makan.index-new', compact('tempatMakan', 'menus'));
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create(string $tempat_id)
    {
        $tempatMakan = tempat_makan::findOrFail($tempat_id);
        return view('admin.menu-makan.create-new', compact('tempatMakan'));
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request, string $tempat_id)
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kalori' => 'nullable|numeric|min:0',
            'auto_kalori' => 'nullable|boolean',
        ]);

        // Auto calculate kalori if requested
        if ($request->has('auto_kalori') && $request->auto_kalori == true) {
            $kalori = $this->estimateCalories($validated['nama_menu']);
            $validated['kalori'] = $kalori;
        }

        menu_makan::create([
            'tempat_id' => $tempat_id,
            'nama_menu' => $validated['nama_menu'],
            'harga' => $validated['harga'],
            'kalori' => $validated['kalori'] ?? null,
        ]);

        return redirect()
            ->route('admin.menu-makan.index', $tempat_id)
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(string $tempat_id, string $id)
    {
        $tempatMakan = tempat_makan::findOrFail($tempat_id);
        $menu = menu_makan::where('tempat_id', $tempat_id)->findOrFail($id);

        return view('admin.menu-makan.edit-new', compact('tempatMakan', 'menu'));
    }

    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, string $tempat_id, string $id)
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kalori' => 'nullable|numeric|min:0',
            'auto_kalori' => 'nullable|boolean',
        ]);

        $menu = menu_makan::where('tempat_id', $tempat_id)->findOrFail($id);

        // Auto calculate kalori if requested
        if ($request->has('auto_kalori') && $request->auto_kalori == true) {
            $kalori = $this->estimateCalories($validated['nama_menu']);
            $validated['kalori'] = $kalori;
        }

        $menu->update([
            'nama_menu' => $validated['nama_menu'],
            'harga' => $validated['harga'],
            'kalori' => $validated['kalori'] ?? null,
        ]);

        return redirect()
            ->route('admin.menu-makan.index', $tempat_id)
            ->with('success', 'Menu berhasil diperbarui!');
    }

    /**
     * Remove the specified menu from storage.
     */
    public function destroy(string $tempat_id, string $id)
    {
        $menu = menu_makan::where('tempat_id', $tempat_id)->findOrFail($id);
        $menu->delete();

        return redirect()
            ->route('admin.menu-makan.index', $tempat_id)
            ->with('success', 'Menu berhasil dihapus!');
    }

    /**
     * Estimate calories based on food name using predefined rules
     */
    private function estimateCalories(string $foodName): int
    {
        $foodName = strtolower($foodName);

        // Simple calorie estimation based on keywords
        $calorieDatabase = [
            // Nasi & Karbohidrat
            'nasi putih' => 200,
            'nasi goreng' => 450,
            'nasi uduk' => 350,
            'nasi kuning' => 300,
            'mie goreng' => 400,
            'mie ayam' => 350,
            'kwetiau' => 380,
            'bihun' => 320,
            
            // Ayam
            'ayam goreng' => 300,
            'ayam bakar' => 250,
            'ayam penyet' => 350,
            'ayam rica' => 280,
            
            // Seafood
            'ikan bakar' => 200,
            'ikan goreng' => 250,
            'udang' => 180,
            'cumi' => 160,
            
            // Daging
            'sate' => 250,
            'rendang' => 350,
            'bakso' => 200,
            'burger' => 500,
            
            // Minuman
            'es teh' => 100,
            'es jeruk' => 120,
            'kopi' => 50,
            'jus' => 150,
            'milkshake' => 300,
            
            // Dessert
            'es krim' => 250,
            'cake' => 300,
            'brownies' => 280,
            'donat' => 250,
        ];

        // Check for exact matches
        foreach ($calorieDatabase as $keyword => $calories) {
            if (strpos($foodName, $keyword) !== false) {
                return $calories;
            }
        }

        // Default estimate based on meal type
        if (strpos($foodName, 'nasi') !== false) return 350;
        if (strpos($foodName, 'mie') !== false) return 380;
        if (strpos($foodName, 'ayam') !== false) return 280;
        if (strpos($foodName, 'ikan') !== false) return 220;
        if (strpos($foodName, 'es') !== false) return 120;
        if (strpos($foodName, 'jus') !== false) return 150;

        // Default calorie if no match
        return 250;
    }

    /**
     * API endpoint to get calorie estimate (for AJAX calls)
     */
    public function getCalorieEstimate(Request $request)
    {
        $foodName = $request->input('nama_menu');
        $kalori = $this->estimateCalories($foodName);

        return response()->json([
            'success' => true,
            'kalori' => $kalori,
            'nama_menu' => $foodName
        ]);
    }
}
