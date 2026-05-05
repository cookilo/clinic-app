<?php

namespace App\Http\Controllers;

use App\Repositories\MedicationRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PrescriptionRequest;
use App\Repositories\PrescriptionRepository;

/**
 * Class PrescriptionsController.
 *
 * @package namespace App\Http\Controllers;
 */
class PrescriptionsController extends Controller
{
    /**
     * @var PrescriptionRepository
     */
    protected PrescriptionRepository $repository;

    /**
     * @var MedicationRepository
     */
    protected MedicationRepository $medicationRepository;

    /**
     * PrescriptionsController constructor.
     *
     * @param PrescriptionRepository $repository
     * @param MedicationRepository $medicationRepository
     */
    public function __construct(PrescriptionRepository $repository,
                                MedicationRepository $medicationRepository,
    )
    {
        $this->repository           = $repository;
        $this->medicationRepository = $medicationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Foundation\Application|Application|Factory|View
     */
    public function index(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $searchParams  = request()->only(['title']);
        $prescriptions = $this->repository->search($searchParams)->orderBy('created_at', 'desc')->paginate(50)->appends(request()->query());
        return view('backend.prescriptions.index', compact('prescriptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PrescriptionRequest $request
     *
     * @return JsonResponse
     *
     */
    public function store(PrescriptionRequest $request): JsonResponse
    {

        DB::beginTransaction(); // Bắt đầu transaction để đảm bảo tính toàn vẹn của dữ liệu
        try {
            // Tạo bản ghi trong bảng prescriptions
            $prescriptionData = $request->only(['title']);
            $prescription     = $this->repository->create($prescriptionData);

            // Lấy thông tin medications từ request
            $medications = $request->input('medications', []);

            if (empty($medications)) {
                DB::rollBack(); // Rollback nếu có lỗi
                return response()->json([
                                            'status' => 'error',
                                            'message' => 'Vui lòng thêm thuốc!',
                                        ], 422);
            }

            // Tạo mảng dữ liệu để insert vào bảng pivot
            $prescriptionItems = [];
            foreach ($medications as $medicationId => $medicationData) {
                $prescriptionItems[$medicationId] = [
                    'quantity'            => (int)$medicationData['quantity'],
                    'dosage_instructions' => $medicationData['dosage_instructions'],
                ];
            }

            // Đính kèm thuốc vào đơn thuốc trong bảng pivot
            $prescription->medications()->attach($prescriptionItems);

            DB::commit(); // Commit transaction nếu thành công

            return response()->json([
                                        'status'  => 'success',
                                        'message' => 'Đơn thuốc đã được tạo thành công.',
                                        'data'    => $prescription->toArray(),
                                    ], 200);

        } catch (ValidatorException $e) {
            DB::rollBack(); // Rollback nếu có lỗi
            return response()->json([
                                        'status' => 'error',
                                        'message' => $e->getMessageBag(),
                                    ], 422);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback nếu có bất kỳ lỗi nào
            return response()->json([
                                        'status'  => 'error',
                                        'message' => 'Xảy ra lỗi khi tạo đơn thuốc: ' . $e->getMessage(),
                                    ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Foundation\Application|Application|Factory|View|JsonResponse
     */
    public function show(int $id): Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
    {
        // Tìm đơn thuốc
        $prescription = $this->repository->find($id);

        // Kiểm tra xem hồ sơ y tế có tồn tại không, nếu không, ném ra exception hoặc trả về thông báo lỗi
        if (!$prescription) {
            if (request()->ajax()) {
                return response()->json(['message' => 'Đơn thuốc không tìm thấy.'], 404);
            } else {
                abort(404, 'Đơn thuốc không tìm thấy.');
            }
        }

        // Lấy tất cả medications từ medicationRepository
        $medications = $this->medicationRepository->all();

        $prescribedMedications = $prescription->medications->map(function ($medication) {
            return [
                'id'                  => $medication->id,
                'name'                => $medication->name,
                'unit'                => $medication->unit,
                'quantity'            => $medication->pivot->quantity,
                'dosage_instructions' => $medication->pivot->dosage_instructions,
                'sale_price'          => $medication->sale_price,
            ];
        });

        // Kiểm tra nếu là yêu cầu AJAX, trả về JSON response
        if (request()->ajax()) {
            return response()->json([
                                        'prescription' => $prescription,
                                        'medications'  => $medications,
                                        'prescribedMedications' => $prescribedMedications,
                                    ]);
        }

        // Nếu không phải AJAX, trả về view
        return view('backend.prescriptions.show', compact('prescription', 'medications', 'prescribedMedications'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Application|Factory|\Illuminate\Foundation\Application|View
     */
    public function edit(int $id): View|\Illuminate\Foundation\Application|Factory|Application
    {

        $prescription = $this->repository->find($id);

        // Kiểm tra xem hồ sơ y tế có tồn tại không, nếu không, ném ra exception hoặc trả về thông báo lỗi
        if (!$prescription) {
            abort(404, 'Đơn thuốc không tìm thấy.');
        }

        // Lấy tất cả medications từ medicationRepository
        $medications = $this->medicationRepository->all();

        $prescribedMedications = [];
        if ($prescription) {
            $prescribedMedications = $prescription->medications->map(function ($medication) {
                return [
                    'id'                  => $medication->id,
                    'name'                => $medication->name,
                    'unit'                => $medication->unit,
                    'quantity'            => $medication->pivot->quantity,
                    'dosage_instructions' => $medication->pivot->dosage_instructions,
                ];
            });
        } else {
            // Xử lý trường hợp không tìm thấy prescription
            abort(404, 'Đơn thuốc không tìm thấy.');
        }

        // Trả về view edit với dữ liệu prescription, medications và prescribedMedications
        return view('backend.prescriptions.edit', compact('prescription', 'medications', 'prescribedMedications'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PrescriptionRequest $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(PrescriptionRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction(); // Bắt đầu transaction để đảm bảo tính toàn vẹn của dữ liệu
        try {
            // Tìm đơn thuốc hiện tại
            $prescription = $this->repository->find($id);

            // Kiểm tra xem đơn thuốc có tồn tại không
            if (!$prescription) {
                return response()->json([
                                            'status' => 'error',
                                            'message' => 'Đơn thuốc không tìm thấy.',
                                        ], 404);
            }

            // Lấy dữ liệu cập nhật từ request
            $prescriptionData = $request->only(['title']);
            $prescription->update($prescriptionData); // Cập nhật thông tin đơn thuốc

            // Lấy thông tin medications từ request
            $medications = $request->input('medications', []);

            if (empty($medications)) {
                DB::rollBack(); // Rollback nếu không có thuốc nào được thêm
                return response()->json([
                                            'status' => 'error',
                                            'message' => 'Vui lòng thêm thuốc!',
                                        ], 422);
            }

            // Tạo mảng dữ liệu để insert vào bảng pivot
            $prescriptionItems = [];
            foreach ($medications as $medicationId => $medicationData) {
                $prescriptionItems[$medicationId] = [
                    'quantity'            => (int)$medicationData['quantity'],
                    'dosage_instructions' => $medicationData['dosage_instructions'],
                ];
            }

            // Cập nhật mối quan hệ với thuốc trong bảng pivot
            // Sử dụng sync để thêm hoặc cập nhật các mục trong bảng pivot
            $prescription->medications()->sync($prescriptionItems);

            DB::commit(); // Commit transaction nếu thành công

            return response()->json([
                                        'status'  => 'success',
                                        'message' => 'Đơn thuốc đã được cập nhật thành công.',
                                        'data'    => $prescription->fresh()->toArray(), // Lấy dữ liệu mới nhất
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
                                        'message' => 'Xảy ra lỗi khi cập nhật đơn thuốc: ' . $e->getMessage(),
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
        DB::beginTransaction();

        try {
            $prescription = $this->repository->find($id);

            if ($prescription) {
                // Xóa các bản ghi trong bảng pivot medication_prescription
                $prescription->medications()->detach(); // Phải đảm bảo quan hệ medications đã được định nghĩa

                // Xóa prescription
                $this->repository->delete($id);

                DB::commit(); // Commit transaction
                return response()->json(['message' => 'Đơn thuốc đã được xóa thành công.'], 200);
            } else {
                return response()->json(['message' => 'Đơn thuốc không tìm thấy.'], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback nếu có lỗi
            return response()->json(['message' => 'Có lỗi xảy ra khi xóa đơn thuốc.'], 500);
        }
    }


    /**
     * Show the form for adding the specified resource.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Foundation\Application|View|Factory|Application
    {

        $medications = $this->medicationRepository->all();
        return view('backend.prescriptions.create', compact('medications'));
    }
}
