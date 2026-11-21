<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SupplyCountry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplyCountryController extends Controller
{
    public function index()
    {
        $items = SupplyCountry::ordered()->get();
        $available = array_diff_key(config('countries'), $items->pluck('name', 'code')->all());
        asort($available);

        return view('backend.settings.supply-countries', compact('items', 'available'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', Rule::in(array_keys(config('countries'))), 'unique:supply_countries,code'],
            'ranking' => 'nullable|integer|min:0|max:9999',
        ], [
            'code.required' => 'Please choose a country.',
            'code.in' => 'Please choose a country from the list.',
            'code.unique' => 'That country is already on the list.',
        ]);

        SupplyCountry::create([
            'code' => $data['code'],
            'name' => config('countries')[$data['code']],
            'is_active' => true,
            'ranking' => $data['ranking'] ?? (int) SupplyCountry::max('ranking') + 1,
        ]);

        return back()->with('success', config('countries')[$data['code']] . ' added.');
    }

    public function update(Request $request, $id)
    {
        $item = SupplyCountry::findOrFail($id);
        $data = $request->validate([
            'ranking' => 'required|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $item->update(['ranking' => $data['ranking'], 'is_active' => $request->boolean('is_active')]);

        return back()->with('success', $item->name . ' updated.');
    }

    public function destroy($id)
    {
        $item = SupplyCountry::findOrFail($id);
        $item->delete();

        return back()->with('success', $item->name . ' removed.');
    }
}
