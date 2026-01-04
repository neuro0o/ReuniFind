<?php

namespace App\Http\Controllers;

use App\Models\ItemTag;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class ItemTagController extends Controller
{
    /**
     * Show the scan QR page
     */
    public function showScanPage()
    {
        return view('tag.scan');
    }

    /**
     * Show item info after QR scan (public access)
     */
    public function showItemInfo($tagID)
    {
        $itemTag = ItemTag::with(['user', 'category'])->findOrFail($tagID);
        
        return view('tag.info', compact('itemTag'));
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        $categories = ItemCategory::orderBy('categoryName')->get();
        
        return view('tag.register', compact('categories'));
    }

    /**
     * Store newly registered item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'itemName' => 'required|string|max:255',
            'itemCategory' => 'required|exists:item_categories,categoryID',
            'itemDescription' => 'required|string|max:500',
            'itemImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle item image upload
        $itemImgPath = null;
        if ($request->hasFile('itemImg')) {
            $itemImgPath = $request->file('itemImg')->store('images/items', 'public');
        }

        // Generate unique tag ID for QR
        $tag = ItemTag::create([
            'itemName' => $validated['itemName'],
            'itemCategory' => $validated['itemCategory'],
            'itemDescription' => $validated['itemDescription'],
            'itemImg' => $itemImgPath,
            'itemStatus' => 'Safe',
            'userID' => Auth::id(),
            'tagImg' => 'temp' // Temporary, will update after getting tagID
        ]);

        // Generate QR code image
        $qrCodeUrl = route('tag.info', ['tagID' => $tag->tagID]);
        
        // Generate QR as PNG instead of SVG for better PDF compatibility
        $qrCodePng = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($qrCodeUrl);

        // Save QR as PNG
        $qrCodePath = 'qr_tags/tag_' . $tag->tagID . '.png';
        Storage::disk('public')->put($qrCodePath, $qrCodePng);

        // Update tag with QR image path
        $tag->update(['tagImg' => $qrCodePath]);

        return redirect()->route('tag.detail', ['tagID' => $tag->tagID])
            ->with('success', 'Item registered successfully! Download your QR tag below.');
    }

    /**
     * Show user's registered items
     */
    public function myRegisteredItems()
    {
        $items = ItemTag::with('category')
            ->where('userID', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tag.my_items', compact('items'));
    }

    /**
     * Show item detail with QR tag download option
     */
    public function showItemDetail($tagID)
    {
        $itemTag = ItemTag::with('category')
            ->where('tagID', $tagID)
            ->where('userID', Auth::id())
            ->firstOrFail();

        return view('tag.detail', compact('itemTag'));
    }

    /**
     * Update item status (Safe/Lost)
     */
    public function updateStatus(Request $request, $tagID)
    {
        $itemTag = ItemTag::where('tagID', $tagID)
            ->where('userID', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'itemStatus' => 'required|in:Safe,Lost'
        ]);

        $itemTag->update(['itemStatus' => $validated['itemStatus']]);

        $message = $validated['itemStatus'] === 'Lost' 
            ? 'Item marked as lost.' 
            : 'Item marked as safe.';

        return back()->with('success', $message);
    }

    /**
     * Edit registered item
     */
    public function edit($tagID)
    {
        $itemTag = ItemTag::where('tagID', $tagID)
            ->where('userID', Auth::id())
            ->firstOrFail();

        $categories = ItemCategory::orderBy('categoryName')->get();

        return view('tag.edit', compact('itemTag', 'categories'));
    }

    /**
     * Update registered item
     */
    public function update(Request $request, $tagID)
    {
        $itemTag = ItemTag::where('tagID', $tagID)
            ->where('userID', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'itemName' => 'required|string|max:255',
            'itemCategory' => 'required|exists:item_categories,categoryID',
            'itemDescription' => 'required|string|max:500',
            'itemImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle new item image upload
        if ($request->hasFile('itemImg')) {
            // Delete old image if exists
            if ($itemTag->itemImg && Storage::disk('public')->exists($itemTag->itemImg)) {
                Storage::disk('public')->delete($itemTag->itemImg);
            }
            
            $validated['itemImg'] = $request->file('itemImg')->store('images/items', 'public');
        }

        $itemTag->update($validated);

        return redirect()->route('tag.my')
            ->with('success', 'Item updated successfully!');
    }

    /**
     * Delete registered item
     */
    public function destroy($tagID)
    {
        $itemTag = ItemTag::where('tagID', $tagID)
            ->where('userID', Auth::id())
            ->firstOrFail();

        // Delete associated images
        if ($itemTag->itemImg && Storage::disk('public')->exists($itemTag->itemImg)) {
            Storage::disk('public')->delete($itemTag->itemImg);
        }
        if ($itemTag->tagImg && Storage::disk('public')->exists($itemTag->tagImg)) {
            Storage::disk('public')->delete($itemTag->tagImg);
        }

        $itemTag->delete();

        return redirect()->route('tag.my')
            ->with('success', 'Item deleted successfully!');
    }

    /**
     * Download QR tag as PDF
     */
    public function downloadQRTag(Request $request, $tagID)
    {
        $itemTag = ItemTag::with(['user', 'category'])
            ->where('tagID', $tagID)
            ->where('userID', Auth::id())
            ->firstOrFail();

        // Get size from request, default to medium
        $size = $request->input('size', 'medium');
        
        // Validate size
        if (!in_array($size, ['small', 'medium', 'large'])) {
            $size = 'medium';
        }

        // Generate PDF
        $pdf = Pdf::loadView('tag.qr_tag_pdf', compact('itemTag', 'size'));
        
        // Use standard A4 page size
        $pdf->setPaper('a4', 'portrait');

        $filename = 'QR_Tag_' . ucfirst($size) . '_' . str_replace(' ', '_', $itemTag->itemName) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Print QR tag (opens print dialog)
     */
    public function printQRTag($tagID)
    {
        $itemTag = ItemTag::with(['user', 'category'])
            ->where('tagID', $tagID)
            ->where('userID', Auth::id())
            ->firstOrFail();

        return view('tag.qr_tag_print', compact('itemTag'));
    }
}
