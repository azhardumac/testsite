<?php

namespace App\Http\Controllers\Admin;

use App\CoinHistory;
use App\Deposit;
use App\User;
use App\UserLogin;
use App\Withdrawal;
use App\WithdrawMethod;
use App\Transaction;
use App\IcoPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Rules\FileTypeValidate;
use App\Auction;
use App\Subscriber;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class AdminController extends Controller
{

    public function dashboard()
    {
        $page_title = 'Dashboard';

        $phase = IcoPlan::whereDate('start_date', '<=', Carbon::now())
                        ->whereDate('end_date', '>=', Carbon::now())
                        ->first();

        // User Info
        $widget['total_users'] = User::count();
        $widget['verified_users'] = User::where('status', 1)->count();
        $widget['email_unverified_users'] = User::where('ev', 0)->count();
        $widget['sms_unverified_users'] = User::where('sv', 0)->count();

        $widget['phase'] = IcoPlan::where('start_date', '<=', Carbon::now())
                                    ->where('end_date', '>=', Carbon::now())
                                    ->first() ?? '0';

        $widget['total_trx'] = Transaction::count();

        $widget['sum_of_coin_transaction'] = CoinHistory::count();
        $widget['sum_of_auction'] = Auction::count();
        $widget['sum_of_subscriber'] = Subscriber::count();

        // Monthly Deposit & Withdraw Report Graph
        $report['months'] = collect([]);
        $report['deposit_month_amount'] = collect([]);
        $report['withdraw_month_amount'] = collect([]);

        $depositsMonth = Deposit::whereYear('created_at', '>=', Carbon::now()->subYear())
            ->selectRaw("SUM( CASE WHEN status = 1 THEN amount END) as depositAmount")
            ->selectRaw("DATE_FORMAT(created_at,'%M') as months")
            ->orderBy('created_at')
            ->groupBy(DB::Raw("MONTH(created_at)"))->get();

        $depositsMonth->map(function ($aaa) use ($report) {
            $report['months']->push($aaa->months);
            $report['deposit_month_amount']->push(getAmount($aaa->depositAmount));
        });

        $withdrawalMonth = Withdrawal::whereYear('created_at', '>=', Carbon::now()->subYear())->where('status', 1)
            ->selectRaw("SUM( CASE WHEN status = 1 THEN amount END) as withdrawAmount")
            ->selectRaw("DATE_FORMAT(created_at,'%M') as months")
            ->orderBy('created_at')
            ->groupBy(DB::Raw("MONTH(created_at)"))->get();
        $withdrawalMonth->map(function ($bb) use ($report){
            $report['withdraw_month_amount']->push(getAmount($bb->withdrawAmount));
        });


        // Withdraw Graph
        $withdrawal = Withdrawal::where('created_at', '>=', \Carbon\Carbon::now()->subDays(30))->where('status', 1)
            ->select(array(DB::Raw('sum(amount)   as totalAmount'), DB::Raw('DATE(created_at) day')))
            ->groupBy('day')->get();
        $withdrawals['per_day'] = collect([]);
        $withdrawals['per_day_amount'] = collect([]);
        $withdrawal->map(function ($a) use ($withdrawals) {
            $withdrawals['per_day']->push(date('d M', strtotime($a->day)));
            $withdrawals['per_day_amount']->push($a->totalAmount + 0);
        });


        // user Browsing, Country, Operating Log
        $user_login_data = UserLogin::whereDate('created_at', '>=', \Carbon\Carbon::now()->subDay(30))->get(['browser', 'os', 'country']);

        $chart['user_browser_counter'] = $user_login_data->groupBy('browser')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_os_counter'] = $user_login_data->groupBy('os')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_country_counter'] = $user_login_data->groupBy('country')->map(function ($item, $key) {
            return collect($item)->count();
        })->sort()->reverse()->take(5);


        $payment['total_deposit_amount'] = Deposit::where('status',1)->sum('amount');
        $payment['total_deposit_charge'] = Deposit::where('status',1)->sum('charge');
        $payment['total_deposit_pending'] = Deposit::where('status',2)->count();
        $payment['total_deposit'] = Deposit::where('status',1)->count();

        $paymentWithdraw['total_withdraw_amount'] = Withdrawal::where('status',1)->sum('amount');
        $paymentWithdraw['total_withdraw_method'] = WithdrawMethod::count();
        $paymentWithdraw['total_withdraw_charge'] = Withdrawal::where('status',1)->sum('charge');
        $paymentWithdraw['total_withdraw_pending'] = Withdrawal::where('status',2)->count();


        $latestUser = User::latest()->limit(6)->get();
        $empty_message = 'User Not Found';

        return view('admin.dashboard', compact('page_title', 'widget', 'report', 'withdrawals', 'chart','payment','paymentWithdraw','latestUser','empty_message', 'phase'));
    }


    public function profile()
    {
        $page_title = 'Profile';
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('page_title', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'image' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $user = Auth::guard('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = uploadImage($request->image, imagePath()['profile']['admin']['path'], imagePath()['profile']['admin']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('admin.profile')->withNotify($notify);
    }


    public function password()
    {
        $page_title = 'Password Setting';
        $admin = Auth::guard('admin')->user();
        return view('admin.password', compact('page_title', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = Auth::guard('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('admin.password')->withNotify($notify);
    }

    public function whitePaper(){
        $page_title = 'White Paper-PDF';
        $pdf = 'assets/whitePaper/Whitepaper.pdf';
        $exists = file_exists($pdf);
        return view('admin.whitePaper.index', compact('page_title', 'pdf', 'exists'));
    }

    public function createWhitePaper(Request $request){
        $request->validate([
            // 'pdf' => 'required|mimes:.pdf,.PDF|max:5120',
            'pdf' => ['required', 'max:5120', new FileTypeValidate(['pdf'])]
        ]);

        $file = $request->file('pdf');
        $destinationPath = 'assets/whitePaper/';
        $originalFile = $file->getClientOriginalExtension();
        $filename = 'Whitepaper.'.strtolower($originalFile);
        $file->move($destinationPath, $filename);

        $notify[] = ['success', 'White paper uploaded Successfully.'];
        return back()->withNotify($notify);

    }

    public function deleteWhitePaper(Request $request){

        $destinationPath = 'assets/whitePaper/';
        $filename = 'Whitepaper.pdf';
        $exists = file_exists($destinationPath.$filename);

        if($exists){
            unlink($destinationPath.$filename);
            $notify[] = ['success', 'White paper deleted Successfully.'];
            return back()->withNotify($notify);
        }else{
            $notify[] = ['error', 'Invalid Request.'];
            return back()->withNotify($notify);
        }
    }

    public function updateWhitePaper(Request $request){

        $request->validate([
            // 'pdf' => 'required|mimes:pdf|max:5120',
            'pdf' => ['required', 'max:5120', new FileTypeValidate(['pdf'])]
        ]);

        $file = 'assets/whitePaper/Whitepaper.pdf';

        if(!$file){
            $notify[] = ['error', 'Invalid Request'];
            return back()->withNotify($notify);
        }
            unlink($file);
            $file = $request->file('pdf');
            $destinationPath = 'assets/whitePaper/';
            $originalFile = $file->getClientOriginalExtension();
            $filename = 'Whitepaper.'.strtolower($originalFile);
            $file->move($destinationPath, $filename);

            $notify[] = ['success', 'White paper updated Successfully.'];
            return back()->withNotify($notify);
    }

    public function profit(){

        $page_title = 'Profit Details';

        $depositCharge = Deposit::where('status', 1)->where('charge', '!=', 0)->get();
        $withdrawCharge = Withdrawal::where('status', 1)->where('charge', '!=', 0)->get();
        $depositArray = [];
        $withdrawArray = [];

        foreach ($depositCharge as $key => $value) {
            $depositArray[$key] = array(
                'type'=>'Deposit',
                'trx'=>$value->trx,
                'user_id'=>$value->user_id,
                'username'=>$value->user->username,
                'method'=>$value->method_currency,
                'charge'=>$value->charge,
                'time'=>$value->created_at
            );
        }

        foreach ($withdrawCharge as $key => $value) {
            $withdrawArray[$key] = array(
                'type'=>'Withdrawal',
                'trx'=>$value->trx,
                'user_id'=>$value->user_id,
                'username'=>$value->user->username,
                'method'=>$value->currency,
                'charge'=>$value->charge,
                'time'=>$value->created_at
            );
        }

        $customArray = array_merge($depositArray, $withdrawArray);
        $charges = $this->paginate($customArray, $perPage = getPaginate());

        $empty_message = 'There is no data';
        return view('admin.profit', compact('page_title', 'charges', 'empty_message'));
    }


    public function paginate($items, $perPage, $page = null, $options = []){
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path'=> route('admin.profit')]);
    }


    public function coinHistory(){
        $page_title = 'Coin Transaction History';
        $histories = CoinHistory::with('user')->latest()->paginate(getPaginate());
        $empty_message = 'There is no data';
        return view('admin.coinHistory', compact('page_title', 'empty_message', 'histories'));
    }

    public function totalAuction(){
        $page_title = 'Auction Histories';
        $histories = Auction::latest()->paginate(getPaginate());
        $empty_message = 'There is no data';
        return view('admin.auctionLog', compact('page_title', 'empty_message', 'histories'));
    }

    public function runningAuction(){
        $page_title = 'Running Auctions';
        $histories = Auction::where('status', 1)->latest()->paginate(getPaginate());
        $empty_message = 'There is no data';
        return view('admin.auctionLog', compact('page_title', 'empty_message', 'histories'));
    }

    public function backedAuction(){
        $page_title = 'Backed Auctions';
        $histories = Auction::where('status', 2)->latest()->paginate(getPaginate());
        $empty_message = 'There is no data';
        return view('admin.auctionLog', compact('page_title', 'empty_message', 'histories'));
    }

    public function completedAuction(){
        $page_title = 'Completed Auctions';
        $histories = Auction::where('status', 3)->latest()->paginate(getPaginate());
        $empty_message = 'There is no data';
        return view('admin.auctionLog', compact('page_title', 'empty_message', 'histories'));
    }




    public function requestReport()
    {
        $page_title = 'Your Listed Report & Request';
        $arr['app_name'] = systemDetails()['name'];
        $arr['app_url'] = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASE_CODE');
        $url = "https://license.viserlab.com/issue/get?".http_build_query($arr);
        $response = json_decode(curlContent($url));
        if ($response->status == 'error') {
            return redirect()->route('admin.dashboard')->withErrors($response->message);
        }
        $reports = $response->message[0];
        return view('admin.reports',compact('reports','page_title'));
    }

    public function reportSubmit(Request $request)
    {
        $request->validate([
            'type'=>'required|in:bug,feature',
            'message'=>'required',
        ]);
        $url = 'https://license.viserlab.com/issue/add';

        $arr['app_name'] = systemDetails()['name'];
        $arr['app_url'] = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASE_CODE');
        $arr['req_type'] = $request->type;
        $arr['message'] = $request->message;
        $response = json_decode(curlPostContent($url,$arr));
        if ($response->status == 'error') {
            return back()->withErrors($response->message);
        }
        $notify[] = ['success',$response->message];
        return back()->withNotify($notify);
    }

    public function systemInfo(){
        $laravelVersion = app()->version();
        $serverDetails = $_SERVER;
        $currentPHP = phpversion();
        $timeZone = config('app.timezone');
        $page_title = 'System Information';
        return view('admin.info',compact('page_title', 'currentPHP', 'laravelVersion', 'serverDetails','timeZone'));
    }








}
