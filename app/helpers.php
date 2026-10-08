<?php 


function sendmail_to_admin($details,$admin_email)
{
	Mail::send('mail/enquiry_admin_email',  $details, function($message) use ($admin_email) {
      $message->to($admin_email, 'SilverGiftz')->subject
        ('Enquiry Alert');
      $message->from($admin_email,'Enquiry Information');
    });
}

function sendmail_to_customer($details,$customer_email,$admin_email)
{
	Mail::send('mail/enquiry_user_email',  $details, function($message) use ($customer_email,$admin_email) {
    $message->to($customer_email, 'SilverGiftz')->subject
      ('Enquiry Alert');
    $message->from($admin_email,'Enquiry Information');
  });
          
}

function paytabs($orderdata)
{
	  $url = "https://secure.paytabs.com/payment/request";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('authorization: S9JNL9KMLB-HZMB2JN6KW-2K9TK9NZ6T', 'Content-Type: application/json'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$data = $orderdata;

		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$resp = curl_exec($curl);
		curl_close($curl);
		// echo $resp;

		$data=json_decode($resp, true);
		//print_r($data);die;

		return $url=$data['redirect_url'];
		///header("Location:".$url);
}

?>