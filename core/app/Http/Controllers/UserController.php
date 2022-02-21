<?php

namespace App\Http\Controllers;

use App\GeneralSetting;
use App\IcoPlan;
use App\Lib\GoogleAuthenticator;
use App\Transaction;
use App\User;
use App\Deposit;
use App\WithdrawMethod;
use App\Withdrawal;
use App\CoinHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Image;
use Carbon\Carbon;
use App\Auction;

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function home()
    {
        $page_title = 'Dashboard';
        $user = auth()->user();
        $transaction = Transaction::where('user_id', $user->id)->count();
        $coinHistory = CoinHistory::where('user_id', $user->id)->count();
        $withdraw = Withdrawal::where('user_id', $user->id)->count();
        $deposit = Deposit::where('user_id', $user->id)->count();

        $currentRate = IcoPlan::where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())->first();

       $logs = Transaction::where('user_id', $user->id)->latest()->take(10)->get();

        return view($this->activeTemplate . 'user.dashboard', compact('page_title', 'transaction', 'coinHistory', 'withdraw', 'deposit', 'currentRate', 'user', 'logs'));
    }

    public function profile()
    {
        $data['page_title'] = "Profile Setting";
        $data['user'] = Auth::user();
        return view($this->activeTemplate. 'user.profile-setting', $data);
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'address' => "sometimes|required|max:80",
            'state' => 'sometimes|required|max:80',
            'zip' => 'sometimes|required|max:40',
            'city' => 'sometimes|required|max:50',
            'image' => 'mimes:png,jpg,jpeg'
        ]
    );

        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $request->country,
            'city' => $request->city,
        ];

        $user = Auth::user();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $user->username . '.jpg';
            $location = 'assets/images/user/profile/' . $filename;
            $in['image'] = $filename;

            $path = './assets/images/user/profile/';
            $link = $path . $user->image;
            if (file_exists($link)) {
                @unlink($link);
            }
            $size = imagePath()['profile']['user']['size'];
            $image = Image::make($image);
            $size = explode('x', strtolower($size));
            $image->resize($size[0], $size[1]);
            $image->save($location);
        }
        $user->fill($in)->update();
        $notify[] = ['success', 'Profile Updated successfully.'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $data['page_title'] = "Change Password";
        return view($this->activeTemplate . 'user.password', $data);
    }

    public function submitPassword(Request $request)
    {

        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|min:5|confirmed'
        ]);
        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', 'Password Changes successfully.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'Current password not match.'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /*
     * Deposit History
     */
    public function depositHistory()
    {
        $page_title = 'Deposit History';
        $empty_message = 'No history found.';
        $logs = auth()->user()->deposits()->with(['gateway'])->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.deposit_history', compact('page_title', 'empty_message', 'logs'));
    }

    /*
     * Withdraw Operation
     */

    public function withdrawMoney()
    {
        $general = GeneralSetting::first();

        if(!$general->withdraw_permission){
            $notify[] = ['error', 'Sorry withdraw there is no permission to withdraw'];
            return redirect()->route('user.home')->withNotify($notify);
        }

        $data['withdrawMethod'] = WithdrawMethod::whereStatus(1)->get();
        $data['page_title'] = "Withdraw Money";
        return view(activeTemplate() . 'user.withdraw.methods', $data);
    }

    public function withdrawStore(Request $request)
    {
        $general = GeneralSetting::first();

        if(!$general->withdraw_permission){
            $notify[] = ['error', 'Sorry withdraw there is no permission to withdraw'];
            return redirect()->route('user.home')->withNotify($notify);
        }

        $this->validate($request, [
            'method_code' => 'required',
            'amount' => 'required|numeric'
        ]);
        $method = WithdrawMethod::where('id', $request->method_code)->where('status', 1)->firstOrFail();
        $user = auth()->user();
        if ($request->amount < $method->min_limit) {
            $notify[] = ['error', 'Your Requested Amount is Smaller Than Minimum Amount.'];
            return back()->withNotify($notify);
        }
        if ($request->amount > $method->max_limit) {
            $notify[] = ['error', 'Your Requested Amount is Larger Than Maximum Amount.'];
            return back()->withNotify($notify);
        }

        if ($request->amount > $user->balance) {
            $notify[] = ['error', 'Your do not have Sufficient Balance For Withdraw.'];
            return back()->withNotify($notify);
        }


        $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = getAmount($afterCharge * $method->rate);

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id; // wallet method ID
        $withdraw->user_id = $user->id;
        $withdraw->amount = getAmount($request->amount);
        $withdraw->currency = $method->currency;
        $withdraw->rate = $method->rate;
        $withdraw->charge = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx = getTrx();
        $withdraw->save();
        session()->put('wtrx', $withdraw->trx);
        return redirect()->route('user.withdraw.preview');
    }

    public function withdrawPreview()
    {
        $general = GeneralSetting::first();

        if(!$general->withdraw_permission){
            $notify[] = ['error', 'Sorry withdraw there is no permission to withdraw'];
            return redirect()->route('user.home')->withNotify($notify);
        }

        $data['withdraw'] = Withdrawal::with('method','user')->where('trx', session()->get('wtrx'))->where('status', 0)->latest()->firstOrFail();
        $data['page_title'] = "Withdraw Preview";
        return view($this->activeTemplate . 'user.withdraw.preview', $data);
    }


    public function withdrawSubmit(Request $request)
    {
        $general = GeneralSetting::first();

        if(!$general->withdraw_permission){
            $notify[] = ['error', 'Sorry withdraw there is no permission to withdraw'];
            return redirect()->route('user.home')->withNotify($notify);
        }

        $general = GeneralSetting::first();
        $withdraw = Withdrawal::with('method','user')->where('trx', session()->get('wtrx'))->where('status', 0)->latest()->firstOrFail();

        $rules = [];
        $inputField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($withdraw->method->user_data as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], 'mimes:jpeg,jpg,png');
                    array_push($rules[$key], 'max:2048');
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }
        $this->validate($request, $rules);
        $user = auth()->user();

        if (getAmount($withdraw->amount) > $user->balance) {
            $notify[] = ['error', 'Your Request Amount is Larger Then Your Current Balance.'];
            return back()->withNotify($notify);
        }

        $directory = date("Y")."/".date("m")."/".date("d");
        $path = imagePath()['verify']['withdraw']['path'].'/'.$directory;
        $collection = collect($request);
        $reqField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($collection as $k => $v) {
                foreach ($withdraw->method->user_data as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory.'/'.uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $request[$inKey]];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $withdraw['withdraw_information'] = $reqField;
        } else {
            $withdraw['withdraw_information'] = null;
        }


        $withdraw->status = 2;
        $withdraw->save();
        $user->balance  -=  $withdraw->amount;
        $user->save();



        $transaction = new Transaction();
        $transaction->user_id = $withdraw->user_id;
        $transaction->amount = getAmount($withdraw->amount);
        $transaction->post_balance = getAmount($user->balance);
        $transaction->charge = getAmount($withdraw->charge);
        $transaction->trx_type = '-';
        $transaction->details = getAmount($withdraw->final_amount) . ' ' . $withdraw->currency . ' Withdraw Via ' . $withdraw->method->name;
        $transaction->trx =  $withdraw->trx;
        $transaction->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => getAmount($withdraw->final_amount),
            'amount' => getAmount($withdraw->amount),
            'charge' => getAmount($withdraw->charge),
            'currency' => $general->cur_text,
            'rate' => getAmount($withdraw->rate),
            'trx' => $withdraw->trx,
            'post_balance' => getAmount($user->balance),
            'delay' => $withdraw->method->delay
        ]);

        $notify[] = ['success', 'Withdraw Request Successfully Send'];
        return redirect()->route('user.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog()
    {
        $general = GeneralSetting::first();

        if(!$general->withdraw_permission){
            $notify[] = ['error', 'Sorry withdraw there is no permission to withdraw'];
            return redirect()->route('user.home')->withNotify($notify);
        }

        $data['page_title'] = "Withdraw Log";
        $data['withdraws'] = Withdrawal::where('user_id', Auth::id())->where('status', '!=', 0)->with('method')->latest()->paginate(getPaginate());
        $data['empty_message'] = "No Data Found!";
        return view($this->activeTemplate.'user.withdraw.log', $data);
    }



    public function show2faForm()
    {
        $gnl = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $gnl->sitename, $secret);
        $prevcode = $user->tsc;
        $prevqr = $ga->getQRCodeGoogleUrl($user->username . '@' . $gnl->sitename, $prevcode);
        $page_title = 'Two Factor';
        return view($this->activeTemplate.'user.twofactor', compact('page_title', 'secret', 'qrCodeUrl', 'prevcode', 'prevqr'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);

        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        if ($oneCode === $request->code) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->tv = 1;
            $user->save();


            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);


            $notify[] = ['success', 'Google Authenticator Enabled Successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $ga = new GoogleAuthenticator();

        $secret = $user->tsc;
        $oneCode = $ga->getCode($secret);
        $userCode = $request->code;

        if ($oneCode == $userCode) {

            $user->tsc = null;
            $user->ts = 0;
            $user->tv = 1;
            $user->save();


            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);


            $notify[] = ['success', 'Two Factor Authenticator Disable Successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }


    public function transactionLog(){
        $logs = Transaction::where('user_id', Auth::user()->id)->latest()->paginate(getPaginate());
        $page_title = 'Transaction Logs';
        return view($this->activeTemplate.'user.transaction', compact('page_title', 'logs'));
    }

    public function buyCoin(){

        $phase = IcoPlan::whereDate('start_date', '<=', Carbon::now())
                        ->whereDate('end_date', '>=', Carbon::now())
                        ->first();

        if(!$phase){
            $notify[] = ['error', 'Sorry Unavailable Phase'];
            return redirect()->route('user.home')->withNotify($notify);
        }

        if($phase->coin_token <= 0){
            $notify[] = ['error', 'Sorry We have not coin to sale right now. Thak you'];
            return redirect()->route('user.home')->withNotify($notify);
        }

        $page_title = 'Buy Coin';
        return view($this->activeTemplate.'user.coin.buy', compact('page_title', 'phase'));
    }

    public function buyCoinConfirm(Request $request){

        $request->validate([
            'coin_quantity' => 'required|integer|gt:0',
        ]);

        $quantity = $request->coin_quantity;

        $coinExists = IcoPlan::whereDate('start_date', '<=', Carbon::now())
                             ->whereDate('end_date', '>=', Carbon::now())
                             ->first();

        if(!$coinExists){
            $notify[] = ['error', 'Sorry Unavailable Service'];
            return redirect()->route('user.home')->withNotify($notify);
        }

        if($coinExists->coin_token < $request->coin_quantity){
            $notify[] = ['error', 'Sorry We have not sufficient coin right now'];
            return back()->withNotify($notify);
        }

        $requiredPrice = $request->coin_quantity * $coinExists->price;

        $currentUser = Auth::user();

        if($currentUser->balance < $requiredPrice){
            $notify[] = ['error', 'Sorry You have not sufficient balance to buy coin'];
            return redirect()->route('user.deposit')->withNotify($notify);
        }

        $general = GeneralSetting::first();

        $currentUser->balance -= $requiredPrice;
        $currentUser->coin_balance += $quantity;
        $currentUser->save();

        $coinExists->coin_token -= $quantity;
        $coinExists->sold += $quantity;
        $coinExists->save();

        $transaction = new Transaction();
        $transaction->user_id = $currentUser->id;
        $transaction->amount = $requiredPrice;
        $transaction->charge = 0;
        $transaction->post_balance = $currentUser->balance;
        $transaction->trx_type = '-';
        $transaction->trx = getTrx();
        $transaction->details = 'Purchased '.$general->coin_name;
        $transaction->save();

        $history = new CoinHistory();
        $history->user_id = $currentUser->id;
        $history->type =  '+';
        $history->stage =  $coinExists->stage;
        $history->coin_rate =  $coinExists->price;
        $history->coin_quantity =  $quantity;
        $history->amount = $requiredPrice;
        $history->coin_post_balance = $currentUser->coin_balance;
        $history->status = 1;
        $history->details = 'Purchased '.$general->coin_name;
        $history->save();

        notify($currentUser, 'BUY_COMPLETE', [
            'amount' => $requiredPrice,
            'trx_id' => $transaction->trx,
            'stage' => $history->stage,
            'rate' => $history->coin_rate,
            'coin_quantity' => $history->coin_quantity,
            'coin_post_balance' => $currentUser->coin_balance,
            'currency' => $general->cur_text,
            'coin_text' => $general->coin_name,
        ]);

        $notify[] = ['success', 'Purchased '.$general->coin_name];
        return redirect()->route('user.coin.buy.log')->withNotify($notify);

    }

    public function buyCoinLog(){
        $page_title = 'Buy Coin Logs';
        $logs = CoinHistory::where('user_id', Auth::user()->id)->latest()->paginate();
        return view($this->activeTemplate.'user.coin.log', compact('page_title', 'logs'));
    }

    public function createAuction(){

        $phase = IcoPlan::whereDate('start_date', '<=', Carbon::now())
                        ->whereDate('end_date', '>=', Carbon::now())
                        ->first();

        if(!$phase){
            $notify[] = ['error', 'Sorry there is no phase'];
            return back()->withNotify($notify);
        }

        $page_title = 'Create your auction';
        return view($this->activeTemplate.'user.auction.create', compact('page_title', 'phase'));
    }

    public function createAuctionConfirm(Request $request){

        $request->validate([
            'coin_quantity' => 'required|integer|gt:0',
            'parcent' => 'required|numeric|min:-100',
        ]);

        $currentUser = Auth::user();
        $userBanance = $currentUser->coin_balance;

        if($userBanance < $request->coin_quantity){
            $notify[] = ['error', 'Sorry you have not sufficient coin to create Auction'];
            return back()->withNotify($notify);
        }

        $phase = IcoPlan::whereDate('start_date', '<=', Carbon::now())
                        ->whereDate('end_date', '>=', Carbon::now())
                        ->first();

        if(!$phase){
            $notify[] = ['error', 'Sorry there is no phase'];
            return back()->withNotify($notify);
        }

        $getAmountOfParcent = ($request->parcent * $phase->price) / 100;
        $finalPrice = $phase->price + $getAmountOfParcent;
        $amount = $request->coin_quantity * $finalPrice;

        $auction = new Auction();
        $auction->quantity = $request->coin_quantity;
        $auction->amount = $amount;
        $auction->expected_profit = $request->parcent;
        $auction->auction_owner = $currentUser->id;
        $auction->status = 1;
        $auction->save();

        $currentUser->coin_balance -= $request->coin_quantity;
        $currentUser->save();

        $history = new CoinHistory();
        $history->user_id = $currentUser->id;
        $history->type = '-';
        $history->stage = $phase->stage;
        $history->coin_rate = $phase->price;
        $history->coin_quantity = $request->coin_quantity;
        $history->amount = $auction->amount;
        $history->coin_post_balance = $currentUser->coin_balance;
        $history->status = 1;
        $history->details = 'Created Auction';
        $history->save();

        $notify[] = ['success', 'Your auction created successfully'];
        return redirect()->route('user.auction.my')->withNotify($notify);

    }

    public function myAuction(){
        $page_title = 'My Auctions';
        $auctions = Auction::where('auction_owner', Auth::user()->id)->with('user')->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.auction.myAuction', compact('page_title', 'auctions'));
    }

    public function auctionBack(Request $request){

        $request->validate([
            'id' => 'required|exists:auctions,id',
        ]);

        $currentUser = Auth::user();

        $findAuction = Auction::where('id', $request->id)
                              ->where('auction_owner', $currentUser->id)
                              ->where('status', 1)
                              ->firstOrFail();

        $findAuction->status = 2;
        $findAuction->save();

        $currentUser->coin_balance += $findAuction->quantity;
        $currentUser->save();

        $general = GeneralSetting::first();

        $history = new CoinHistory();
        $history->user_id = $currentUser->id;
        $history->type = '+';
        $history->stage = '';
        $history->coin_rate = '';
        $history->coin_quantity = $findAuction->quantity;
        $history->amount = $findAuction->amount;
        $history->coin_post_balance = $currentUser->coin_balance;
        $history->status = 1;
        $history->details = 'Backed ' .getAmount($findAuction->quantity).' '.$general->coin_name;
        $history->save();

        $notify[] = ['success', 'Your auction has backed successfully'];
        return redirect()->back()->withNotify($notify);

    }

    public function auctionList(){

        $phase = IcoPlan::whereDate('start_date', '<=', Carbon::now())
                        ->whereDate('end_date', '>=', Carbon::now())
                        ->first();

        if(!$phase){
            $notify[] = ['error', 'Sorry there is no phase'];
            return redirect()->back()->withNotify($notify);
        }

        $user = Auth::user();

        $auctions = Auction::where('auction_owner', '!=', $user->id)
                           ->where('status', 1)
                           ->latest()
                           ->paginate(getPaginate());

        $page_title = 'Auction List';
        return view($this->activeTemplate.'user.auction.list', compact('page_title', 'phase', 'auctions'));
    }


    public function buyAuction(Request $request){

        $phase = IcoPlan::whereDate('start_date', '<=', Carbon::now())
                        ->whereDate('end_date', '>=', Carbon::now())
                        ->first();

        if(!$phase){
            $notify[] = ['error', 'Sorry there is no phase'];
            return redirect()->back()->withNotify($notify);
        }

        $request->validate([
            'id' => 'required|exists:auctions,id',
        ]);

        $buyer = Auth::user();

        $auctionBuyPermission = Auction::where('auction_owner', '!=', $buyer->id)
                                       ->where('id', $request->id)
                                       ->where('status', 1)
                                       ->firstOrFail();

        $owner = User::find($auctionBuyPermission->auction_owner);

        $getAmountOfParcent = ($auctionBuyPermission->expected_profit * $phase->price) / 100;
        $finalPrice = $phase->price + $getAmountOfParcent;
        $amount = $auctionBuyPermission->quantity * $finalPrice;

        if($buyer->balance < $amount){
            $notify[] = ['error', 'You have not sufficient balance to buy auction'];
            return redirect()->route('user.deposit')->withNotify($notify);
        }

        $general = GeneralSetting::first();

        $owner->balance += $amount;
        $owner->save();

        $buyer->balance -= $amount;
        $buyer->coin_balance += $auctionBuyPermission->quantity;
        $buyer->save();

        $auctionBuyPermission->auction_buyer = $buyer->id;
        $auctionBuyPermission->status = 3;
        $auctionBuyPermission->auction_completed = Carbon::now();
        $auctionBuyPermission->amount = $amount;
        $auctionBuyPermission->save();

        $history = new CoinHistory();
        $history->user_id = $buyer->id;
        $history->type = '+';
        $history->coin_rate = $phase->price;
        $history->coin_quantity = $auctionBuyPermission->quantity;
        $history->amount = $amount;
        $history->coin_post_balance = $buyer->coin_balance;
        $history->status = 1;
        $history->details = 'Buy '.getAmount($auctionBuyPermission->quantity).' '.$general->coin_name.' from auction';
        $history->save();

        $trx = getTrx();

        //Transaction for Owner
        $transaction = new Transaction();
        $transaction->user_id = $owner->id;
        $transaction->amount = $amount;
        $transaction->charge = 0;
        $transaction->post_balance = $owner->balance;
        $transaction->trx_type = '+';
        $transaction->trx = $trx;
        $transaction->details = 'Get '.$amount.' '.$general->cur_text.' from the auction';
        $transaction->save();

        $transaction = new Transaction();
        $transaction->user_id = $buyer->id;
        $transaction->amount = $amount;
        $transaction->charge = 0;
        $transaction->post_balance = $buyer->balance;
        $transaction->trx_type = '-';
        $transaction->trx = $trx;
        $transaction->details = 'Pay '.$amount.' '.$general->cur_text.' for the auction';
        $transaction->save();

        notify($owner, 'PURCHASED_AUCTION', [
            'amount' => $amount,
            'trx_id' => $trx,
            'quantity' => getAmount($auctionBuyPermission->quantity),
            'parcent' => getAmount($auctionBuyPermission->expected_profit),
            'buyer' => $buyer->fullname,
            'purchased' => $auctionBuyPermission->auction_completed,
            'currency' => $general->cur_text,
            'coin_text' => $general->coin_name,
        ]);

        $notify[] = ['success', 'You have purchased auction successfully'];
        return redirect()->route('user.auction.buy.history')->withNotify($notify);

    }

    public function auctionPurchasedHistory(){
        $user = Auth::user();
        $auctions = Auction::where('auction_buyer', $user->id)->latest()->paginate(getPaginate());
        $page_title = 'Purchased Auctions';
        return view($this->activeTemplate.'user.auction.purchasedList', compact('page_title', 'auctions'));
    }

    public function referrals(){
        $referrals = User::where('ref_by',auth()->user()->id)->paginate(getPaginate());
        $page_title = 'My Referrals';
        return view($this->activeTemplate.'user.referrals',compact('page_title','referrals'));
    }


}
