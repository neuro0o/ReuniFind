<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemLocation;

class ItemLocationController extends Controller
{
    public function index() {
        $locations = ItemLocation::all();
        return view('admin.locations.index', compact('locations'));
    }

    public function create() {
        return view('admin.locations.create');
    }

    public function store(Request $request) {
        $request->validate([
            'locationName' => 'required|string|unique:item_locations,locationName',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        ItemLocation::create($request->only('locationName', 'latitude', 'longitude'));

        return redirect()->route('locations.index')->with('success', 'Location added successfully!');
    }

    public function edit(ItemLocation $location) {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, ItemLocation $location) {
        $request->validate([
            'locationName' => 'required|string|unique:item_locations,locationName,' . $location->locationID . ',locationID',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $location->update($request->only('locationName', 'latitude', 'longitude'));

        return redirect()->route('locations.index')->with('success', 'Location updated successfully!');
    }

    public function destroy(ItemLocation $location) {
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Location deleted successfully!');
    }
}
