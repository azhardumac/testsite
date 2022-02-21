<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\IcoPlan;
use Carbon\Carbon;

class IcoController extends Controller
{

    public function manageIcoPage(){
        $page_title = 'ICO Management';
        $phases = IcoPlan::latest()->paginate(getPaginate());
        $empty_message = 'There is no data';
        return view('admin.ico.manageIco', compact('page_title', 'phases', 'empty_message'));
    }

    public function createIco(Request $request){

        $request->validate([
            'start_date' => 'required|date_format:"Y-m-d"',
            'end_date' => 'required|date_format:"Y-m-d"|after:start_date',
            'coin_token' => 'required|integer',
            'price' => 'required|numeric|gt:0',
            'stage' => 'required|string',
        ]);

        $icoPlans = IcoPlan::whereDate('end_date','>',$request->start_date)->first();

        if($icoPlans){
            $notify[] = ['error', 'Please change the time schedule for create a new ICO Plan because it already exists'];
            return back()->withNotify($notify);
        }

        $newIco = new IcoPlan();
        $newIco->start_date = $request->start_date;
        $newIco->end_date = $request->end_date;
        $newIco->total_coin = $request->coin_token;
        $newIco->coin_token = $request->coin_token;
        $newIco->price = $request->price;
        $newIco->stage = ucwords($request->stage);
        $newIco->save();

        $notify[] = ['success', 'Created a new ICO Plan successfully'];
        return back()->withNotify($notify);
    }

    public function deleteIco(Request $request){

        $request->validate([
            'id' => 'required|exists:ico_plans,id'
        ]);

        $findPlan = IcoPlan::find($request->id);
        $findPlan->delete();

        $notify[] = ['success', 'Deleted ICO Plan successfully'];
        return back()->withNotify($notify);
    }

    public function editIco(Request $request){

        $requiredValidation = [
            'price' => 'required|numeric|gt:0',
            'stage' => 'required|string',
            'id' => 'required|exists:ico_plans,id'
        ];

        $sometimeValidation = $request->coin_token ? ['coin_token' => 'integer|gt:0'] : [];

        $rule = array_merge($requiredValidation, $sometimeValidation);

        $request->validate($rule);

        $findPlan = IcoPlan::find($request->id);

        if($request->coin_token){
            $findPlan->total_coin += $request->coin_token;
            $findPlan->coin_token += $request->coin_token;
        }

        $findPlan->price = $request->price;
        $findPlan->stage = ucwords($request->stage);
        $findPlan->save();

        $notify[] = ['success', 'Updated ICO Plan successfully'];
        return back()->withNotify($notify);
    }










}
