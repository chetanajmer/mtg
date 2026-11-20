<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BrandingAsset;
use Illuminate\Http\Request;

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
            $asset->deleteFile();

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

        $asset->deleteFile();

        $asset->delete();

        return response()->json(['success' => true, 'message' => 'Asset deleted successfully']);
    }

    /**
     * Upload rules. An SVG must not carry scripts (same check as the order approval uploads in OrderWorkflow);
     * files are also served with a sandbox header, so nothing in them can run on this site.
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
        // Private storage (storage/app): only the owner gets it, through file() and download()
        $path = $file->storeAs('branding_assets', \Illuminate\Support\Str::random(40) . '.' . $extension, 'local');
        return [mb_substr(basename($file->getClientOriginalName()), 0, 200), $path];
    }

    public function download($id)
    {
        $userid = session()->get('userid');

        if (!$userid) {
            return redirect()->route('corporate.login');
        }

        $asset = BrandingAsset::where('id', $id)->where('user_id', $userid)->firstOrFail();

        $filePath = $asset->absolutePath();

        if ($filePath) {
            return response()->download($filePath, $asset->file_name, ['X-Content-Type-Options' => 'nosniff']);
        }

        return redirect()->back()->with('error', 'File not found');
    }

    /** The file itself for previews on the page: only the owner's, and nothing in it can run. */
    public function file($id)
    {
        $asset = BrandingAsset::where('id', $id)->where('user_id', session()->get('userid'))->firstOrFail();
        $filePath = $asset->absolutePath();
        abort_unless($filePath, 404);
        $types = ['png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'webp' => 'image/webp', 'pdf' => 'application/pdf', 'svg' => 'image/svg+xml'];
        $type = $types[strtolower(pathinfo($filePath, PATHINFO_EXTENSION))] ?? 'application/octet-stream';

        return response()->file($filePath, [
            'Content-Type' => $type,
            'Content-Security-Policy' => "default-src 'none'; img-src 'self' data:; style-src 'unsafe-inline'; sandbox",
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
