<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reward;
use App\Models\Product_setting;
use App\Models\Reward_setting;
use DB;
use Illuminate\Support\Facades\Redirect;





class RewardController extends Controller

{

    public function index(Request $request)
    {
       $items=Product_setting::where('id','1')->first();
       $reward_status=Reward_setting::where('id','1')->get();
       $reward=DB::select('select * from rewards');
       $reward_setting=$items['reward'];
       if($reward_setting=="on")
       {
          return view('backend.reward.list-reward',compact('reward','reward_status'));
       }
       return Redirect::to('/dashboard'); 
	     
	  }


  public function addreward()
    {
       $items=Product_setting::where('id','1')->first();
    
       $reward_setting=$items['reward'];
       if($reward_setting=="on")
       {
          return view('backend.reward.add-reward');
       }

        return Redirect::to('/dashboard'); 
    }


    public function add_reward(Request $request)
    {
      $item=new Reward;
      $item->reward_points=$request->post('reward_points');
      $item->status=$request->post('status');
      $item->totalsale_from=$request->post('totalsale_from');
      $item->totalsale_to=$request->post('totalsale_to');
      $item->save();
      $request->session()->flash('success','Saved Successfully !');
      return Redirect::to('reward');
    }

    public function edit_reward($slug)
    {
       $items=Product_setting::where('id','1')->first();
       $rewards=DB::Table('rewards')->select('*')->where('id',$slug)->first();
       $reward_setting=$items['reward'];
       if($reward_setting=="on")
       {
          return view('backend.reward.edit-reward',compact('rewards'));
       }

        return Redirect::to('/dashboard'); 
    }


    public function update_reward(Request $request)
    {
      
      $id=$request->get('id');
        DB::table('rewards')
        ->where('id',$id)
        ->update(['reward_points' => $request->reward_points,
                  
                  'status'=>$request->status,
                  
                  'totalsale_from'=>$request->totalsale_from,
                  'totalsale_to'=>$request->totalsale_to,
                  
                
         ]);        
        
        $request->session()->flash('success','Saved Succesfully!');
        return Redirect::to('reward');
      
    }

     public function totalsale_active(Request $request)
    {
         
         $parent_id = $request->my_id;
         $mystatus=$request->status;

         DB::table('rewards')->where('id',$parent_id)->update(['totalsale_active' => $mystatus]);

        $msg="updated";
        return $msg;    
    }


    public function rewardproductstatus(Request $request)
    {
         
         $mystatus=$request->status;
            DB::table('reward_settings')
            ->where('id','1')
            ->update(['product_status' => $mystatus]);
        $msg="updated";
        return $msg;    
    }

    public function rewardtotalsalestatus(Request $request)
    {
         
         $mystatus=$request->status;
            DB::table('reward_settings')
            ->where('id','1')
            ->update(['totalsale_status' => $mystatus]);
        $msg="updated";
        return $msg;    
    }

     public function totalsalestatus(Request $request)
    {
         
         $parent_id = $request->my_id;
         $mystatus=$request->status;
        
            DB::table('rewards')
            ->where('id',$parent_id)
            ->update(['status' => $mystatus]);

        $msg="updated";
        return $msg;    
    }




    
	



	





    



    

    



    



}

