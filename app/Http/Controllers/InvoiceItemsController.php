<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\InvoiceItemRequest;
use App\Http\Requests\InvoiceItemUpdateRequest;
use App\Repositories\InvoiceItemRepository;
use App\Validators\InvoiceItemValidator;

/**
 * Class InvoiceItemsController.
 *
 * @package namespace App\Http\Controllers;
 */
class InvoiceItemsController extends Controller
{
    /**
     * @var InvoiceItemRepository
     */
    protected InvoiceItemRepository $repository;

    /**
     * @var InvoiceItemValidator
     */
    protected InvoiceItemValidator $validator;

    /**
     * InvoiceItemsController constructor.
     *
     * @param InvoiceItemRepository $repository
     * @param InvoiceItemValidator $validator
     */
    public function __construct(InvoiceItemRepository $repository, InvoiceItemValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $invoiceItems = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $invoiceItems,
            ]);
        }

        return view('invoiceItems.index', compact('invoiceItems'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  InvoiceItemRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(InvoiceItemRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $invoiceItem = $this->repository->create($request->all());

            $response = [
                'message' => 'InvoiceItem created.',
                'data'    => $invoiceItem->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoiceItem = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $invoiceItem,
            ]);
        }

        return view('invoiceItems.show', compact('invoiceItem'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoiceItem = $this->repository->find($id);

        return view('invoiceItems.edit', compact('invoiceItem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  InvoiceItemUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(InvoiceItemUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $invoiceItem = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'InvoiceItem updated.',
                'data'    => $invoiceItem->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'InvoiceItem deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'InvoiceItem deleted.');
    }
}
