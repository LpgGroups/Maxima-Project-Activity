<?php

namespace App\Http\Controllers\carrousel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarrouselAds;
use Illuminate\Support\Facades\Storage;

class CarrouselController extends Controller
{
    public function index()
    {
        $carrousels = CarrouselAds::orderBy('order')->get();

        return view('dashboard.dev.carrousel.index', [
            'title' => 'Kelola Carousel Ads',
            'carrousels' => $carrousels,
        ]);
    }

    public function create()
    {
        return view('dashboard.dev.carrousel.store', [
            'title' => 'Tambah Carousel Ad',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'image' => 'required|image|max:2048',
            'is_active' => 'nullable|boolean',
            'url' => 'nullable|url',
        ]);
        $maxOrder = CarrouselAds::max('order') ?? 0;
        $newOrder = $maxOrder + 1;
        $imagePath = $request->file('image')->store('carousel', 'public');

        CarrouselAds::create([
            'title' => $request->title,
            'summary' => $request->summary,
            'image' => $imagePath,
            'order' => $newOrder,
            'is_active' => $request->is_active ?? true,
            'url' => $request->url,
        ]);

        return redirect()->route('carrousel.index')->with('success', 'Carousel berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $carrousel = CarrouselAds::findOrFail($id);

        return view('dashboard.dev.carrousel.edit', [
            'title' => 'Edit Carousel Ad',
            'carrousel' => $carrousel,
        ]);
    }

    public function update(Request $request, $id)
    {
        $carrousel = CarrouselAds::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'url' => 'nullable|url',
        ]);

        $data = [
            'title' => $request->title,
            'summary' => $request->summary,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? true,
            'url' => $request->url,
        ];

        if ($request->hasFile('image')) {
            // Optional: hapus gambar lama
            if ($carrousel->image && Storage::disk('public')->exists($carrousel->image)) {
                Storage::disk('public')->delete($carrousel->image);
            }

            $data['image'] = $request->file('image')->store('carousel', 'public');
        }

        $carrousel->update($data);

        return redirect()->route('carrousel.index')->with('success', 'Carousel berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $carrousel = CarrouselAds::findOrFail($id);

        if ($carrousel->image && Storage::disk('public')->exists($carrousel->image)) {
            Storage::disk('public')->delete($carrousel->image);
        }

        $carrousel->delete();

        return redirect()->route('carrousel.index')->with('success', 'Carousel berhasil dihapus.');
    }
}
