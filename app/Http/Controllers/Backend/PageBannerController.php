<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PageBanner;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/** Banners > Page Banners: the strips under the header, several per page, a different one on every visit. */
class PageBannerController extends Controller
{
    private const IMAGE_RULE = 'image|mimes:jpg,jpeg,png,webp|max:4096';

    private const MESSAGES = [
        'images.required' => 'Please choose at least one banner picture.',
        'images.max' => 'Please upload up to 20 banners at a time.',
        'images.*.image' => 'Each banner must be a picture.',
        'images.*.mimes' => 'Banners must be JPG, PNG or WEBP files.',
        'images.*.max' => 'Each banner must be 4 MB or smaller.',
        'images.*.uploaded' => 'A banner could not be uploaded. Each must be 4 MB or smaller.',
        'image.image' => 'The banner must be a picture.',
        'image.mimes' => 'The banner must be a JPG, PNG or WEBP file.',
        'image.max' => 'The banner must be 4 MB or smaller.',
        'link.regex' => 'The link must start with / (a page on this site) or https://.',
    ];

    public function index(Request $request)
    {
        $pages = config('page_banners.pages');
        $page = array_key_exists($request->query('page'), $pages) ? $request->query('page') : 'default';
        $counts = PageBanner::selectRaw('page, COUNT(*) as total, SUM(is_active) as active')->groupBy('page')->get()->keyBy('page');

        return view('backend.settings.page-banners', [
            'pages' => $pages,
            'page' => $page,
            'counts' => $counts,
            'banners' => PageBanner::where('page', $page)->ordered()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'page' => ['required', Rule::in(array_keys(config('page_banners.pages')))],
            'images' => 'required|array|max:20',
            'images.*' => 'required|' . self::IMAGE_RULE,
            'alt' => 'nullable|string|max:150',
            'link' => ['nullable', 'string', 'max:255', 'regex:#^(/|https://)#'],
        ], self::MESSAGES);

        $next = (int) PageBanner::where('page', $data['page'])->max('ranking');
        foreach ($request->file('images') as $file) {
            PageBanner::create([
                'page' => $data['page'],
                'image' => $this->saveImage($file),
                'alt' => trim((string) ($data['alt'] ?? '')) ?: null,
                'link' => $data['link'] ?? null,
                'is_active' => true,
                'ranking' => ++$next,
            ]);
        }
        $n = count($request->file('images'));

        return redirect()->route('page-banners.index', ['page' => $data['page']])
            ->with('success', $n . ' banner' . ($n === 1 ? '' : 's') . ' added to ' . config('page_banners.pages')[$data['page']]['label'] . '.');
    }

    public function update(Request $request, $id)
    {
        $banner = PageBanner::findOrFail($id);
        $data = $request->validate([
            'alt' => 'nullable|string|max:150',
            'link' => ['nullable', 'string', 'max:255', 'regex:#^(/|https://)#'],
            'ranking' => 'required|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|' . self::IMAGE_RULE,
        ], self::MESSAGES);

        if ($request->hasFile('image')) {
            $banner->deleteUploadedImage();
            $banner->image = $this->saveImage($request->file('image'));
        }
        $banner->alt = trim((string) ($data['alt'] ?? '')) ?: null;
        $banner->link = $data['link'] ?? null;
        $banner->ranking = $data['ranking'];
        $banner->is_active = $request->boolean('is_active');
        $banner->save();

        return redirect()->route('page-banners.index', ['page' => $banner->page])->with('success', 'Banner saved.');
    }

    public function destroy($id)
    {
        $banner = PageBanner::findOrFail($id);
        $banner->deleteUploadedImage();
        $banner->delete();

        return redirect()->route('page-banners.index', ['page' => $banner->page])->with('success', 'Banner removed.');
    }

    private function saveImage(UploadedFile $file)
    {
        // The name and extension come from the checked picture, never from what the browser sent
        $name = Str::random(24) . '.' . $file->guessExtension();
        $file->move(public_path(PageBanner::UPLOAD_FOLDER), $name);

        return PageBanner::UPLOAD_FOLDER . '/' . $name;
    }
}
