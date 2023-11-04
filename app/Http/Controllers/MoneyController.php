<?php

namespace App\Http\Controllers;

use App\Models\Spending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //spendingsテーブルから当月・一週間分(日曜日開始)のデータを取得
        //今日日付の曜日番号取得
        $w = date("w",strtotime(date('Y-m-d')));
        //今週最初日付の取得
        $start_day = date('Y-m-d', strtotime("-{$w} day", strtotime(date('Y-m-d'))));
        //今週最後の日付取得
        $w = 6- $w;
        $end_day = date('Y-m-d', strtotime("+{$w} day", strtotime(date('Y-m-d'))));
        //指定週間のデータ取得
        $results=Spending::groupBy('tgt_date')
        ->select('tgt_date',DB::raw('sum(tgt_payment) as sumvalue'))
        ->whereBetween('tgt_date',[$start_day,$end_day])
        ->get();

        $tgt_date = array();
        $tgt_sumvalue = array();


        foreach ($results as $result) {
            // $tgt_date[]   =$result['tgt_date'];
            // $tgt_sumvalu[]=$result['sumvalue'];
            array_push($tgt_date,$result['tgt_date']);
            array_push($tgt_sumvalue,$result['sumvalue']);
        }
        // dd($tgt_date,$tgt_sumvalue);

        return view('money.index',compact('tgt_date','tgt_sumvalue'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('money.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Spending $spending)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Spending $spending)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Spending $spending)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spending $spending)
    {
        //
    }
}
