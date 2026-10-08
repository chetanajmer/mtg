@php
$cartorders =\App\Models\Cartorder::where('orderid',$orderid)->get();
$main_colors=App\Models\Color::get();
@endphp

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="robots" content="noindex" />
      <title>Silvergiftz</title>
   </head>
<body style="margin:0;font-family:Calibri;">
<table border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;border-collapse:collapse;background-color:#f7f7f7;">
    <tbody>
        <tr>
            <td width="100%" valign="top" border="0" style="width:100%;padding:0;border:0;">
                <div align="center">
                    <table border="0" cellspacing="0" cellpadding="0" width="800" style="width:800px;border-collapse:collapse;background-color:#f7f7f7;border:0;">
                        <tbody>
                          <tr border="0">
                            <td valign="top" border="0" style="padding:20px 20px 20px 20px">
                              <div align="center">
                                <table border="0" cellspacing="0" cellpadding="0" width="800" style="border-collapse:collapse;border:0;">
                                  <tbody>
                                    <tr border="0">
                                      <td border="0">
                                        <div align="center">
                                          <img src="https://silvergiftz.com/assets/img/logo/logo.png" alt="" width="250" border="0">
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                    </table>



                    <table border="0" cellspacing="0" cellpadding="0" width="800" style="width:800px;border-collapse:collapse;background-color:#fff;border-radius:10px;-webkit-border-radius:10px;border:0;">
                        <tbody>
                          <tr border="0">
                            <td valign="top" border="0" style="padding:20px 20px 20px 20px;border:0;">
                              <div align="center">
                                <table border="0" cellspacing="0" cellpadding="0" width="800" style="border-collapse:collapse;border:0;">
                                  <tbody>
                                    <tr>
                                      <div align="center">
                                        <th border="0" style="font-size:24px;text-transform:uppercase;color:#000;margin:0;padding:0;text-align:center;font-family:Calibri;font-weight:600;border:0;">New Enquiry</th>
                                      </div>
                                    </tr>
                                    <tr>
                                      <div align="center">
                                          <td border="0" style="font-size:22px;color:#000;margin:0;padding:0;text-align:center;border:1px #ffffff solid;text-transform:uppercase;font-family:Calibri;">
                                            <div style="font-size:22px;color:#000;margin:10px 0 0 0;padding:7px 15px;text-align:center;border:2px #000000 solid;text-transform:uppercase;display:inline-block;border-radius:10px;-webkit-border-radius:10px;font-family:Calibri;font-weight:600;">Order no : {{$orderid}}</div>
                                          </td>
                                      </div>
                                    </tr>
                                    <tr><td border="0" height="20"></td></tr>
                                    <!-- <tr>
                                      <td border="0" style="font-size:20px;color:#000;margin:0;padding:0;text-align:left;border-top:0px #ffffff solid;border-left:0px #ffffff solid;border-right:0px #ffffff solid;border-bottom:0px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:600;">
                                            Hello Devendra,
                                      </td>
                                    </tr>
                                    <tr><td border="0" height="10"></td></tr>
                                    <tr>
                                        <td border="0" style="font-size:18px;color:#000;margin:0;padding:0;text-align:left;border-top:0px #ffffff solid;border-left:0px #ffffff solid;border-right:0px #ffffff solid;border-bottom:0px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:400;">Thank you for order information with us.
                                        </td>
                                    </tr> -->
                                    <tr><td border="0" height="20"></td></tr>
                                    <tr>
                                      <td border="0" style="font-size:22px;color:#000;margin:0;padding:0 0 10px 0;text-align:left;border-bottom:1px #dddddd solid;text-transform:uppercase;font-family:Calibri;font-weight:600;">
                                           Enquiry information
                                      </td>
                                    </tr>
                                    <tr><td border="0" height="15"></td></tr>
                                    <tr>
                                      <td>
                                        <table border="0" cellspacing="0" cellpadding="0" width="800" style="width:800px;border-collapse:collapse;background-color:#fff;border-radius:10px;-webkit-border-radius:10px;border:0;">
                                            <tbody>
                                              <tr border="0">
                                                <td valign="top" border="0" style="padding:0px 0px 0px 0px;border:0;">
                                                  <div align="center">
                                                    <table border="0" cellspacing="0" cellpadding="0" width="800" style="border-collapse:collapse;border:0;">
                                                      <tbody>
                                                          @foreach($cartorders as $cartorder)
                                                            @php
                                                              $split_modelno=str_split($cartorder->modelno,3);
                                                            @endphp
                                                        <tr>
                                                        <td width="120" style="vertical-align:middle;width:100px;border:1px #ddd solid;border-radius:10px;-webkit-border-radius:10px;-moz-border-radius:10px;">
                                                           
                                                              @if($split_modelno[0]=='SGM')
                                                                <img  src="{{$cartorder->thumbnail}}" border="0" width="100" height="100" alt="{{$cartorder->name}}" style="box-sizing:border-box;margin-top:0px;padding: 0px;border:0px #ffffff solid;width:100px;">
                                                              @else
                                                                <img  src="{{ URL::asset('upload/product/thumbnail/'.$cartorder->thumbnail) }}" border="0" width="100" height="100" alt="{{$cartorder->name}}" style="box-sizing:border-box;margin-top:0px;padding: 0px;border:0px #ffffff solid;width:100px;">
                                                              @endif
                                                          </td>
                                                          <td width="10"></td>
                                                          <td border="0" style="border:0;text-align:left;vertical-align:top;margin:0 0 0 20px">
                                                            <table border="0" cellspacing="0" cellpadding="0" width="" style="border-collapse:collapse;border:0;">
                                                              <tbody>
                                                                <tr>
                                                                  <td style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;border:1px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:600;">{{$cartorder->name}}</td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                  <td style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;border:1px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:400;">Model Number : 
                                                                    <b style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;text-transform:uppercase;font-family:Calibri;font-weight:600;">{{$cartorder->modelno}}</b>
                                                                  </td>
                                                                </tr>

                                                                @if(!empty($cartorder->color))
                                                                   @php $colors=json_decode($cartorder->color);
                                                                   $color=array(); @endphp
                                                                <tr>
                                                                  <td style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;border:1px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:400;">Colors : 

                                                                     @php
                                                                      foreach($colors as $val)
                                                                      {
                                                                        $get_color=explode('_',$val);
                                                                        $color=$get_color[0];
                                                                        $qty=$get_color[1];
                                                                        $main_colors=App\Models\Color::get();
                                                                        foreach($main_colors as $main_colors)
                                                                        {
                                                                          $colorcode=$main_colors->code;
                                                                          $colorname=$main_colors->name;
                                                                          if($color==$colorcode)
                                                                          { @endphp
                                                                        <span style="width:12px;height:12px;border:1px #ddd solid;display:inline-block;margin:0;padding:0;vertical-align:middle;border-radius:12px;-webkit-border-radius:12px;-moz-border-radius:12px;background-color:{{$colorcode}};"> </span>
                                                                        <span style="font-weight:400;font-size: 16px;font-family: Roboto, sans-serif, serif, EmojiFont;margin: 0px 0 0 0;display:inline-block;">{{$qty}}</span>
                                                                         @php  }  } } @endphp
                                                                  </td>
                                                                </tr>
                                                                     @endif
                                                                <tr>
                                                                  <td style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;border:1px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:400;">Total Quantity : <b style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;border-bottom:0px #ffffff solid;text-transform:uppercase;font-family:Calibri;font-weight:600;">{{$cartorder->quantity}}   </b>
                                                                  </td>
                                                                </tr>
                                                                
                                                                
                                                                
                                                              </tbody>
                                                            </table>
                                                          </td>
                                                        </tr>
                                                        <tr><td border="0" height="20"></td></tr>
                                                        @endforeach
                                                      </tbody>
                                                    </table>
                                                  </div>
                                                </td>
                                              </tr>
                                            </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                     <tr>
                                      <td border="0" style="font-size:22px;color:#000;margin:0;padding:0 0 10px 0;text-align:left;border-bottom:1px #dddddd solid;text-transform:uppercase;font-family:Calibri;font-weight:600;">
                                           Customer information
                                      </td>

                                    </tr>
                                    <tr><td border="0" height="15"></td></tr>
                                    <tr>
                                      <td>
                                        <table border="0" cellspacing="0" cellpadding="0" width="800" style="width:800px;border-collapse:collapse;background-color:#fff;border-radius:10px;-webkit-border-radius:10px;border:0;">
                                            <tbody>
                                              <tr border="0">
                                                <td valign="top" border="0" style="padding:0px 0px 0px 0px;border:0;">
                                                  <div align="center">
                                                    <table border="0" cellspacing="0" cellpadding="0" width="800" style="border-collapse:collapse;border:0;">
                                                      <tbody>
                                                        <tr>
                                                          <td border="0" style="border:0;text-align:left;vertical-align:top;margin:0 0 0 20px">
                                                            <table border="0" cellspacing="0" cellpadding="0" width="" style="border-collapse:collapse;border:0;">
                                                              <tbody>
                                                               
                                                                <tr>
                                                                  <td style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;border:1px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:400;">Name : 
                                                                    <b style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;text-transform:uppercase;font-family:Calibri;font-weight:600;">{{$customer_name}}</b>
                                                                  </td>
                                                                </tr>
                                                               
                                                                <tr>
                                                                  <td style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;border:1px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:400;">Phone : <b style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;border-bottom:0px #ffffff solid;text-transform:uppercase;font-family:Calibri;font-weight:600;">{{$customer_phone}}</b>
                                                                  </td>
                                                                </tr>

                                                                <tr>
                                                                  <td style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;border:1px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:400;">Email : <b style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;border-bottom:0px #ffffff solid;text-transform:uppercase;font-family:Calibri;font-weight:600;">{{$customer_email}}</b>
                                                                  </td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                  <td style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;border:1px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:400;">Company : <b style="font-size:18px;color:#000;margin:0;padding:0 0 0px 0;text-align:left;border-bottom:0px #ffffff solid;text-transform:uppercase;font-family:Calibri;font-weight:600;">{{$customer_company}}</b>
                                                                  </td>
                                                                </tr>
                                                                
                                                              </tbody>
                                                            </table>
                                                          </td>
                                                        </tr>
                                                        <tr><td border="0" height="20"></td></tr>
                                                        <tr>
                                                        <td border="0" style="font-size:22px;color:#000;margin:0;padding:0 0 10px 0;text-align:left;border-bottom:1px #dddddd solid;text-transform:uppercase;font-family:Calibri;font-weight:600;">
                                                           
                                                        </td>

                                                      </tr>
                                                      </tbody>
                                                    </table>
                                                  </div>
                                                </td>
                                              </tr>
                                            </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                 <!--    <tr>
                                        <td border="0" style="font-size:18px;color:#000;margin:0;padding:10px 0 0 0;text-align:left;border-top:1px #dddddd solid;border-left:0px #ffffff solid;border-right:0px #ffffff solid;border-bottom:0px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:400;">Thank you for order information with us.
                                        </td>
                                    </tr> -->
                                    <!-- <tr><td border="0" height="10"></td></tr>
                                    <tr>
                                        <td border="0" style="font-size:18px;color:#000;margin:0;padding:0px 0 0 0;text-align:left;border-top:0px #ffffff solid;border-left:0px #ffffff solid;border-right:0px #ffffff solid;border-bottom:0px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:400;">     Email : <a href="mailto:enquiry@silvergiftz.com" style="text-decoration: none; color: #000;font-size: 18px;margin:0;padding:0px 0 0 0;">enquiry@silvergiftz.com</a>
                                        </td>
                                    </tr>
                                    <tr><td border="0" height="10"></td></tr>
                                    <tr>
                                        <td border="0" style="font-size:18px;color:#000;margin:0;padding:0px 0 0 0;text-align:left;border-top:0px #ffffff solid;border-left:0px #ffffff solid;border-right:0px #ffffff solid;border-bottom:0px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:400;">     Call : <a href="tel:+97143936753" style="text-decoration: none; color: #000000;font-size: 18px;margin:0;padding:0px 0 0 0;">+971 4 393 6753</a>
                                        </td>
                                    </tr> -->
                                    <tr><td border="0" height="20"></td></tr>
                                    <tr>
                                        <td border="0" style="font-size:18px;color:#00000;margin:0;padding:0px 0 0 0;text-align:left;border-top:0px #ffffff solid;border-left:0px #ffffff solid;border-right:0px #ffffff solid;border-bottom:0px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:400;">    Thank you
                                        </td>
                                    </tr>
                                    <tr>
                                        <td border="0" style="font-size:18px;color:#000;margin:0;padding:0px 0 0 0;text-align:left;border-top:0px #ffffff solid;border-left:0px #ffffff solid;border-right:0px #ffffff solid;border-bottom:0px #ffffff solid;text-transform:none;font-family:Calibri;font-weight:600;">    SilverGiftz
                                        </td>
                                    </tr>
                                    <tr>
                                      
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                    </table>
                    




<!-- 
                    <table border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;border-collapse:collapse;background-color:#f7f7f7;">
                        <tbody>
                          <tr><th>&nbsp;</th></tr>
                          <tr>
                            <th style="font-size:24px;text-transform:uppercase;color:#000;margin:0;padding:0;">Follow us</th>
                          </tr>
                          <tr>
                            <td valign="top" style="padding:15px 0px 40px 0px">
                              <div align="center">
                                <table border="0" cellspacing="0" cellpadding="0" width="600" style="border-collapse:collapse;">
                                  <tbody>
                                    <tr>
                                      <td valign="top">
                                        <div align="center">
                                          <table border="0" cellspacing="0" cellpadding="0" width="" style="border-collapse:collapse;">
                                            <tbody>
                                              <tr>
                                                <td width="60">
                                                  <a href="https://www.facebook.com/silverpixelz.mktg" target="_blank"><img src="images/fb_icon.png" alt="" border="0" width="50"></a>
                                                </td>
                                                <td width="60">
                                                  <a href="https://www.instagram.com/silverpixelz" target="_blank"><img src="images/insta_icon.png" alt="" border="0" width="50"></a>
                                                </td>
                                                <td width="60">
                                                  <a href="https://www.linkedin.com/company/silverpixelz-mktg" target="_blank"><img src="images/linkedin_icon.png" alt="" border="0" width="50"></a>
                                                </td>
                                                <td width="60">
                                                  <a href="https://www.tiktok.com/@silvergiftz" target="_blank"><img src="images/tiktok_icons.png" alt="" border="0" width="50"></a>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                    </table> -->
                </div>
            </td>
        </tr>
    </tbody>
</table>
</body>
  
</html>

