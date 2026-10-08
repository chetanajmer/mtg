@php

//$category=\App\Models\Category::where('id',$items->category)->first();

$homesettings=\App\Models\Homepage_setting::where('id','1')->first();

//$complete_url=Request::fullUrl(); 

$role=session()->get('role');

//$relatedproducts=\App\Models\Product::where('category',$category->id)->where('is_active',"online")->get()->take(9);





$baseurl= url('/'); 

$total=0;

//echo count($items_array); die;


@endphp

@extends('layouts.frontapp')

    

@section('content')

<!-- breadcrumb-section start -->

<nav class="breadcrumb-section theme1 bg-lighten2 pt-20 pb-10">

    <div class="container">

        <div class="row">

           <!--  <div class="col-12">

                <div class="section-title text-center mb-15">

                    <h2 class="title text-dark text-capitalize">Checkout</h2>

                </div>

            </div> -->

            <div class="col-12">

                <ol class="breadcrumb bg-transparent m-0 p-0 align-items-center">

                    <li class="breadcrumb-item"><a href="{{url('/')}}" >Home</a></li>

                    <li class="breadcrumb-item active" aria-current="page">Wishlist</li>

                </ol>

            </div>

        </div>

    </div>

</nav>

<!-- breadcrumb-section end -->

<!-- product tab start -->




@if(count($items_array)>0)

<section class="whish-list-section theme1 pt-80 pb-80">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="title mb-30 pb-25 text-capitalize">Wishlist</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" scope="col">Product Image</th>
                                <th class="text-center" scope="col">Product Details</th>
                                <th class="text-center" scope="col">Price</th>
                                <th class="text-center" scope="col">action</th>
                                <th class="text-center" scope="col">Checkout</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items_array as $itemsarr)
                            @php
                            $products=\App\Models\Product::where('slug',$itemsarr->productslug)->get();
                            @endphp
                            @foreach($products as $items)
                                <tr>
                                <th class="text-center" scope="row">
                                    <img src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" alt="{{$items->name}}">
                                </th>
                                <td class="text-center">
                                    <span class="whish-title">{{$items->modelno}} - {{$items->name}}</span>
                                </td>
                              
                                <td class="text-center">
                                    <span class="whish-list-price">
                                        {{$homesettings->currencysymbol}} {{$items->price}}
                                    </span></td>

                                <td class="text-center">
                                    <a href="{{url('deletewishlist/'.$itemsarr->id)}}"> <span class="trash"><i class="fas fa-trash-alt"></i> </span></a>
                                </td>
                                <td class="text-center">
                                    <a href="{{url('product/'.$items->slug)}}" class="btn theme-btn--dark1 btn--lg">buy now</a>
                                </td>
                                </tr>
                            @endforeach
                           @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@else

<section class="check-out-section pt-80 pb-50">

    <div class="container">

        <div class="row">

            <div class="col-lg-12 mb-30" style="text-align: center;">There is No items in Wishlist !</div>

        </div>

    </div>

</section>

@endif            

<!-- product tab end -->

@push('custom-scripts')






@endpush

@endsection