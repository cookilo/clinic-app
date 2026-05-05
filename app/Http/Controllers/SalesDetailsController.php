<?php

namespace App\Http\Controllers;

use App\Repositories\MedicationRepository;
use App\Repositories\SalesDetailsRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Class SalesDetailsController.
 *
 * @package namespace App\Http\Controllers;
 */
class SalesDetailsController extends Controller
{
    /**
     * @var SalesDetailsRepository
     */
    protected SalesDetailsRepository $repository;

    /**
     * @var MedicationRepository
     */
    protected MedicationRepository $medicationRepository;

    /**
     * SalesDetailsController constructor.
     *
     * @param SalesDetailsRepository $repository
     * @param MedicationRepository $medicationRepository
     */
    public function __construct(
        SalesDetailsRepository $repository,
        MedicationRepository $medicationRepository,
    )
    {
        $this->repository = $repository;
        $this->medicationRepository = $medicationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $searchParams  = request()->only(['start_sale_date', 'end_sale_date', 'medication_id', 'medication_name']);
        $salesDetails = $this->repository->search($searchParams)->orderBy('sale_date', 'desc')->paginate(50);
        $medications = $this->medicationRepository->all();
        return view('backend.sales_details.index', compact('salesDetails', 'medications'));
    }
}
