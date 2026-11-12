<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomeCategoryCard;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/** Setup & Config > Home Category Cards: the cards under Search by Category on the homepage. */
class HomeCategoryCardController extends Controller
{
    private const IMAGE_RULE = 'image|mimes:jpg,jpeg,png,webp|max:2048';

    private const MESSAGES = [
        'image.required' => 'Please choose a picture for the card.',
        'image.image' => 'The card picture must be a picture.',
        'image.mimes' => 'The card picture must be a JPG, PNG or WEBP file.',
        'image.max' => 'The card picture must be 2 MB or smaller.',
        'image.uploaded' => 'The card picture could not be uploaded. It must be 2 MB or smaller.',
        'subcategory_ids.max' => 'Choose up to ' . HomeCategoryCard::MAX_LINKS . ' subcategories.',
    ];

    public function index()
    {
        $categories = Category::orderBy('ranking')->orderBy('id')->get(['id', 'catname', 'is_active']);
        $subcategories = Subcategory::orderBy('ranking')->orderBy('id')->get(['id', 'catid', 'catname', 'is_active'])->groupBy('catid');

        return view('backend.settings.home-category-cards', [
            'cards' => HomeCategoryCard::ordered()->with('category')->get(),
            'categories' => $categories,
            'subcategories' => $subcategories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        HomeCategoryCard::create([
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'image' => $this->saveImage($request->file('image')),
            'subcategory_ids' => $data['subcategory_ids'],
            'is_active' => true,
            'ranking' => $data['ranking'] ?? (int) HomeCategoryCard::max('ranking') + 1,
        ]);

        return back()->with('success', 'Card added.');
    }

    public function update(Request $request, $id)
    {
        $card = HomeCategoryCard::findOrFail($id);
        $data = $this->validated($request, false);

        if ($request->hasFile('image')) {
            $card->deleteUploadedImage();
            $card->image = $this->saveImage($request->file('image'));
        }
        $card->subcategory_ids = $data['subcategory_ids'];
        $card->category_id = $data['category_id'];
        $card->title = $data['title'];
        $card->ranking = $data['ranking'];
        $card->is_active = $request->boolean('is_active');
        $card->save();

        return back()->with('success', 'Card saved.');
    }

    public function destroy($id)
    {
        $card = HomeCategoryCard::findOrFail($id);
        $card->deleteUploadedImage();
        $card->delete();

        return back()->with('success', 'Card removed.');
    }

    private function validated(Request $request, $isNew)
    {
        $data = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'nullable|string|max:100',
            'subcategory_ids' => 'nullable|array|max:' . HomeCategoryCard::MAX_LINKS,
            'subcategory_ids.*' => 'integer',
            'ranking' => ($isNew ? 'nullable' : 'required') . '|integer|min:0|max:9999',
            'image' => ($isNew ? 'required' : 'nullable') . '|' . self::IMAGE_RULE,
        ], self::MESSAGES);

        // Only subcategories of the chosen category, in the order picked
        $own = Subcategory::where('catid', $data['category_id'])->pluck('id')->map('intval')->all();
        $data['subcategory_ids'] = array_values(array_intersect(array_map('intval', $data['subcategory_ids'] ?? []), $own));
        $data['title'] = trim((string) ($data['title'] ?? '')) ?: null;
        return $data;
    }

    private function saveImage(UploadedFile $file)
    {
        // The name and extension come from the checked picture, never from what the browser sent
        $name = Str::random(24) . '.' . $file->guessExtension();
        $file->move(public_path(HomeCategoryCard::UPLOAD_FOLDER), $name);

        return HomeCategoryCard::UPLOAD_FOLDER . '/' . $name;
    }
}
