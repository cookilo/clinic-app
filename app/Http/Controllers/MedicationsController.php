<?php

namespace App\Http\Controllers;

use App\Exports\MedicationsExport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\MedicationRequest;
use App\Repositories\MedicationRepository;

/**
 * Class MedicationsController.
 *
 * @package namespace App\Http\Controllers;
 */
class MedicationsController extends Controller
{
    /**
     * @var MedicationRepository
     */
    protected MedicationRepository $repository;

    /**
     * MedicationsController constructor.
     *
     * @param MedicationRepository $repository
     */
    public function __construct(MedicationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function index(): \Illuminate\Foundation\Application|View|Factory|Application
    {

        $searchParams = request()->only(['name', 'status']);
        $medications = $this->repository->search($searchParams)->orderBy('created_at', 'desc')->paginate(20)->appends(request()->query());

        return view('backend.medications.index', compact('medications'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MedicationRequest $request
     *
     * @return JsonResponse
     */
    public function store(MedicationRequest $request): JsonResponse
    {

        try {

            $medication = $this->repository->create($request->all());

            return response()->json([
                                        'status' => 'success',
                                        'message' => 'Thuốc đã được tạo thành công.',
                                        'data' => $medication->toArray()
                                    ], 200);

        } catch (ValidatorException $e) {

            return response()->json([
                                        'status' => 'error',
                                        'errors' => $e->getMessageBag()
                                    ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $patient = $this->repository->find($id);
        if (!$patient) {
            return response()->json(['message' => 'Thuốc không tồn tại'], 404);
        }

        return response()->json($patient);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $medication = $this->repository->find($id);
        if (!$medication) {
            return response()->json(['message' => 'Thuốc không tồn tại'], 404);
        }

        return response()->json($medication);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MedicationRequest $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(MedicationRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->all();
            $data['print_invoice'] = $data['print_invoice'] ?? 0;

            $this->repository->update($data, $id);
            return response()->json(['message' => 'Cập nhật thông tin thành công!'], 200);
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
        $medication = $this->repository->find($id);
        if ($medication) {
            $this->repository->delete($id);
            return response()->json(['message' => 'Thuốc đã được xóa thành công.'], 200);
        } else {
            return response()->json(['message' => 'Thuốc không tìm thấy.'], 404);
        }
    }

    public function export(Request $request)
    {
        $date = now()->format('d-m-Y-H-i-s');
        return Excel::download(
            new MedicationsExport($request->all()),
            "medications_{$date}.xlsx"
        );
    }
}
