<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemCategory;

class ItemCategoryController extends Controller
{
    public function index() {
        $categories = ItemCategory::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create() {
        return view('admin.categories.create');
    }

    public function store(Request $request) {
        $request->validate([
            'categoryName' => 'required|string|unique:item_categories,categoryName',
            'description' => 'required|string'
        ]);

        ItemCategory::create($request->only('categoryName', 'description'));

        return redirect()->route('categories.index')->with('success', 'Category added successfully!');
    }

    public function edit(ItemCategory $category) {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, ItemCategory $category) {
        $request->validate([
            'categoryName' => 'required|string|unique:item_categories,categoryName,' . $category->categoryID . ',categoryID',
            'description' => 'required|string'
        ]);

        $category->update($request->only('categoryName', 'description'));

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(ItemCategory $category) {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
