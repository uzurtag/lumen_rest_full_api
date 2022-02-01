<?php

namespace App\Http\Controllers;

use App\Models\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /** @var UserRepository */
    protected $repository;

    /**
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->repository = $user;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = $this->repository->find(["email" => $request['email']]);
        $user = array_shift($user);

        try {
            if (Hash::check($request['password'], $user->password)) {
                $token = base64_encode(Str::random(40));

                $this->repository->updateToken(["token" => $token, "email" => $user->email]);

                return response()->json(['status' => 'success', 'token' => $token]);
            } else {
                return response()->json(['status' => 'fail', 'message' => 'Email or password was wrong'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'message' => 'User was not found'], 401);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        try {
            $this->repository->store(
                [
                    $request['email'],
                    Hash::make($request['password']),
                    $request['first_name'],
                    $request['last_name'],
                    $request['phone']
                ]
            );

            return response()->json(['status' => 'success', 'message' => 'User was created'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User Registration Failed!', 'exception' => $e->getMessage()], 400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function initializeResetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string'
        ]);

        try {
            $user = $this->repository->find(["email" => $request['email']]);
            $user = array_shift($user);

            if (!empty($user)) {
                $hash = Hash::make(Str::random(30));

                $this->repository->updateHash(["email" => $user->email, "password_hash" => Hash::make($hash)]);

                return response()->json(['status' => 'success', 'message' => "Hash for reset password {$hash}"]);
            } else {
                return response()->json(['message' => 'User not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'User not found', 'err'=>$e->getMessage()], 404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function restore(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'hash' => 'required|string'
        ]);

        try {
            $user = $this->repository->find(["email" => $request['email']]);
            $user = array_shift($user);

            if (Hash::check($request['hash'], $user->password_hash)) {
                $newPassword = Str::random(10);

                $this->repository->restore(["email" => $user->email, "password" => Hash::make($newPassword)]);

                return response()->json(['status' => 'success', 'message' => "Password was reset and set to {$newPassword}"]);
            } else {
                return response()->json(['status' => 'fail', 'message' => "Hash was wrong"]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
