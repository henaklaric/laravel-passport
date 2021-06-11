<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        Log::info("Get users request received from user: " . $request->user()->name . " (ID: " . $request->user()->id . ")");

        $users = User::get();

        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        Log::info("Store user request received from user: " . $request->user()->name . " (ID: " . $request->user()->id . ")");

        $input = $request->input();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        return response()->json([
            "message" => "User stored successfully." ,
            "user" => $user
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        Log::info("Get user request received from user: " . $request->user()->name . " (ID: " . $request->user()->id . ")");

        $user = User::find($id);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, $id)
    {
        Log::info("Update user request received from user: " . $request->user()->name . " (ID: " . $request->user()->id . ")");

        $user = User::find($id);

        $input = $request->input();

        if ($request->filled('password')) {
            $input['password'] = bcrypt($request->input('password'));
        }

        $user->update($input);

        return response()->json([
            "message" => "User updated successfully." ,
            "user" => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        if (!Auth::user()->is_admin) {
            return response()->json([
                "message" => "Action forbidden."
            ], 400);
        }

        Log::info("Delete user request received from user: " . $request->user()->name . " (ID: " . $request->user()->id . ")");

        $user = User::find($id);

        $user->delete();

        return response()->json([
            "message" => "User deleted successfully."
        ]);
    }
}
