<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BrandingAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandingAssetController extends Controller
{
    public function index(Request $request)
    {
        $userid = session()->get('userid');

        if (!$userid) {
            return redirect()->route('corporate.login');
        }

        $query = BrandingAsset::where('user_id', $userid);

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where('asset_name', 'like', '%' . $request->search . '%');
        }

        // Filter by file type
        if ($request->has('file_type') && !empty($request->file_type) && $request->file_type !== 'all') {
            $query->where('file_type', $request->file_type);
        }

        $assets = $query->orderByDesc('created_at')->paginate(12)->withQueryString();

        return view('web_frontend.branding-assets', compact('assets'));
    }

    public function store(Request $request)
    {
        $userid = session()->get('userid');

        if (!$userid) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'asset_name' => 'required|string|max:255',
            'file' => array_merge(['required'], $this->fileRules()),
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            [$fileName, $filePath] = $this->storeFile($file);

            $asset = BrandingAsset::create([
                'user_id' => $userid,
                'asset_name' => $request->asset_name,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_type' => strtoupper($file->getClientOriginalExtension()),
                'file_size' => $file->getSize(),
                'description' => $request->description
            ]);

            return response()->json(['success' => true, 'message' => 'Asset uploaded successfully']);
        }

        return response()->json(['success' => false, 'message' => 'File upload failed']);
    }

    public function show($id)
    {
        $userid = session()->get('userid');

        if (!$userid) {
            return redirect()->route('corporate.login');
        }

        $asset = BrandingAsset::where('id', $id)->where('user_id', $userid)->firstOrFail();

        return response()->json($asset);
    }

    public function update(Request $request, $id)
    {
        $userid = session()->get('userid');

        if (!$userid) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $asset = BrandingAsset::where('id', $id)->where('user_id', $userid)->firstOrFail();

        $request->validate([
            'asset_name' => 'required|string|max:255',
            'file' => array_merge(['nullable'], $this->fileRules()),
            'description' => 'nullable|string'
        ]);

        $asset->asset_name = $request->asset_name;
        $asset->description = $request->description;

        if ($request->hasFile('file')) {
            // Delete old file
            if (Storage::disk('public')->exists($asset->file_path)) {
                Storage::disk('public')->delete($asset->file_path);
            }

            $file = $request->file('file');
            [$fileName, $filePath] = $this->storeFile($file);

            $asset->file_name = $fileName;
            $asset->file_path = $filePath;
            $asset->file_type = strtoupper($file->getClientOriginalExtension());
            $asset->file_size = $file->getSize();
        }

        $asset->save();

        return response()->json(['success' => true, 'message' => 'Asset updated successfully']);
    }

    public function destroy($id)
    {
        $userid = session()->get('userid');

        if (!$userid) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $asset = BrandingAsset::where('id', $id)->where('user_id', $userid)->firstOrFail();

        // Delete file from storage
        if (Storage::disk('public')->exists($asset->file_path)) {
            Storage::disk('public')->delete($asset->file_path);
        }

        $asset->delete();

        return response()->json(['success' => true, 'message' => 'Asset deleted successfully']);
    }

    /**
     * Upload rules. Files are served from the public storage folder, so an SVG must not carry
     * scripts (same check as the order approval uploads in OrderWorkflow).
     */
    private function fileRules()
    {
        return ['file', 'mimes:png,jpg,jpeg,webp,pdf,ai,svg', 'max:10240', function ($attribute, $file, $fail) {
            if (strtolower($file->getClientOriginalExtension()) === 'svg'
                && preg_match('/<script|<foreignObject|\bon[a-z]+\s*=|javascript:|<!ENTITY/i', (string) file_get_contents($file->getRealPath()))) {
                $fail('This SVG contains scripts and cannot be accepted. Please upload it as PDF or PNG.');
            }
        }];
    }

    /**
     * Stores the upload under a random name (not guessable, no odd characters in the path).
     * The original name is kept for the download.
     * @return array [original file name, stored path]
     */
    private function storeFile($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $path = $file->storeAs('branding_assets', \Illuminate\Support\Str::random(40) . '.' . $extension, 'public');
        return [mb_substr(basename($file->getClientOriginalName()), 0, 200), $path];
    }

    public function download($id)
    {
        $userid = session()->get('userid');

        if (!$userid) {
            return redirect()->route('corporate.login');
        }

        $asset = BrandingAsset::where('id', $id)->where('user_id', $userid)->firstOrFail();

        $filePath = storage_path('app/public/' . $asset->file_path);

        if (file_exists($filePath)) {
            return response()->download($filePath, $asset->file_name);
        }

        return redirect()->back()->with('error', 'File not found');
    }
}
