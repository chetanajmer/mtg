@extends('layouts.frontapp')
@section('content')


<section id="content">
    <div class="wrap-bread-crumb">
            <div class="container">
                <div class="bread-crumb">
                    <a href="{{url('/')}}">Home</a>
                    <span>Contact Us</span>
                </div>
            </div>
        </div>
        <div class="content-page">
            <div class="container">
                <div class="content-about content-contact-page">
                    <h2 class="title30 play-font dark font-bold text-uppercase">contact us</h2>
                    @if(!empty($items->map))
                    <div class="contact-google-map bg-white border">
                        <div id="map" class="map-custom">
                        @php echo $items->map; @endphp
                        </div>
                        <!-- <script>
                          function initMap() {
                            var myLatLng = {lat: 40.707914, lng: -74.009628};

                            var map = new google.maps.Map(document.getElementById('map'), {
                              zoom: 12,
                              center: myLatLng
                            });

                            var marker = new google.maps.Marker({
                              position: myLatLng,
                              map: map,
                            });
                          }
                        </script> -->
                        <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEkQ6AW_lnHAzPiXPdSNy1GKXiI1I9AGg&amp;callback=initMap" async defer></script> -->
                    </div>
                    @endif
                    <!-- End Google Map -->
                    
                    <div class="contact-form-faq">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="contact-form-page">
                                    <h2 class="title18 dark font-bold text-uppercase play-font">Contact Form</h2>
                                    <form class="contact-form">
                                        <p class="contact-name">
                                            <input class="dark border" onblur="if (this.value=='') this.value = this.defaultValue" onfocus="if (this.value==this.defaultValue) this.value = ''" value="User name*" type="text">
                                        </p>
                                        <p class="contact-email">
                                            <input class="dark border" onblur="if (this.value=='') this.value = this.defaultValue" onfocus="if (this.value==this.defaultValue) this.value = ''" value="Email*" type="text">
                                        </p>
                                        <p class="contact-message">
                                            <textarea class="dark border" onblur="if (this.value=='') this.value = this.defaultValue" onfocus="if (this.value==this.defaultValue) this.value = ''" cols="30" rows="10">Your comment*</textarea>
                                        </p>
                                        <p class="contact-submit">
                                            <input  class="shop-button white bg-dark" type="submit" value="Post Comment">
                                        </p>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="contact-faq">
                                    <!-- <h2 class="title18 dark font-bold text-uppercase play-font">FAQs</h2> -->
                                    <p>{{$items->description}}</p>
                                    <!-- <div class="contact-accordion toggle-tab">
                                        <div class="item-toggle-tab active">
                                            <h2 class="toggle-tab-title dark">At vero eos et accusamus et iusto</h2>
                                            <p class="desc toggle-tab-content dark opaci">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid animi archi tecto aspernatur assumenda cum inventore labore magnam </p>
                                        </div>
                                        <div class="item-toggle-tab">
                                            <h2 class="toggle-tab-title dark">Dignissimos ducimus qui blanditiis</h2>
                                            <p class="desc toggle-tab-content dark opaci">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid animi archi tecto aspernatur assumenda cum inventore labore magnam </p>
                                        </div>
                                        <div class="item-toggle-tab">
                                            <h2 class="toggle-tab-title dark">Raesentium voluptatum deleniti</h2>
                                            <p class="desc toggle-tab-content dark opaci">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid animi archi tecto aspernatur assumenda cum inventore labore magnam </p>
                                        </div>
                                        <div class="item-toggle-tab">
                                            <h2 class="toggle-tab-title dark">At vero eos et accusamus et iusto</h2>
                                            <p class="desc toggle-tab-content dark opaci">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid animi archi tecto aspernatur assumenda cum inventore labore magnam </p>
                                        </div>
                                        <div class="item-toggle-tab">
                                            <h2 class="toggle-tab-title dark">Dignissimos ducimus qui blanditiis</h2>
                                            <p class="desc toggle-tab-content dark opaci">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid animi archi tecto aspernatur assumenda cum inventore labore magnam </p>
                                        </div>
                                        <div class="item-toggle-tab">
                                            <h2 class="toggle-tab-title dark">Raesentium voluptatum deleniti</h2>
                                            <p class="desc toggle-tab-content dark opaci">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid animi archi tecto aspernatur assumenda cum inventore labore magnam </p>
                                        </div>
                                    </div> -->
                                    <div class="contact-page-info blockquote">
                        <div class="row">
                        @if(!empty($items->address))
                            <div class="col-md-5 col-sm-12 col-xs-12">
                                <div class="contact-box contact-address-box">
                                    <span class="dark"><i class="fa fa-home"></i></span>
                                    <label class="title16 dark">ADDRESS:</label>
                                    <p class="desc">{{$items->address}}</p>
                                </div>
                            </div>
                        @endif
                        @if(!empty($items->phone1))
                            <div class="col-md-12 col-sm-12 col-xs-12 mb-20">
                                <div class="contact-box">
                                    <span class="dark"><i class="fa fa-phone"></i></span>
                                    <ul class="list-inline-block">
                                        <li>
                                            <label class="title16 dark">PHONES:</label>
                                        </li>
                                        <li>
                                            <span class="dark">{{$items->phone1}}</span>
                                            <span class="dark">{{$items->phone2}}</span>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                                <!-- <div class="contact-box">
                                    <span class="dark"><i class="fa fa-print"></i></span>
                                    <ul class="list-inline-block">
                                        <li>
                                            <label class="title16 dark">Fax:</label>
                                        </li>
                                        <li>
                                            <span class="dark">800-6969-0044;</span>
                                        </li>
                                    </ul>
                                </div> -->
                            </div>
                            @if(!empty($items->email1))
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="contact-box contact-email-box">
                                    <span class="dark"><i class="fa fa-envelope"></i></span>
                                    <label class="title16 dark">e-mail:</label>
                                    <p class="desc"><a href="#" class="dark">{{$items->email1}}</a></p>
                                    <p class="desc"><a href="#" class="dark">{{$items->email2}}</a></p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <!-- End Contact Info -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Content -->

@endsection