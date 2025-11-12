<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemLocation;

class ItemLocationController extends Controller
{   
    // Display Item Location List
    public function index() {
        $locations = ItemLocation::all();
        return view('admin.locations.index', compact('locations'));
    }

    // Create new Item Location entry
    public function create() {
        return view('admin.locations.create');
    }

    // Save Item Location entry
    public function store(Request $request) {
        $request->validate([
            'locationName' => 'required|string|unique:item_locations,locationName',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        ItemLocation::create($request->only('locationName', 'latitude', 'longitude'));

        return redirect()->route('locations.index')->with('success', 'Location added successfully!');
    }

    // Show Item Location Edit Form
    public function edit(ItemLocation $location) {
        return view('admin.locations.edit', compact('location'));
    }

    // Update Item Location entry
    public function update(Request $request, ItemLocation $location) {
        $request->validate([
            'locationName' => 'required|string|unique:item_locations,locationName,' . $location->locationID . ',locationID',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $location->update($request->only('locationName', 'latitude', 'longitude'));

        return redirect()->route('locations.index')->with('success', 'Location updated successfully!');
    }

    // Delete Item Location entry
    public function destroy(ItemLocation $location) {
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Location deleted successfully!');
    }
}
