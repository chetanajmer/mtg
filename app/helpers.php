<?php
/**
 * Create a PayTabs payment page and return its redirect URL.
 * $orderdata can be an array (preferred) or a JSON string.
 */
function paytabs($orderdata)
{
		if(is_array($orderdata))
		{
			$orderdata['profile_id']=(int)config('services.paytabs.profile_id');
			$orderdata=json_encode($orderdata);
		}

		$url = rtrim(config('services.paytabs.base_url'),'/')."/payment/request";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('authorization: '.config('services.paytabs.server_key'), 'Content-Type: application/json'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $orderdata);
		$resp = curl_exec($curl);
		curl_close($curl);

		$data=json_decode($resp, true);
		return $data['redirect_url'] ?? null;
}

/**
 * Ask PayTabs (server to server) for the real result of a transaction.
 * Returns the decoded response, or null when the request failed.
 */
function paytabs_query($tran_ref)
{
		$url = rtrim(config('services.paytabs.base_url'),'/')."/payment/query";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('authorization: '.config('services.paytabs.server_key'), 'Content-Type: application/json'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array(
			'profile_id' => (int)config('services.paytabs.profile_id'),
			'tran_ref' => (string)$tran_ref,
		)));
		$resp = curl_exec($curl);
		curl_close($curl);

		$data=json_decode($resp, true);
		return is_array($data) ? $data : null;
}

/**
 * Run a Google Analytics query, but return $default instead of crashing the
 * page when the credentials file is missing or the API call fails (for
 * example on a local copy of the site).
 */
function ga_safe(callable $query, $default = [])
{
		try
		{
			$result=$query();
			return $result===null ? $default : $result;
		}
		catch(\Throwable $e)
		{
			\Log::warning('Google Analytics unavailable: '.$e->getMessage());
			return $default;
		}
}

/**
 * Check the "signature" header PayTabs sends with every callback.
 */
function paytabs_valid_callback($raw_body,$signature)
{
		$server_key=config('services.paytabs.server_key');
		if(empty($server_key) || empty($signature))
		{
			return false;
		}
		return hash_equals(hash_hmac('sha256', $raw_body, $server_key), (string)$signature);
}

/**
 * Staff inbox for quotes, contact messages and order responses:
 * QUOTE_REQUEST_EMAIL (config mail.staff_address), else the admin Header Settings email.
 */
function staff_email()
{
		return config('mail.staff_address')
			?: optional(\Illuminate\Support\Facades\DB::table('header_settings')->where('id', 1)->first())->headeremail;
}
?>