<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use AkkiIo\LaravelGoogleAnalytics\Facades\LaravelGoogleAnalytics;
use AkkiIo\LaravelGoogleAnalytics\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class AnalyticsController extends Controller
{
  public function visited_page(Request $request)
  {
    $visitedpages = ga_safe(function () { return LaravelGoogleAnalytics::getMostViewsByPage(Period::months(12)); });
    $options='';
    return view('backend.analytics.visited-pages',compact('visitedpages','options')); 
  }

  public function usersbydate(Request $request)
  {
    if((!empty($request->start_date)) && (!empty($request->end_date)) )
    {
      $start_date=date('Ymd',strtotime($request->start_date));
      $end_date=date('Ymd',strtotime($request->end_date));

      $items =ga_safe(function () { return LaravelGoogleAnalytics::getTotalUsersByDate(Period::years(1)); });
      if(!empty($items))
      {
        foreach($items as $val)
        {
          if($val['date'] >= $start_date &&  $val['date'] <= $end_date)
          {
            $newarray[] = $val;
          }
          else
          {
            $newarray[] ='';
          }
        }
        $users=$newarray;
      }
      else
      {
        $users='';
      }
      
    }
    else
    {
      $users =ga_safe(function () { return LaravelGoogleAnalytics::getTotalUsersByDate(Period::months(1)); });
    }
   
    return view('backend.analytics.users_by_date',compact('users')); 
  }

 

  public function mostvisitedpage(Request $request)
  {
    $options=$request->options;
    $start_date=new Carbon($request->start_date);
    $end_date=new Carbon($request->end_date);
        
    $different_days = $start_date->diff($end_date)->days;
    if(!empty($different_days))
    {
      
      $options = '';
      // Most viewed pages inside the chosen date range
      $visitedpages = ga_safe(function () use ($start_date, $end_date) {
        return LaravelGoogleAnalytics::getMostViewsByPage(Period::create($start_date, $end_date));
      });
    }
    else
    {   
      $visitedpages = ga_safe(function () { return LaravelGoogleAnalytics::getMostViewsByPage(Period::days(30)); });
      // echo "<pre>"; print_r($visitedpages);die;
    }
     return view('backend.analytics.visited-pages',compact('visitedpages','options','start_date')); 
    }

   

   

  

    

    


   

}
