<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthlySalesController extends Controller
{
    public function index(Request $request)
    {

        $year = $request->input('year');
        $month = $request->input('month');

        $query = DB::table('monthly_sales');

        if ($year) {
            $query->where('sale_year', $year);
        }

        if ($month) {
            $query->where('sale_month', $month);
        }

        $sales = $query->orderBy('sale_year', 'desc')
                       ->orderBy('sale_month', 'desc')
                       ->paginate(10)
                       ->appends($request->only(['year', 'month']));

        return view('backend.reports.monthly_sales', compact('sales'));
    }
}
