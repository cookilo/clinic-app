<?php

namespace App\Http\Controllers;

use App\Repositories\InvoiceItemRepository;
use App\Repositories\InvoiceRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PatientRequest;
use App\Repositories\PatientRepository;
use App\Helpers\PhoneHelper;

/**
 * Class PatientsController.
 *
 * @package namespace App\Http\Controllers;
 */
class PatientsController extends Controller
{
    /**
     * @var PatientRepository
     */
    protected PatientRepository $repository;

    /**
     * @var InvoiceRepository
     */
    protected InvoiceRepository $invoiceRepository;

    /**
     * @var InvoiceItemRepository
     */
    protected InvoiceItemRepository $invoiceItemRepository;

    /**
     * PatientsController constructor.
     *
     * @param PatientRepository $repository
     * @param InvoiceRepository $invoiceRepository
     * @param InvoiceItemRepository $invoiceItemRepository
     */
    public function __construct(PatientRepository $repository,
                                InvoiceRepository $invoiceRepository,
                                InvoiceItemRepository $invoiceItemRepository,
    )
    {
        $this->repository = $repository;
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceItemRepository = $invoiceItemRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $searchParams = request()->only(['full_name', 'phone', 'parent_name', 'date_of_birth']);
        $patients = $this->repository->search($searchParams)->orderBy('created_at', 'desc')->paginate(50);

        return view('backend.patients.index', compact('patients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PatientRequest $request
     *
     * @return JsonResponse
     *
     */
    public function store(PatientRequest $request): JsonResponse
    {

        $patient = $this->repository->create($request->all());

        return response()->json([
                                    'status'  => 'success',
                                    'message' => 'Bệnh nhân đã được tạo thành công.',
                                    'data'    => $patient->toArray()
                                ], 200);

    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $patient = $this->repository->find($id);
        if ($patient) {
            $patient->phone = PhoneHelper::formatPhoneNumber($patient->phone);
            return response()->json($patient);
        }

        return response()->json(['message' => 'Bệnh nhân không tìm thấy.'], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $patient = $this->repository->find($id);
        if (!$patient) {
            return response()->json(['message' => 'Bệnh nhân không tồn tại'], 404);
        }

        return response()->json($patient);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PatientRequest $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(PatientRequest $request, int $id): JsonResponse
    {
        try {
            $this->repository->update($request->all(), $id);

            return response()->json(['message' => 'Cập nhật thông tin bệnh nhân thành công!'], 200);
        } catch (ValidatorException $e) {

            return response()->json(['errors' => $e->getMessageBag()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        DB::beginTransaction(); // Bắt đầu transaction

        try {
            // Tìm bệnh nhân
            $patient = $this->repository->find($id);

            if ($patient) {
                    // Tìm hóa đơn liên quan đến bệnh nhân
                    $invoices = $this->invoiceRepository->findWhere(['patient_id' => $id]);

                    if ($invoices) {
                        foreach ($invoices as $invoice) {
                            // Xóa các invoice_items liên quan đến invoice
                            $this->invoiceItemRepository->deleteByInvoiceId($invoice->id);
                            // Xóa invoice
                            $this->invoiceRepository->delete($invoice->id);
                        }
                    }

                // Xóa bệnh nhân
                $this->repository->delete($id);

                DB::commit(); // Commit transaction
                return response()->json(['message' => 'Bệnh nhân và tất cả thông tin liên quan đã được xóa thành công.'], 200);
            } else {
                return response()->json(['message' => 'Bệnh nhân không tìm thấy.'], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback nếu có lỗi
            return response()->json(['message' => 'Có lỗi xảy ra khi xóa bệnh nhân.'], 500);
        }
    }

}
