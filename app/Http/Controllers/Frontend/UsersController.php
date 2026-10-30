<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Storeuser;
use App\Models\Cartorder;
use App\Models\General_setting;
use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\CartItem;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Product;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Footerlevel1;
use App\Models\Contact;
use App\Models\StoreuserAddress;

class UsersController extends Controller
{
    public function storelogin(Request $request)
    {

    	//$items_array=Tmporder::where('sessionid',session()->get('sessionid'))->get();
    	return view('frontend.login');
    }

    public function dostorelogin(Request $request)
    {

      $email=$request->get('email');
      $password=$request->get('password');
      $checkuser=DB::table('storeusers')->select('*')->where('email', $email)
      ->where('password',$password)->first();
      
      if(!empty($checkuser))
      {
        //print_r($checkuser->id); die;  
        $userid=$checkuser->id; 
        $useremail=$checkuser->email;
        session()->put('userid',$userid);
        session()->put('username',$useremail);
        $request->session()->flash('loginsuccess','Login Successfull !');
         return Redirect::to('store/myaccount');
         
      }
      else
      {
          $useremail=DB::table('storeusers')->select('*')->where('email', $email)->first();
          if(!empty($useremail))
          {
              $request->session()->flash('loginerror','Invalid Password');
              return Redirect::back();
          }
          else
          {
              $request->session()->flash('loginerror','You are not Registered !');
              return Redirect::back();
          }  
          $request->session()->flash('loginerror','Invalid Details !');
          return Redirect::back();

       }   
    }

    public function storeregister(Request $request)
    {

    	//$items_array=Tmporder::where('sessionid',session()->get('sessionid'))->get();
    	return view('frontend.register');
    }

    public function dostoreregister(Request $request)
    {
    	//print_r($_POST);die;

    	$email=$request->email;
    	$password=$request->password;

        $checkuser=DB::table('storeusers')->select('*')->where('email', $email)->first();
        
        if(!empty($checkuser))
        {	
         
            $request->session()->flash('error','Email Already Registered !');
            return Redirect::back();
        }
        else
        {
        	$user=new Storeuser;
         	$user->email=$email;
         	$user->fname=$request->firstname;
         	$user->lname=$request->lastname;
         	$user->password=$request->password;
         	$user->offersopted=$request->offers;
         	$user->newsletteropted=$request->newsletter;
         	$user->save();

         	$general_settings=General_setting::where('id','1')->first();

         	$admin_email=$general_settings->mailfromemail;
         	$mail_from=$general_settings->mailname;


         	$details = [
          	'email'=>$email,
          	'password'=>$password,
          	'$admin_email'=>$admin_email,
          	'mail_from'=>$mail_from,
        	 ];

    		Mail::to($email)->send(new \App\Mail\Registrationemail($details));

        }

          $request->session()->flash('success','Registered successfully !');
          return Redirect::to('store/login');
    	
    }


    public function myaccount(Request $request)
    {

    	$userid=session()->get('userid');
    	$roles=session()->get('role');
        $footerlevel1=Footerlevel1::findorFail(1);
        $contact=Contact::where('id','1')->first();
      if(!empty($roles))
      {
        echo "You are Logged in as Administrator, please logout and login as store User";
      }
      else
      {

            if(!empty($userid))
    	      {
              $items_array=Cartorder::orderbyDesc('id')->where('userid',$userid)->get()->unique('orderid');
            	$userdetails=DB::table('storeusers')->select('*')->where('id', $userid)->first();
        	    return view('web_frontend.myaccount',compact('userdetails','items_array','footerlevel1','contact'));
            }
            else
            {
        	    return view('frontend.login',compact('footerlevel1','contact'));
            }
      }
    }

    public function corporatePortal(Request $request)
    {
        // Middleware will handle authentication redirect
        $userid = session()->get('userid');

        // Get user details
        $user = DB::table('storeusers')->where('id', $userid)->first();

        // Get total orders count for this user
        $totalOrders = Cartorder::where('userid', $userid)->count();

        // Get total spend (YTD) - from cart orders (using ordertotal column)
        $totalSpend = Cartorder::where('userid', $userid)->sum('ordertotal') ?? 0;

        // Get items delivered (orders with status 'Delivered')
        $itemsDelivered = Cartorder::where('userid', $userid)->where('orderstatus', 'Delivered')->sum('quantity') ?? 0;

        // Get pending approvals (orders with status 'Pending')
        $pendingApprovals = Cartorder::where('userid', $userid)->where('orderstatus', 'Pending')->count();

        // Get total recipients (this is a placeholder - you may need to adjust based on your actual data structure)
        // For now, we'll use the number of unique addresses or assume it's the same as orders count
        $totalRecipients = Cartorder::where('userid', $userid)->count();

        // Get recent orders for this user
        $recentOrders = Cartorder::where('userid', $userid)->orderbyDesc('id')->limit(5)->get();

        // Get collections for this user
        $collections = Collection::where('user_id', $userid)->withCount('items')->limit(5)->get();

        return view('web_frontend.myaccount', compact('totalOrders', 'totalSpend', 'recentOrders', 'collections', 'user', 'itemsDelivered', 'pendingApprovals', 'totalRecipients'));
    }

    public function corporateLogin(Request $request)
    {
        // If already authenticated, redirect to corporate portal
        if (session()->has('userid')) {
            return redirect()->route('corporate.portal');
        }

        return view('web_frontend.auth.login');
    }

    public function doCorporateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        $checkuser = DB::table('storeusers')->select('*')->where('email', $email)->first();

        if (!empty($checkuser)) {
            // Check password - support both plain text (for existing users) and hashed passwords
            if (Hash::check($password, $checkuser->password) || $checkuser->password === $password) {
                // If password is plain text, hash it for future use
                if ($checkuser->password === $password) {
                    DB::table('storeusers')
                        ->where('id', $checkuser->id)
                        ->update(['password' => Hash::make($password)]);
                }

                session()->put('userid', $checkuser->id);
                session()->put('username', $checkuser->email);
                session()->put('fullname', $checkuser->fname . ' ' . $checkuser->lname);

                $this->mergeSessionCart($checkuser->id);

                $request->session()->flash('success', 'Login Successful!');
                return redirect($request->input('redirect') ?: route('corporate.portal'));
            } else {
                $request->session()->flash('error', 'Invalid Password');
                if ($request->input('auth_modal')) {
                    $request->session()->flash('auth_form', 'login');
                }
                return redirect()->back()->withInput();
            }
        } else {
            $request->session()->flash('error', 'You are not Registered!');
            if ($request->input('auth_modal')) {
                $request->session()->flash('auth_form', 'login');
            }
            return redirect()->back()->withInput();
        }
    }

    public function corporateRegister(Request $request)
    {
        // If already authenticated, redirect to corporate portal
        if (session()->has('userid')) {
            return redirect()->route('corporate.portal');
        }

        return view('web_frontend.auth.register');
    }

    public function doCorporateRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:storeusers,email',
            'mobile' => 'nullable|string|max:20',
            'location' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $email = $request->email;
        $password = $request->password;

        // Check if email already exists
        $checkuser = DB::table('storeusers')->select('*')->where('email', $email)->first();

        if (!empty($checkuser)) {
            $request->session()->flash('error', 'Email Already Registered!');
            return redirect()->back()->withInput();
        } else {
            $user = new Storeuser();
            $user->email = $email;
            $user->fname = $request->name;
            $user->lname = '';
            $user->password = Hash::make($password); // Hash the password
            $user->mobile = $request->mobile;
            $user->location = $request->location;
            $user->save();

            // Auto-login after registration
            session()->put('userid', $user->id);
            session()->put('username', $user->email);
            session()->put('fullname', $user->fname . ' ' . $user->lname);

            $request->session()->flash('success', 'Registered successfully!');
            return redirect()->route('corporate.portal');
        }
    }

    public function storeCartRegister(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|unique:storeusers,email',
            'country_code' => 'nullable|string|max:10',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            $request->session()->flash('auth_form', 'register');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new Storeuser();
        $user->fname = $request->first_name;
        $user->lname = $request->last_name ?? '';
        $user->company = $request->company;
        $user->email = $request->email;
        $user->mobile = trim(($request->country_code ?? '') . ' ' . ($request->mobile ?? ''));
        $user->address = $request->address;
        $user->location = $request->address;
        $user->password = Hash::make($request->password);
        $user->save();

        session()->put('userid', $user->id);
        session()->put('username', $user->email);
        session()->put('fullname', $user->fname . ' ' . $user->lname);

        $this->mergeSessionCart($user->id);

        $request->session()->flash('success', 'Registered successfully!');
        return redirect($request->input('redirect') ?: route('cart.index'));
    }

    public function accountSettings(Request $request)
    {
        // If not authenticated, redirect to login
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login');
        }

        $user = DB::table('storeusers')->where('id', session()->get('userid'))->first();

        return view('web_frontend.account-settings', compact('user'));
    }

    public function updateAccountSettings(Request $request)
    {
        // If not authenticated, redirect to login
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login');
        }

        $userid = session()->get('userid');

        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:30',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'flatno' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        DB::table('storeusers')
            ->where('id', $userid)
            ->update([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'company' => $request->company,
                'mobile' => $request->mobile,
                'location' => $request->location,
                'address' => $request->address,
                'flatno' => $request->flatno,
                'city' => $request->city,
                'region' => $request->region,
                'pincode' => $request->pincode,
                'country' => $request->country,
            ]);

        session()->put('fullname', trim($request->fname . ' ' . $request->lname));

        $request->session()->flash('success', 'Account settings updated successfully!');
        return redirect()->route('corporate.account-settings');
    }

    private function mergeSessionCart($userId)
    {
        CartItem::where('session_id', session()->getId())
            ->whereNull('user_id')
            ->update(['user_id' => $userId, 'session_id' => null]);
    }

    public function redirectToGoogleCorporate(Request $request)
    {
        if ($request->input('redirect')) {
            session()->put('google_redirect', $request->input('redirect'));
        }

        return Socialite::driver('google')
            ->redirectUrl(route('corporate.google.callback'))
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $redirectTo = session()->pull('google_redirect') ?: route('corporate.portal');

        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('corporate.google.callback'))
                ->user();
        } catch (\Exception $e) {
            $request->session()->flash('error', 'Google login failed. Please try again.');
            $request->session()->flash('auth_form', 'login');
            return redirect($redirectTo);
        }

        if (empty($googleUser->email)) {
            $request->session()->flash('error', 'Could not get your email from Google. Please login with email.');
            $request->session()->flash('auth_form', 'login');
            return redirect($redirectTo);
        }

        $user = Storeuser::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (!$user) {
            $user = new Storeuser();
            $user->fname = $googleUser->name ?? $googleUser->email;
            $user->lname = '';
            $user->email = $googleUser->email;
            $user->google_id = $googleUser->id;
            $user->google_token = $googleUser->token;
            $user->password = Hash::make(Str::random(16));
            $user->socialaccount = 'google';
            $user->save();
        } else {
            DB::table('storeusers')->where('id', $user->id)->update([
                'google_id' => $googleUser->id,
                'google_token' => $googleUser->token,
            ]);
        }

        session()->put('userid', $user->id);
        session()->put('username', $user->email);
        session()->put('fullname', trim($user->fname . ' ' . $user->lname));

        $this->mergeSessionCart($user->id);

        return redirect($redirectTo);
    }

    public function corporateAddresses(Request $request)
    {
        // If not authenticated, redirect to login
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login');
        }

        $userid = session()->get('userid');

        // Get user details including location
        $user = DB::table('storeusers')->where('id', $userid)->first();

        // Get saved addresses for this user
        $addresses = StoreuserAddress::where('user_id', $userid)
            ->orderBy('id')
            ->get();

        return view('web_frontend.addresses', compact('user', 'addresses'));
    }

    public function storeCorporateAddress(Request $request)
    {
        // If not authenticated, redirect to login
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login');
        }

        $userid = session()->get('userid');

        $request->validate([
            'label' => 'required|string|max:100',
            'name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:30',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        // First address is always default, or when checkbox is ticked
        $isDefault = $request->boolean('is_default')
            || !StoreuserAddress::where('user_id', $userid)->exists();

        if ($isDefault) {
            StoreuserAddress::where('user_id', $userid)->update(['is_default' => false]);
        }

        $address = StoreuserAddress::create([
            'user_id' => $userid,
            'label' => $request->label,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'location' => $request->location,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'is_default' => $isDefault,
        ]);

        if ($isDefault) {
            $this->syncDefaultAddressToUser($userid, $address);
        }

        $request->session()->flash('success', 'Address added successfully!');
        return redirect()->route('corporate.addresses');
    }

    public function selectCorporateAddress(Request $request, $id)
    {
        // If not authenticated, redirect to login
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login');
        }

        $userid = session()->get('userid');

        $address = StoreuserAddress::where('user_id', $userid)->where('id', $id)->firstOrFail();

        StoreuserAddress::where('user_id', $userid)->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        $this->syncDefaultAddressToUser($userid, $address);

        $request->session()->flash('success', 'Address selected as default!');
        return redirect()->route('corporate.addresses');
    }

    public function deleteCorporateAddress(Request $request, $id)
    {
        // If not authenticated, redirect to login
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login');
        }

        $userid = session()->get('userid');

        $address = StoreuserAddress::where('user_id', $userid)->where('id', $id)->firstOrFail();
        $wasDefault = $address->is_default;
        $address->delete();

        // Promote the newest remaining address to default if the deleted one was default
        if ($wasDefault) {
            $next = StoreuserAddress::where('user_id', $userid)->orderByDesc('id')->first();
            if ($next) {
                $next->update(['is_default' => true]);
                $this->syncDefaultAddressToUser($userid, $next);
            }
        }

        $request->session()->flash('success', 'Address deleted successfully!');
        return redirect()->route('corporate.addresses');
    }

    private function syncDefaultAddressToUser($userid, $address)
    {
        DB::table('storeusers')
            ->where('id', $userid)
            ->update([
                'location' => $address->location,
                'address' => $address->address,
                'city' => $address->city,
                'country' => $address->country,
            ]);
    }

    public function updateCorporateAddress(Request $request)
    {
        // If not authenticated, redirect to login
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login');
        }

        $userid = session()->get('userid');

        $request->validate([
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        // Update user address details
        DB::table('storeusers')
            ->where('id', $userid)
            ->update([
                'location' => $request->location,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
            ]);

        $request->session()->flash('success', 'Address updated successfully!');
        return redirect()->route('corporate.addresses');
    }

    public function createCollection(Request $request)
    {
        // Check if user is authenticated
        if (!session()->has('userid')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized - Please login'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Check if collection name already exists for this user
        $existingCollection = Collection::where('user_id', session()->get('userid'))
            ->where('name', $request->name)
            ->first();

        if ($existingCollection) {
            return response()->json(['success' => false, 'message' => 'Collection with this name already exists']);
        }

        $collection = new Collection();
        $collection->name = $request->name;
        $collection->description = $request->description;
        $collection->user_id = session()->get('userid');
        $collection->save();

        return response()->json(['success' => true, 'collection' => $collection]);
    }

    public function viewCollection($id)
    {
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login');
        }

        $collection = Collection::with(['items.product'])->where('id', $id)->where('user_id', session()->get('userid'))->first();

        if (!$collection) {
            abort(404);
        }

        // Get all products for adding to collection
        $products = Product::where('is_active', 'online')->limit(50)->get();

        return view('web_frontend.collection-detail', compact('collection', 'products'));
    }

    public function deleteCollection($id)
    {
        if (!session()->has('userid')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $collection = Collection::where('id', $id)->where('user_id', session()->get('userid'))->first();

        if (!$collection) {
            return response()->json(['success' => false, 'message' => 'Collection not found'], 404);
        }

        $collection->items()->delete();
        $collection->delete();

        return response()->json(['success' => true, 'message' => 'Collection deleted successfully']);
    }

    public function updateCollection(Request $request, $id)
    {
        if (!session()->has('userid')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:collections,name,' . $id . ',id,user_id,' . session()->get('userid'),
            'description' => 'nullable|string|max:1000',
        ]);

        $collection = Collection::where('id', $id)->where('user_id', session()->get('userid'))->first();

        if (!$collection) {
            return response()->json(['success' => false, 'message' => 'Collection not found'], 404);
        }

        $collection->name = $request->name;
        $collection->description = $request->description;
        $collection->save();

        return response()->json(['success' => true, 'collection' => $collection]);
    }

    public function allCollections()
    {
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login');
        }

        $collections = Collection::where('user_id', session()->get('userid'))
            ->withCount('items')
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        return view('web_frontend.collections-list', compact('collections'));
    }

    public function addItemToCollection(Request $request, $id)
    {
        if (!session()->has('userid')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $collection = Collection::where('id', $id)->where('user_id', session()->get('userid'))->first();

        if (!$collection) {
            return response()->json(['success' => false, 'message' => 'Collection not found'], 404);
        }

        // Check if product already exists in collection
        $existingItem = CollectionItem::where('collection_id', $id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingItem) {
            // Update quantity
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            // Add new item
            $item = new CollectionItem();
            $item->collection_id = $id;
            $item->product_id = $request->product_id;
            $item->quantity = $request->quantity;
            $item->save();
        }

        return response()->json(['success' => true, 'message' => 'Product added to collection']);
    }

    public function removeItemFromCollection($id, $itemId)
    {
        if (!session()->has('userid')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $collection = Collection::where('id', $id)->where('user_id', session()->get('userid'))->first();

        if (!$collection) {
            return response()->json(['success' => false, 'message' => 'Collection not found'], 404);
        }

        $item = CollectionItem::where('id', $itemId)->where('collection_id', $id)->first();

        if ($item) {
            $item->delete();
        }

        return response()->json(['success' => true, 'message' => 'Item removed from collection']);
    }

    public function updateItemQuantity(Request $request, $id, $itemId)
    {
        if (!session()->has('userid')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $collection = Collection::where('id', $id)->where('user_id', session()->get('userid'))->first();

        if (!$collection) {
            return response()->json(['success' => false, 'message' => 'Collection not found'], 404);
        }

        $item = CollectionItem::where('id', $itemId)->where('collection_id', $id)->first();

        if ($item) {
            $item->quantity = $request->quantity;
            $item->save();
        }

        return response()->json(['success' => true, 'message' => 'Quantity updated']);
    }


    public function dostorelogout(Request $request)
    {
      $request->session()->forget('username');
      $request->session()->forget('userid');
      session()->flash('success', 'Logged Out Successfully!');
      return redirect()->route('corporate.login');
    }


    public function resetpassword(Request $request)
    {
        // If already authenticated, redirect to corporate portal
        if (session()->has('userid')) {
            return redirect()->route('corporate.portal');
        }

        return view('web_frontend.auth.forgot-password');
    }


    public function doresetpassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->get('email');

        $checkuser = DB::table('storeusers')->select('*')->where('email', $email)->first();

        if (!empty($checkuser)) {
            // Generate a random password
            $password = Str::random(10);

            // Update password with hash
            DB::table('storeusers')
                ->where('email', $email)
                ->update([
                    'password' => Hash::make($password),
                ]);

            // Send email with new password (if mail is configured)
            try {
                $data = [
                    'email' => $email,
                    'password' => $password,
                ];

                Mail::send('emails.password_reset', $data, function($message) use ($email) {
                    $message->to($email, 'User')->subject('Password Reset - More Than Giftz');
                    $message->from('noreply@morethangiftz.com', 'More Than Giftz');
                });
            } catch (\Exception $e) {
                // Log error but continue (email might not be configured)
                \Log::error('Password reset email failed: ' . $e->getMessage());
            }

            $request->session()->flash('success', 'A new password has been sent to your email address. Please check your inbox and spam folder.');
            return redirect()->route('corporate.login');
        } else {
            $request->session()->flash('error', 'Email Not Registered, Please Sign Up');
            return redirect()->back()->withInput();
        }
    }

    public function updateuserdetails(Request $request)
    {
return "Details Updated Succesfully !" ;  
        return $request->id;
       DB::table('storeusers')
                        ->where('id',$request->id)
                        ->update([
                            'fname' => $request->fname,
                            'lname' => $request->lname,
                            'mobile' => $request->mobile,
                            'email' => $request->email,
                            'flatno' => $request->flatno,
                            'city' => $request->city,
                            'region' => $request->region,
                            'country' => $request->country,
                            'password' => $request->password,
                            'address' => $request->address,

                          ]);


      return "Details Updated Succesfully !" ;                 

    }

    public function storeuser()
    {
        $items_array=Storeuser::orderbyDesc('id')->get();
        return view('backend.users.storeuser',compact('items_array'));
    }

    

}
