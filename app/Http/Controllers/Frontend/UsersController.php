<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Storeuser;
use App\Models\Cartorder;
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
      $checkuser=DB::table('storeusers')->select('*')->where('email', $email)->first();

      if(!empty($checkuser) && !empty($password) && $this->passwordMatches($checkuser, $password))
      {
        $request->session()->regenerate();
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
         	$user->password=Hash::make($request->password);
         	$user->offersopted=$request->offers;
         	$user->newsletteropted=$request->newsletter;
         	$user->save();

    		$this->sendWelcome($email, $request->firstname);

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

        // Cards and Recent Orders: one entry per order (not per product line), read from the order workflow
        $orders = \App\Models\Order::where('storeuser_id', $userid)->with('stepStates', 'items')->orderByDesc('id')->get();
        $stats = $this->dashboardStats($orders);
        $recentOrders = $orders->take(5);
        $cartCount = CartItem::where('user_id', $userid)->count(); // shown on the "View Quote Cart" quick action

        // Get collections for this user
        $collections = Collection::where('user_id', $userid)->withCount('items')->limit(5)->get();

        return view('web_frontend.myaccount', compact('stats', 'recentOrders', 'collections', 'user', 'cartCount'));
    }

    /**
     * Figures for the five dashboard cards. An order is delivered when its
     * Delivery step (8) is done and completed when Project Completed (9) is
     * done; spend counts orders confirmed by staff (step 1 done) this year,
     * at the same order value the order pages show.
     */
    private function dashboardStats($orders)
    {
        $isDone = function ($order, $step) {
            return optional($order->state($step))->state === 'done';
        };
        $approvalSteps = array_keys(array_filter(config('order_steps'), function ($step) {
            return $step['type'] === 'approval';
        }));
        $year = now()->year;

        $waiting = $orders->filter(function ($order) use ($approvalSteps) {
            return in_array((int) $order->current_step, $approvalSteps, true) && $order->step_state === 'waiting_customer';
        });
        $delivered = $orders->filter(function ($order) use ($isDone) {
            return $isDone($order, 8);
        });
        $completed = $orders->filter(function ($order) use ($isDone) {
            return $isDone($order, 9);
        });
        $thisYear = $orders->filter(function ($order) use ($year) {
            return $order->created_at && $order->created_at->year === $year;
        });
        $confirmed = $thisYear->filter(function ($order) use ($isDone) {
            return $isDone($order, 1);
        });

        return [
            'orders' => $orders->count(),
            'ordersThisYear' => $thisYear->count(),
            'waiting' => $waiting->count(),
            'waitingOrder' => optional($waiting->last())->orderid, // oldest first
            'itemsDelivered' => (int) $delivered->sum(function ($order) {
                return $order->items->sum('quantity');
            }),
            'ordersDelivered' => $delivered->count(),
            'active' => $orders->count() - $completed->count(),
            'completed' => $completed->count(),
            'spend' => (float) $confirmed->sum('total'),
            'confirmedOrders' => $confirmed->count(),
            'awaitingConfirmation' => (float) $thisYear->diff($confirmed)->sum('total'),
        ];
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
            if ($this->passwordMatches($checkuser, $password)) {
                $guestSessionId = session()->getId();
                $request->session()->regenerate();

                session()->put('userid', $checkuser->id);
                session()->put('username', $checkuser->email);
                session()->put('fullname', $checkuser->fname . ' ' . $checkuser->lname);

                $this->mergeSessionCart($checkuser->id, $guestSessionId);

                $request->session()->flash('success', 'Login Successful!');
                return redirect($this->safeRedirect($request->input('redirect'), route('corporate.portal')));
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

    /**
     * Check a store user's password. Old accounts may still have a plain text
     * password; those are upgraded to a hash the first time they log in.
     */
    private function passwordMatches($user, $password)
    {
        if (empty($user->password)) {
            return false;
        }

        if (strpos($user->password, '$2y$') === 0) {
            return Hash::check($password, $user->password);
        }

        if (hash_equals((string) $user->password, (string) $password)) {
            DB::table('storeusers')
                ->where('id', $user->id)
                ->update(['password' => Hash::make($password)]);
            return true;
        }

        return false;
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
            $this->sendWelcome($user->email, $user->fname);

            // Auto-login after registration, with a new session id
            $request->session()->regenerate();
            session()->put('userid', $user->id);
            session()->put('username', $user->email);
            session()->put('fullname', $user->fname . ' ' . $user->lname);

            $request->session()->flash('success', 'Registered successfully!');
            $request->session()->flash('account_created', ['name' => $user->fname]); // welcome pop-up
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
        $this->sendWelcome($user->email, $user->fname);

        $request->session()->regenerate();
        session()->put('userid', $user->id);
        session()->put('username', $user->email);
        session()->put('fullname', $user->fname . ' ' . $user->lname);

        $this->mergeSessionCart($user->id);

        $request->session()->flash('success', 'Registered successfully!');
        $request->session()->flash('account_created', ['name' => $user->fname]); // welcome pop-up
        return redirect($this->safeRedirect($request->input('redirect'), route('cart.index')));
    }

    /** Welcome email after signing up; a mail problem never stops the sign-up. */
    private function sendWelcome($email, $name)
    {
        try {
            Mail::to($email)->send(new \App\Mail\Registrationemail(['name' => trim((string) $name) ?: null]));
        } catch (\Throwable $e) {
            \Log::error('Welcome email failed for ' . $email . ': ' . $e->getMessage());
        }
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

    private function mergeSessionCart($userId, $sessionId = null)
    {
        CartItem::where('session_id', $sessionId ?: session()->getId())
            ->whereNull('user_id')
            ->update(['user_id' => $userId, 'session_id' => null]);
    }

    /**
     * Only follow redirects that stay on this site.
     */
    private function safeRedirect($target, $fallback)
    {
        if (is_string($target) && $target !== '') {
            if (strpos($target, '/') === 0 && strpos($target, '//') !== 0 && strpos($target, '/\\') !== 0) {
                return $target;
            }
            $home = url('/');
            if ($target === $home || strpos($target, $home . '/') === 0) {
                return $target;
            }
        }

        return $fallback;
    }

    public function redirectToGoogleCorporate(Request $request)
    {
        // Without keys Google shows a raw "missing client_id" error page
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            \Log::warning('Google login clicked but Google_client_id / Google_client_secret are not set in .env');
            $request->session()->flash('error', 'Google sign-in is not available right now. Please login with your email and password.');
            $request->session()->flash('auth_form', 'login');
            return redirect()->back();
        }

        if ($request->input('redirect')) {
            session()->put('google_redirect', $this->safeRedirect($request->input('redirect'), route('corporate.portal')));
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

        $isNewAccount = !$user;
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

        $guestSessionId = session()->getId();
        $request->session()->regenerate();

        session()->put('userid', $user->id);
        session()->put('username', $user->email);
        session()->put('fullname', trim($user->fname . ' ' . $user->lname));

        $this->mergeSessionCart($user->id, $guestSessionId);

        if ($isNewAccount) {
            $request->session()->flash('account_created', ['name' => strtok((string) $user->fname, ' ')]); // welcome pop-up
        }
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

        $name = trim($request->name);

        // Check if collection name already exists for this user
        $existingCollection = Collection::where('user_id', session()->get('userid'))
            ->where('name', $name)
            ->first();

        if ($existingCollection) {
            return response()->json(['success' => false, 'message' => 'You already have a collection called "' . $name . '". Please choose another name.']);
        }

        $collection = new Collection();
        $collection->name = $name;
        $collection->description = $request->description;
        $collection->user_id = session()->get('userid');
        $collection->save();

        return response()->json(['success' => true, 'collection' => $collection, 'url' => route('collections.view', $collection->id)]);
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

        // One row per saved product, priced like the cart: never below the MOQ, tier price for that quantity
        $rows = $collection->items->map(function ($item) {
            $product = $item->product;
            if (!$product) {
                return (object) ['item' => $item, 'product' => null];
            }
            $moq = $product->minOrderQuantity();
            $quoted = max((int) $item->quantity, $moq);
            $unit = $product->priceForQuantity($quoted);
            return (object) [
                'item' => $item, 'product' => $product, 'image' => $product->imageUrl(), 'moq' => $moq,
                'quoted' => $quoted, 'unit' => $unit, 'total' => $unit * $quoted,
                'available' => $product->is_active === 'online',
            ];
        });
        $totals = [
            'products' => $rows->count(),
            'pieces' => $rows->sum(function ($row) { return $row->product ? $row->quoted : 0; }),
            'value' => $rows->sum(function ($row) { return $row->product ? $row->total : 0; }),
        ];

        return view('web_frontend.collection-detail', compact('collection', 'rows', 'totals'));
    }

    /**
     * Product picker on the collection page: searches the whole online
     * catalogue (name, model number, SKU), 20 at a time.
     */
    public function collectionProductSearch(Request $request)
    {
        if (!session()->has('userid')) {
            return response()->json(['success' => false, 'message' => 'Please log in again.'], 401);
        }

        $search = trim((string) $request->query('q', ''));
        $page = Product::where('is_active', 'online')
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)->orWhere('modelno', 'like', $like)->orWhere('sku', 'like', $like);
                });
            })
            ->orderBy('name')
            ->paginate(20);

        $inCollection = [];
        if ($request->filled('collection')) {
            $collection = Collection::where('id', $request->query('collection'))->where('user_id', session()->get('userid'))->first();
            $inCollection = $collection ? $collection->items()->pluck('quantity', 'product_id')->all() : [];
        }

        return response()->json([
            'success' => true,
            'total' => $page->total(),
            'nextPage' => $page->hasMorePages() ? $page->currentPage() + 1 : null,
            'products' => collect($page->items())->map(function ($product) use ($inCollection) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'modelno' => $product->modelno,
                    'image' => $product->imageUrl(),
                    'price' => round($product->priceForQuantity($product->minOrderQuantity()), 2),
                    'moq' => $product->minOrderQuantity(),
                    'url' => route('web.product', $product->slug),
                    'inCollection' => (int) ($inCollection[$product->id] ?? 0),
                ];
            })->values(),
        ]);
    }

    /**
     * The customer's collections for the Save to Collection pop-up, saying which already hold this product.
     */
    public function collectionsForProduct(Request $request)
    {
        if (!session()->has('userid')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate(['product_id' => 'required|integer']);

        $collections = Collection::where('user_id', session()->get('userid'))->withCount('items')->orderByDesc('updated_at')->get();
        $saved = CollectionItem::whereIn('collection_id', $collections->pluck('id'))->where('product_id', $request->product_id)->pluck('collection_id')->all();

        return response()->json(['success' => true, 'collections' => $collections->map(function ($collection) use ($saved) {
            return [
                'id' => $collection->id,
                'name' => $collection->name,
                'count' => (int) $collection->items_count,
                'saved' => in_array($collection->id, $saved),
                'url' => route('collections.view', $collection->id),
            ];
        })->values()]);
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

    public function allCollections(Request $request)
    {
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login');
        }

        $userid = session()->get('userid');
        $search = trim((string) $request->query('search', ''));
        $totalCollections = Collection::where('user_id', $userid)->count();

        $collections = Collection::where('user_id', $userid)
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)->orWhere('description', 'like', $like);
                });
            })
            ->with('items.product')
            ->withCount('items')
            ->orderBy('updated_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        // Card summary: up to 4 product images, pieces and an estimate priced like the cart (MOQ + tiers)
        foreach ($collections as $collection) {
            $items = $collection->items->filter(function ($item) {
                return $item->product;
            });
            $collection->preview_images = $items->map(function ($item) {
                return $item->product->imageUrl();
            })->filter()->take(4)->values();
            $collection->total_pieces = $items->sum('quantity');
            $collection->estimated_value = $items->sum(function ($item) {
                $quantity = max((int) $item->quantity, $item->product->minOrderQuantity());
                return $quantity * $item->product->priceForQuantity($quantity);
            });
        }

        return view('web_frontend.collections-list', compact('collections', 'search', 'totalCollections'));
    }

    /**
     * Put every product of a saved collection into the quote cart, using the
     * cart's own rules (MOQ, tier price). Offline or deleted products are skipped.
     */
    public function collectionToCart($id)
    {
        if (!session()->has('userid')) {
            return response()->json(['success' => false, 'message' => 'Please log in again.'], 401);
        }

        $collection = Collection::with('items.product')->where('id', $id)->where('user_id', session()->get('userid'))->first();
        if (!$collection) {
            return response()->json(['success' => false, 'message' => 'Collection not found'], 404);
        }

        $cart = app(CartController::class);
        $added = 0;
        $skipped = [];
        foreach ($collection->items as $item) {
            if (!$item->product || $item->product->is_active !== 'online') {
                $skipped[] = optional($item->product)->name ?: 'A product that no longer exists';
                continue;
            }
            $cart->addProductToCart($item->product, $item->quantity);
            $added++;
        }

        if (!$added) {
            return response()->json(['success' => false, 'message' => $collection->items->count()
                ? 'None of the products in this collection are available any more.'
                : 'This collection has no products yet.', 'skipped' => $skipped], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $added . ' product' . ($added == 1 ? '' : 's') . ' added to your quote cart.',
            'skipped' => $skipped,
            'count' => CartItem::where('user_id', session()->get('userid'))->count(), // same number the header cart badge shows
            'cartUrl' => route('cart.index'),
        ]);
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

        if (Product::where('id', $request->product_id)->value('is_active') !== 'online') {
            return response()->json(['success' => false, 'message' => 'This product is not available any more.'], 422);
        }

        // Check if product already exists in collection
        $existingItem = CollectionItem::where('collection_id', $id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingItem) {
            // Update quantity
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
            $item = $existingItem;
        } else {
            // Add new item
            $item = new CollectionItem();
            $item->collection_id = $id;
            $item->product_id = $request->product_id;
            $item->quantity = $request->quantity;
            $item->save();
        }

        return response()->json(['success' => true, 'message' => 'Product added to collection', 'quantity' => (int) $item->quantity]);
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
      // Every customer key goes; the admin's keys (if an admin is logged in on this browser) stay
      $request->session()->forget(['username', 'userid', 'fullname', 'member_id', 'member_account', 'member_company', 'member_role']);
      $request->session()->regenerate();
      $request->session()->regenerateToken();
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

        // The password only changes when the owner opens the emailed link,
        // so a stranger typing someone's email can't lock them out.
        if (!empty($checkuser)) {
            $token = Str::random(64);
            DB::table('password_resets')->where('email', $checkuser->email)->delete();
            DB::table('password_resets')->insert([
                'email' => $checkuser->email,
                'token' => hash('sha256', $token),
                'created_at' => now(),
            ]);

            try {
                $data = [
                    'resetUrl' => route('corporate.password.reset', $token) . '?email=' . urlencode($checkuser->email),
                    'minutes' => self::RESET_LINK_MINUTES,
                ];
                Mail::send('emails.password_reset_link', $data, function($message) use ($checkuser) {
                    $message->to($checkuser->email)->subject('Reset your password - More Than Giftz');
                });
            } catch (\Exception $e) {
                \Log::error('Password reset email failed: ' . $e->getMessage());
            }
        }

        // Same message either way, so the form doesn't reveal who is registered
        $request->session()->flash('success', 'If an account exists for that email, we have sent a link to reset your password. Please check your inbox and spam folder.');
        return redirect()->route('corporate.login');
    }

    const RESET_LINK_MINUTES = 60;

    private function validResetToken($email, $token)
    {
        $row = DB::table('password_resets')->where('email', $email)->first();
        return $row
            && hash_equals($row->token, hash('sha256', (string) $token))
            && \Carbon\Carbon::parse($row->created_at)->addMinutes(self::RESET_LINK_MINUTES)->isFuture();
    }

    public function showResetPasswordForm(Request $request, $token)
    {
        $email = (string) $request->query('email');
        if (!$this->validResetToken($email, $token)) {
            $request->session()->flash('error', 'This password reset link is invalid or has expired. Please request a new one.');
            return redirect()->route('corporate.forgot.password');
        }

        return view('web_frontend.auth.reset-password', compact('token', 'email'));
    }

    public function doResetPasswordWithToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!$this->validResetToken($request->email, $request->token)) {
            $request->session()->flash('error', 'This password reset link is invalid or has expired. Please request a new one.');
            return redirect()->route('corporate.forgot.password');
        }

        DB::table('storeusers')->where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_resets')->where('email', $request->email)->delete();

        $request->session()->flash('success', 'Your password has been changed. You can log in now.');
        return redirect()->route('corporate.login');
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
