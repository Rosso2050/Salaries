<?php

namespace App\Http\Controllers;

use App\Bounce;
use App\Employee;
use App\Salary;
use Illuminate\Http\Request;
use Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function empEdit($id)
    {
        $emp=Employee::find($id);
//        return view('emp',compact('$emp'));
        return response()->json($emp, 200);
    }
    public function empUpdate($id,$bounce)
    {
        Salary::query()->delete();
         $emp=Employee::find($id);
         $emp->bounce_percent=$bounce;
         $emp->save();
        return response()->json($emp, 200);
    }
    public function calculateResult()
    {
        Salary::query()->delete();
        $months=['Jan','Feb','March','April','May','June','July','Aug','Sep','Oct','Nov','Dec'];
        $Days=[];
        foreach ($months as $key=>$month)
        {
            $myday=new Carbon('last day of '.$month);
            $bounceDay=new Carbon('first day of '.$month);
            $bounceDay=date('Y-m-d H:i:s', strtotime($bounceDay . ' +14 day'));
            if ($myday->format('D') == 'Fri')
            {
                $myday=date('Y-m-d H:i:s', strtotime($myday . ' -1 day'));
            }
            elseif ($myday->format('D') == 'Sat')
            {
                $myday=date('Y-m-d H:i:s', strtotime($myday . ' -2 day'));
            }
            if (Carbon::parse($bounceDay)->format('D') == 'Fri')
            {
                $bounceDay=date('Y-m-d H:i:s', strtotime($bounceDay . ' +2 day'));
            }
            elseif (Carbon::parse($bounceDay)->format('D') == 'Sat')
            {
                $bounceDay=date('Y-m-d H:i:s', strtotime($bounceDay . ' +1 day'));
            }

            $Days[]=['monthNum'=>$key+1,'month'=>$month,'last_day'=>$myday.'','BounceDay'=>$bounceDay];
        }
        //=========================================
        $emps=Employee::all();
        $total_salaries=0;
        $total_bounces=0;
        foreach ($emps as $emp)
        {
            $total_salaries+=$emp->salary;
            $total_bounces+=($emp->salary*$emp->bounce_percent)/100;
        }
        foreach ($Days as $day){
            $salary=new Salary();
            $salary->year=date('Y');
            $salary->month=$day['month'];
            $salary->monthNum=$day['monthNum'];
            $salary->salary_pay_day=$day['last_day'];
            $salary->bounce_pay_day=$day['BounceDay'];
            $salary->salary_total=$total_salaries;
            $salary->bounce_total=$total_bounces;
            $salary->save();
        }
        $salaries=Salary::all();
        return response()->json($salaries, 200);
    }

    public function showMonth($monthNum)
    {
        $salaries=Salary::where('monthNum',$monthNum)->get();
//        dd($salaries);
        return response()->json($salaries, 200);
    }

    public function test()
    {
        dd(date('Y'));
        $months=['Jan','Feb','March','April','May','June','July','Aug','Sep','Oct','Nov','Dec'];
        $Days=[];
        foreach ($months as $month)
        {

            $myday=new Carbon('last day of '.$month);
            $bounceDay=new Carbon('first day of '.$month);
            $bounceDay=date('Y-m-d H:i:s', strtotime($bounceDay . ' +14 day'));
            if ($myday->format('D') == 'Fri')
            {
                $myday=date('Y-m-d H:i:s', strtotime($myday . ' -1 day'));
            }
            elseif ($myday->format('D') == 'Sat')
            {
                $myday=date('Y-m-d H:i:s', strtotime($myday . ' -2 day'));
            }
            if (Carbon::parse($bounceDay)->format('D') == 'Fri')
            {
                $bounceDay=date('Y-m-d H:i:s', strtotime($bounceDay . ' +2 day'));
            }
            elseif (Carbon::parse($bounceDay)->format('D') == 'Sat')
            {
                $bounceDay=date('Y-m-d H:i:s', strtotime($bounceDay . ' +1 day'));
            }

            $Days[]=['month'=>$month,'last_day'=>$myday.'','BounceDay'=>$bounceDay];
        }


    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('home');
    }
}
