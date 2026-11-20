<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Category;
use App\Models\Cartorder;
use App\Models\About;
use App\Models\Contact;
use Illuminate\Support\Facades\Redirect;

class CommonController extends Controller
{
    

    /** Admin editor for the About Us page (/aboutus). */
    public function storeaboutus(Request $request)
    {
        $content=About::pageContent();
        $steps=config('order_steps');
        return view('backend.pages.about',compact('content','steps'));
    }

    /** Save the About Us page text and hero image (stored in abouts.content, over config/about_page.php). */
    public function updateaboutus(Request $request)
    {
        if($request->isMethod('get'))
        {
            return Redirect::to('storeaboutus');
        }
        $defaults=config('about_page');

        if($request->input('reset')==='1')
        {
            $old=(array) optional(About::find(1))->content;
            About::where('id',1)->update(['content'=>null]);
            $this->deleteAboutImage($old['hero_image'] ?? null);
            $request->session()->flash('success','The About Us page is back to the default text.');
            return Redirect::to('storeaboutus');
        }

        $text='required|string|max:300';
        $long='required|string|max:1000';
        $rules=[
            'hero_eyebrow'=>'nullable|string|max:60', 'hero_title'=>'required|string|max:150', 'hero_text'=>$long,
            'what_title'=>'required|string|max:100', 'what_intro'=>'nullable|string|max:300',
            'steps_title'=>'required|string|max:100', 'steps_intro'=>'nullable|string|max:300',
            'categories_title'=>'required|string|max:100', 'categories_intro'=>'nullable|string|max:300',
            'why_title'=>'required|string|max:100', 'why_intro'=>'nullable|string|max:300',
            'cta_title'=>'required|string|max:150', 'cta_text'=>'nullable|string|max:300',
            'hero_image'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
        foreach(array_keys($defaults['cards']) as $i) { $rules["cards.$i.title"]='required|string|max:80'; $rules["cards.$i.text"]=$text; }
        foreach(array_keys($defaults['points']) as $i) { $rules["points.$i.title"]='required|string|max:80'; $rules["points.$i.text"]=$text; }
        foreach(array_keys($defaults['steps']) as $n) { $rules["steps.$n"]=$text; }
        $data=$request->validate($rules,['required'=>'Please fill in every field (or press "Reset to default text").','hero_image.max'=>'The image must be 4 MB or smaller.']);

        $old=(array) optional(About::find(1))->content;
        $content=[];
        foreach($defaults as $key=>$default)
        {
            if($key==='hero_image') continue;
            $content[$key]=$data[$key] ?? (is_array($default) ? [] : '');
        }
        $content['hero_image']=$old['hero_image'] ?? null;
        if($request->hasFile('hero_image'))
        {
            $name='about-hero-'.\Illuminate\Support\Str::random(12).'.'.strtolower($request->file('hero_image')->getClientOriginalExtension());
            $request->file('hero_image')->move(public_path('upload/pages/'),$name);
            $this->deleteAboutImage($content['hero_image']);
            $content['hero_image']=$name;
        }
        elseif($request->boolean('remove_image'))
        {
            $this->deleteAboutImage($content['hero_image']);
            $content['hero_image']=null;
        }

        if(!About::where('id',1)->exists()) { DB::table('abouts')->insert(['id'=>1]); }
        About::where('id',1)->update(['content'=>json_encode($content)]);
        $request->session()->flash('success','About Us page saved.');
        return Redirect::to('storeaboutus');
    }

    private function deleteAboutImage($name)
    {
        if($name && preg_match('/^about-hero-[A-Za-z0-9]+\.(jpe?g|png|webp)$/',$name) && is_file(public_path('upload/pages/'.$name)))
        {
            @unlink(public_path('upload/pages/'.$name));
        }
    }



    public function storecontactus(Request $request)
    {
        $items=Contact::where('id','1')->first();
        return view('backend.pages.contact',compact('items'));
    }


    public function updatecontactus(Request $request)
    {
       
            DB::table('contacts')
                        ->where('id','1')
                        ->update([
                            'description' => $request->description,
                            'address' => $request->address,
                            'phone1' => $request->phone1,
                            'phone2' => $request->phone2,
                            'email1' => $request->email1,
                            'email2' => $request->email2,
                            'map' => $request->map,
  
                            
                    ]);



     
       $request->session()->flash('success','Updated Successfully!'); 
        return Redirect::to('storecontactus');
    }

}
