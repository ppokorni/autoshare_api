<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {

    //login function that uses Sanctum to create a token, returns a json response
    public function login(Request $request) {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);

            $credentials = request(['email', 'password']);

            //check if the credentials are correct
            if (auth()->attempt($credentials)) {

                /** @var User $user */
                $user = auth()->user();
                $token = $user->createToken('authToken');
                return response()->json([
                    'user' => new UserResource($user),
                    'token' => $token->plainTextToken
                ], 200);
            } else {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        }  catch (\Throwable $th) {
            report($th);
            return response()->json(['error' => 'There was an error'], 500);
        }
    }

    //register function that uses Sanctum to create a token, returns a json response
    public function register(Request $request) {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
                'name' => 'nullable',
                'surname' => 'nullable',
            ]);

            $credentials = request(['email', 'password']);
            //check if user exists
            if (User::where('email', $credentials['email'])->count()) {
                return response()->json(['error' => 'User already exists'], 401);
            }

            //create user
            DB::beginTransaction();
            $user = User::create([
                'email' => $credentials['email'],
                'name' => $request->name ?? "",
                'surname' => $request->surname ?? "",
                'password' => bcrypt($credentials['password'])
            ]);

            $token = $user->createToken('authToken');
            DB::commit();
            return response()->json([
                'user' => new UserResource($user),
                'token' => $token->plainTextToken
            ], 200);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th);
            return response()->json(['error' => 'There was an error'], 500);
        }
    }

}
