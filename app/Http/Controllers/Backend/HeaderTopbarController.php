<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\HeaderTopbarItem;
use Illuminate\Http\Request;

/** Setup & Config > Header Top Bar: the language and currency menus and the offer texts in the black top bar. */
class HeaderTopbarController extends Controller
{
    private const MESSAGES = [
        'text.required' => 'Please type the text to show.',
        'text.max' => 'Keep it to 60 characters so it fits the bar.',
        'link.regex' => 'The link must start with / (a page on this site) or https://.',
    ];

    public function index()
    {
        return view('backend.settings.header-topbar', [
            'items' => HeaderTopbarItem::ordered()->get(),
            'types' => HeaderTopbarItem::TYPES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(false), self::MESSAGES);
        HeaderTopbarItem::create([
            'type' => 'offer', // the language and currency menus are fixed; only offers are added
            'text' => trim($data['text']),
            'link' => $data['link'] ?? null,
            'is_active' => true,
            'ranking' => $data['ranking'] ?? (int) HeaderTopbarItem::max('ranking') + 1,
        ]);

        return back()->with('success', 'Offer added.');
    }

    public function update(Request $request, $id)
    {
        $item = HeaderTopbarItem::findOrFail($id);
        $data = $request->validate($this->rules(true), self::MESSAGES);
        $item->text = trim($data['text']);
        $item->link = $item->type === 'offer' ? ($data['link'] ?? null) : null;
        $item->ranking = $data['ranking'];
        $item->is_active = $request->boolean('is_active');
        $item->save();

        return back()->with('success', 'Saved.');
    }

    public function destroy($id)
    {
        $item = HeaderTopbarItem::findOrFail($id);
        if ($item->type !== 'offer') {
            return back()->with('error', 'The language and currency menus can be switched off but not removed.');
        }
        $item->delete();

        return back()->with('success', 'Offer removed.');
    }

    private function rules($isUpdate)
    {
        return [
            'text' => 'required|string|max:60',
            'link' => ['nullable', 'string', 'max:255', 'regex:#^(/|https://)#'],
            'ranking' => ($isUpdate ? 'required' : 'nullable') . '|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ];
    }
}
