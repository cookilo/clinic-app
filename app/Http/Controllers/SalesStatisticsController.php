<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Repositories\SalesStatisticsRepository;

/**
 * Class SalesStatisticsController.
 *
 * @package namespace App\Http\Controllers;
 */
class SalesStatisticsController extends Controller
{
    /**
     * @var SalesStatisticsRepository
     */
    protected SalesStatisticsRepository $repository;

    /**
     * SalesStatisticsController constructor.
     *
     * @param SalesStatisticsRepository $repository
     */
    public function __construct(SalesStatisticsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $searchParams  = request()->only(['start_sale_date', 'end_sale_date']);
        $salesStatistics = $this->repository->search($searchParams)->orderBy('sale_date', 'desc')->paginate(50)->appends(request()->query());
        return view('backend.sales_statistics.index', compact('salesStatistics'));
    }
}
