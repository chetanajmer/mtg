<?php

namespace App\Http\Controllers;

use App\Models\NewBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewBrandController extends Controller
{
    public function index()
    {
        $brands = NewBrand::orderBy('id', 'desc')->get();
        return view('backend.newbrand.index', compact('brands'));
    }

    public function create()
    {
        return view('backend.newbrand.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->brand_name) . '-' . rand(1000, 9999);

        $brand = new NewBrand();
        $brand->brand_name = $request->brand_name;
        $brand->slug = $slug;

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/newbrand/'), $imageName);
            $brand->logo = $imageName;
        }

        $brand->save();

        return redirect()->route('newbrand.index')->with('success', 'Brand added successfully!');
    }

    public function edit($id)
    {
        $brand = NewBrand::findOrFail($id);
        return view('backend.newbrand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $brand = NewBrand::findOrFail($id);
        $brand->brand_name = $request->brand_name;

        if ($request->hasFile('logo')) {
            if ($brand->logo && file_exists(public_path('upload/newbrand/' . $brand->logo))) {
                unlink(public_path('upload/newbrand/' . $brand->logo));
            }

            $image = $request->file('logo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/newbrand/'), $imageName);
            $brand->logo = $imageName;
        }

        $brand->save();

        return redirect()->route('newbrand.index')->with('success', 'Brand updated successfully!');
    }

    public function destroy($id)
    {
        $brand = NewBrand::findOrFail($id);

        if ($brand->logo && file_exists(public_path('upload/newbrand/' . $brand->logo))) {
            unlink(public_path('upload/newbrand/' . $brand->logo));
        }

        $brand->delete();

        return redirect()->route('newbrand.index')->with('success', 'Brand deleted successfully!');
    }
}
