<?php

namespace App\Http\Controllers;

use App\Repositories\InvoiceItemRepository;
use App\Repositories\MedicationRepository;
use App\Repositories\PatientRepository;
use App\Repositories\PrescriptionRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\InvoiceRequest;
use App\Repositories\InvoiceRepository;

/**
 * Class InvoicesController.
 *
 * @package namespace App\Http\Controllers;
 */
class InvoicesController extends Controller
{
    /**
     * @var InvoiceRepository
     */
    protected InvoiceRepository $repository;

    /**
     * @var InvoiceItemRepository
     */
    protected InvoiceItemRepository $invoiceItemRepository;

    /**
     * @var MedicationRepository
     */
    protected MedicationRepository $medicationRepository;

    /**
     * @var PatientRepository
     */
    protected PatientRepository $patientRepository;

    /**
     * @var PrescriptionRepository
     */
    protected PrescriptionRepository $prescriptionRepository;

    /**
     * InvoicesController constructor.
     *
     * @param InvoiceRepository $repository
     * @param MedicationRepository $medicationRepository
     * @param InvoiceItemRepository $invoiceItemRepository
     * @param PatientRepository $patientRepository
     * @param PrescriptionRepository $prescriptionRepository
     */
    public function __construct(InvoiceRepository $repository,
                                MedicationRepository $medicationRepository,
                                InvoiceItemRepository $invoiceItemRepository,
                                PatientRepository $patientRepository,
                                PrescriptionRepository $prescriptionRepository,
    )
    {
        $this->repository             = $repository;
        $this->invoiceItemRepository  = $invoiceItemRepository;
        $this->medicationRepository   = $medicationRepository;
        $this->patientRepository      = $patientRepository;
        $this->prescriptionRepository = $prescriptionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Foundation\Application|Application|Factory|View
     */
    public function index(Request $request): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $patientId = $request->query('patient_id');
        if ($patientId) {

            $conditions = [];
            $createdAt                = $request->query('created_at');
            $conditions['patient_id'] = $patientId;

            if ($createdAt) {
                $invoices = $this->repository->findInvoicesByDate($createdAt, $conditions);
            } else {
                $invoices = $this->repository->findWhere($conditions)->sortByDesc('created_at');
            }

            $patient = $this->patientRepository->find($conditions['patient_id']);

            return view('backend.invoices.index', compact('invoices', 'patient'));
        } else {

            $searchParams = request()->only(['start_created_at', 'end_created_at', 'patient_name']);
            $invoices     = $this->repository->search($searchParams)->orderBy('created_at', 'desc')->paginate(50);
            return view('backend.invoices.list', compact('invoices'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param InvoiceRequest $request
     *
     * @return JsonResponse
     *
     */
    public function store(InvoiceRequest $request): JsonResponse
    {
        DB::beginTransaction(); // Bắt đầu transaction để đảm bảo tính toàn vẹn của dữ liệu
        try {

            // Tạo bản ghi trong bảng invoices
            $invoiceData = $request->only(['patient_id', 'weight', 'symptoms', 'diagnosis', 'paraclinical', 'notes']);
            $invoice     = $this->repository->create($invoiceData);

            // Lấy thông tin medications từ request
            $medications = $request->input('medications', []);
            if (empty($medications)) {
                DB::rollBack(); // Rollback nếu có lỗi
                return response()->json([
                                            'status'  => 'error',
                                            'message' => 'Vui lòng thêm thuốc!',
                                        ], 422);
            }

            $totalAmount = 0;
            foreach ($medications as $medicationId => $medicationData) {
                $quantity           = (int)$medicationData['quantity'];
                $salePrice          = (int)$medicationData['sale_price'];
                $purchasePrice      = (int)$medicationData['purchase_price'];
                $dosageInstructions = $medicationData['dosage_instructions'];

                if ($quantity <= 0 || $salePrice <= 0) {
                    continue; // Bỏ qua nếu quantity hoặc price không hợp lệ
                }

                // Lấy thông tin thuốc từ bảng medications
                $medication = $this->medicationRepository->find($medicationId);
                if (!$medication) {
                    // Nếu không tìm thấy thuốc, rollback và trả về lỗi
                    DB::rollBack();
                    return response()->json([
                                                'status'  => 'error',
                                                'message' => "Không tìm thấy thuốc với ID: {$medicationId}.",
                                            ], 422);
                }

                if ($medication->stock < $quantity) {
                    // Nếu tồn kho không đủ, rollback và trả về lỗi
                    DB::rollBack();
                    return response()->json([
                                                'status'  => 'error',
                                                'message' => "Thuốc {$medication->name} không đủ hàng trong kho. Thiếu: {$medication->stock}."
                                            ], 422);
                }

                // Tính tổng tiền cho từng dòng
                $totalPrice  = $quantity * $salePrice;
                $totalAmount += $totalPrice;

                // Tạo dòng invoice_items
                $this->invoiceItemRepository->create([
                                                         'invoice_id'          => $invoice->id,
                                                         'medication_id'       => $medicationId,
                                                         'quantity'            => $quantity,
                                                         'purchase_price'      => $purchasePrice,
                                                         'sale_price'          => $salePrice,
                                                         'total_price'         => $totalPrice,
                                                         'dosage_instructions' => $dosageInstructions,
                                                     ]);

                // Trừ số lượng stock trong bảng medications
                $medication->stock -= $quantity;
                $medication->save();
            }

            // Cập nhật tổng tiền vào bảng invoices
            $invoice->update(['total_amount' => $totalAmount]);

            DB::commit(); // Commit transaction nếu thành công

            return response()->json([
                                        'status'  => 'success',
                                        'message' => 'Lịch sử khám bệnh đã được tạo thành công.',
                                        'data'    => $invoice->toArray(),
                                    ], 200);
        } catch (ValidatorException $e) {
            DB::rollBack(); // Rollback nếu có lỗi

            return response()->json([
                                        'status' => 'error',
                                        'errors' => $e->getMessageBag(),
                                    ], 422);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback nếu có bất kỳ lỗi nào

            return response()->json([
                                        'status'  => 'error',
                                        'message' => 'Xảy ra lỗi khi tạo hóa đơn: ' . $e->getMessage(),
                                    ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Foundation\Application|Application|Factory|View
     */
    public function show(int $id): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $invoice = $this->repository->with(['invoiceItems.medication'])->find($id);
        return view('backend.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Foundation\Application|Application|Factory|View
     */
    public function edit(int $id): Application|Factory|View|\Illuminate\Foundation\Application
    {
        // Lấy hồ sơ y tế với mối quan hệ liên kết với invoice, invoiceItems, và medication
        $invoice = $this->repository->with(['invoiceItems.medication'])->find($id);

        // Kiểm tra xem hồ sơ y tế có tồn tại không, nếu không, ném ra exception hoặc trả về thông báo lỗi
        if (!$invoice) {
            abort(404, 'Lịch sử khám bệnh không tìm thấy.');
        }

        $medications   = $this->medicationRepository->all();
        $prescriptions = $this->prescriptionRepository->all();

        // Trả về view edit với dữ liệu invoice và medications
        return view('backend.invoices.edit', compact('invoice', 'medications', 'prescriptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param InvoiceRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     */
    public function update(InvoiceRequest $request, int $id): JsonResponse
    {

        DB::beginTransaction(); // Bắt đầu transaction để đảm bảo tính toàn vẹn của dữ liệu
        try {
            // Lấy thông tin hóa đơn
            $invoice = $this->repository->find($id);

            // Lấy thông tin thuốc từ request
            $medications = $request->input('medications', []);
            if (empty($medications)) {
                DB::rollBack(); // Rollback nếu có lỗi
                return response()->json([
                                            'status'  => 'error',
                                            'message' => 'Vui lòng thêm thuốc!',
                                        ], 422);
            }

            $totalAmount = 0;

            // Lấy danh sách các thuốc đã tồn tại trong invoice trước đó
            $existingInvoiceItems = $this->invoiceItemRepository->findByField('invoice_id', $invoice->id);

            // Tạo một mảng để lưu các thuốc hiện tại sau khi cập nhật
            $currentMedicationIds = array_keys($medications);

            // Kiểm tra và xóa các invoice_items không có trong request hiện tại (các thuốc đã bị xóa)
            foreach ($existingInvoiceItems as $existingItem) {
                if (!in_array($existingItem->medication_id, $currentMedicationIds)) {
                    // Lấy lại thuốc để khôi phục stock
                    $medication = $this->medicationRepository->find($existingItem->medication_id);

                    // Cộng lại số lượng thuốc đã bị xóa vào stock
                    if ($medication) {
                        $medication->stock += $existingItem->quantity;
                        $medication->save();
                    }

                    // Xóa invoice_item
                    $existingItem->delete();
                }
            }

            // Lặp qua các thuốc trong request
            foreach ($medications as $medicationId => $medicationData) {
                $newQuantity        = (int)$medicationData['quantity'];
                $purchasePrice      = (int)$medicationData['purchase_price'];
                $salePrice          = (int)$medicationData['sale_price'];
                $dosageInstructions = $medicationData['dosage_instructions'];
                if ($newQuantity > 0 && $salePrice > 0) {
                    // Lấy thông tin thuốc từ bảng medications để kiểm tra tồn kho
                    $medication = $this->medicationRepository->find($medicationId);

                    if ($medication) {
                        // Kiểm tra tồn tại invoice_item
                        $invoiceItem = $this->invoiceItemRepository->findByInvoiceAndMedication($invoice->id, $medicationId);

                        // Lưu số lượng cũ để khôi phục
                        $oldQuantity = $invoiceItem ? $invoiceItem->quantity : 0;

                        // Tính tổng số lượng thuốc tồn kho với số lượng cũ
                        $availableStock = $medication->stock + $oldQuantity;

                        if ($newQuantity > $availableStock) {
                            DB::rollBack();
                            return response()->json([
                                                        'status'  => 'error',
                                                        'message' => "Thuốc {$medication->name} không đủ hàng trong kho. Còn lại: {$medication->stock}.",
                                                    ], 422);
                        }

                        // Cập nhật hoặc tạo mới invoice_item
                        if ($invoiceItem) {
                            // Nếu đã có invoice_item, cập nhật số lượng và giá tiền
                            $invoiceItem->update([
                                                     'quantity'            => $newQuantity,
                                                     'total_price'         => $newQuantity * $invoiceItem->sale_price,
                                                     'dosage_instructions' => $dosageInstructions,
                                                 ]);
                        } else {
                            // Nếu chưa có, tạo mới invoice_item
                            $this->invoiceItemRepository->create([
                                                                     'invoice_id'          => $invoice->id,
                                                                     'medication_id'       => $medicationId,
                                                                     'quantity'            => $newQuantity,
                                                                     'sale_price'          => $salePrice,
                                                                     'purchase_price'      => $purchasePrice,
                                                                     'total_price'         => $newQuantity * $salePrice,
                                                                     'dosage_instructions' => $dosageInstructions,
                                                                 ]);
                        }

                        // Tính tổng tiền cho hóa đơn
                        $totalAmount += $newQuantity * $salePrice;

                        // Trừ số lượng stock trong bảng medications
                        $medication->stock -= ($newQuantity - $oldQuantity); // Chỉ trừ số lượng tăng thêm
                        $medication->save();
                    }
                }
            }

            // Cập nhật tổng tiền trong bảng invoices
            $invoiceData                 = $request->only(['weight', 'symptoms', 'diagnosis', 'paraclinical', 'notes']);
            $invoiceData['total_amount'] = $totalAmount;
            $invoice->update($invoiceData);

            DB::commit(); // Commit transaction nếu thành công

            return response()->json([
                                        'status'  => 'success',
                                        'message' => 'Lịch sử khám bệnh đã được cập nhật thành công.',
                                        'data'    => $invoice->toArray(),
                                    ], 200);
        } catch (ValidatorException $e) {

            DB::rollBack();
            return response()->json([
                                        'status' => 'error',
                                        'errors' => $e->getMessageBag()->toArray(),
                                    ], 422);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback nếu có bất kỳ lỗi nào

            return response()->json([
                                        'status'  => 'error',
                                        'message' => 'Xảy ra lỗi khi tạo lịch sử khám bệnh: ' . $e->getMessage(),
                                    ], 500);
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
            // Tìm invoice
            $invoice = $this->repository->find($id);

            if ($invoice) {

                // Lấy tất cả các invoice_items liên quan đến invoice
                $invoiceItems = $this->invoiceItemRepository->findByField('invoice_id', $invoice->id);

                // Duyệt qua các invoice_items để khôi phục lại tồn kho thuốc
                foreach ($invoiceItems as $invoiceItem) {
                    // Lấy thông tin thuốc
                    $medication = $this->medicationRepository->find($invoiceItem->medication_id);

                    if ($medication) {
                        // Cộng lại số lượng thuốc từ invoice_item vào tồn kho
                        $medication->stock += $invoiceItem->quantity;
                        $medication->save();
                    }
                }

                // Xóa các invoice_items liên quan đến invoice
                $this->invoiceItemRepository->deleteByInvoiceId($invoice->id);

                // Xóa invoice
                $this->repository->delete($invoice->id);

                DB::commit(); // Commit transaction
                return response()->json(['message' => 'Lịch sử khám bệnh đã được xóa thành công.'], 200);
            } else {
                return response()->json(['message' => 'Lịch sử khám bệnh không tìm thấy.'], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback nếu có lỗi
            return response()->json(['message' => 'Có lỗi xảy ra khi xóa lịch sử khám bệnh.'], 500);
        }
    }


    /**
     * Show the form for adding the specified resource.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Foundation\Application|View|Factory|Application
    {

        $medications   = $this->medicationRepository->all();
        $prescriptions = $this->prescriptionRepository->all();
        return view('backend.invoices.create', compact('medications', 'prescriptions'));
    }

    /**
     * In hóa đơn cho một hóa đơn cụ thể.
     *
     * @param int $id ID của hóa đơn
     * @return Response
     */
    public function printInvoice(int $id): Response
    {

        $invoice = $this->repository->with(['invoiceItems.medication'])->find($id);
        if ($invoice) {
            $patient = $this->patientRepository->find($invoice->patient_id);
            $invoice->invoiceItems = $invoice->invoiceItems->filter(function ($item) {
                return $item->medication->print_invoice == 1;
            });

            $pdf = PDF::loadView('backend.invoices.invoice', compact('invoice', 'patient'))->setPaper('a4');

            return $pdf->stream('invoice_' . $invoice->id . '.pdf');
        }

        abort(404, 'Không tìm thấy hóa đơn.');
    }
}
