<?php

namespace App\Http\Controllers;

use App\Models\Repositories\Company\CompanyRepository;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /** @var CompanyRepository  */
    protected $repository;

    /**
     * @param CompanyRepository $company
     */
    public function __construct(CompanyRepository $company)
    {
        $this->repository = $company;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        try {
            $result = $this->repository->show(["id" => $request['userId']]);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getCode()], 404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
            'phone' => 'required',
        ]);


        try {
            $this->repository->store(
                [
                    $request['userId'],
                    $request['title'],
                    $request['description'],
                    $request['phone']
                ]
            );

            return response()->json(['status' => 'success', 'message' => 'Company was created'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getCode()], 409);
        }
    }
}
