<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Plan;

class DashboardController extends Controller
{
    public function index()
    {

        $plansRaw = Plan::select(['label', 'price'])
        ->addSelect(DB::Raw('(select count(users.id) from users where users.plan_id=plans.id and users.status=1) as total_members'))->get();


        $totalMembers = 0;
        $totalSales = 0;
        $plans = [];
        foreach ($plansRaw as $s) {
            $totalMembers += $s->total_members;
            $totalSales += $s->total_members * $s->price;
            $p = $s->toArray();
            $p['total_sales'] = $s->total_members * $s->price;
            $plans[] = $p;
        }

        return view('admin.dashboard', compact('plans', 'totalMembers', 'totalSales'));
    }
}
