<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\HomeLogo;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HomeLogoController extends Controller
{
    private const LOGO_RULE = 'image|mimes:jpg,jpeg,png,webp|max:2048';

    private const MESSAGES = [
        'logo.required' => 'Please choose a logo to upload.',
        'logo.image' => 'The logo must be a picture.',
        'logo.mimes' => 'The logo must be a JPG, PNG or WEBP file.',
        'logo.max' => 'The logo must be 2 MB or smaller.',
        'logo.uploaded' => 'The logo could not be uploaded. It must be 2 MB or smaller.',
    ];

    public function index()
    {
        $items = HomeLogo::ordered()->get()->groupBy('type');

        return view('backend.settings.home-logos', ['items' => $items, 'types' => HomeLogo::TYPES]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(HomeLogo::TYPES))],
            'name' => 'required|string|max:100',
            'ranking' => 'nullable|integer|min:0|max:9999',
            'logo' => 'required|' . self::LOGO_RULE,
        ], self::MESSAGES);

        HomeLogo::create([
            'type' => $data['type'],
            'name' => trim($data['name']),
            'logo' => $this->saveLogo($request->file('logo')),
            'is_active' => true,
            'ranking' => $data['ranking'] ?? (int) HomeLogo::where('type', $data['type'])->max('ranking') + 1,
        ]);

        return back()->with('success', trim($data['name']) . ' added.');
    }

    public function update(Request $request, $id)
    {
        $item = HomeLogo::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'ranking' => 'required|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
            'logo' => 'nullable|' . self::LOGO_RULE,
        ], self::MESSAGES);

        if ($request->hasFile('logo')) {
            $item->deleteUploadedLogo();
            $item->logo = $this->saveLogo($request->file('logo'));
        }
        $item->name = trim($data['name']);
        $item->ranking = $data['ranking'];
        $item->is_active = $request->boolean('is_active');
        $item->save();

        return back()->with('success', $item->name . ' updated.');
    }

    public function destroy($id)
    {
        $item = HomeLogo::findOrFail($id);
        $item->deleteUploadedLogo();
        $item->delete();

        return back()->with('success', $item->name . ' removed.');
    }

    private function saveLogo(UploadedFile $file)
    {
        // The name and extension come from the checked picture, never from what the browser sent
        $name = Str::random(24) . '.' . $file->guessExtension();
        $file->move(public_path(HomeLogo::UPLOAD_FOLDER), $name);

        return HomeLogo::UPLOAD_FOLDER . '/' . $name;
    }
}
