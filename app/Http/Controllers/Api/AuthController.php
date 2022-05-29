<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Hash;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{

      /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token =auth()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

      /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

        /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
       /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $users = User::all();
       return response()->json([
           'message' => 'Display listing of the resource',
           'success' => true,
           'data' => $users,
       ]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator =Validator::make($request->all(), [
            'name'=>'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3',
       ]);

       if($validator->fails()){
        return response()->json([
            "error" => 'validation_error',
            "message" => $validator->errors(),
        ], 422);
    }

    try{
    $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        return response()->json([
            "success" => true,
            "message" => 'User save successfully',
            "data" => $user,
        ], 200);

    }catch(Exception $e){
        return response()->json([
            "error" => 'validation_error',
            "message" => $validator->errors(),
        ], 400);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $user =User::findOrFail($id);

             return response()->json([
                "success" => true,
                "message" => 'Display listing of the resource',
                "data" => $user,
            ], 200);

            }catch(Exception $e){
                return response()->json([
                    "success" =>false,
                    "message" => $validator->errors(),
                ], 400);
            }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator =Validator::make($request->all(), [
            'name'=>'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3',
       ]);

       if($validator->fails()){
        return response()->json([
            "error" => 'validation_error',
            "message" => $validator->errors(),
        ], 401);
    }

    try{
        $user =User::findOrFail($id);
        $user->name= $request->name;
        $user->email= $request->email;
        $user->password= Hash::make($request->password);
        $user->save();

        return response()->json([
            "success" => true,
            "message" => 'User Updated successfully',
            "data" => $user,
        ], 200);

    }catch(Exception $e){
        return response()->json([
            "error" => 'validation_error',
            "message" => $validator->errors(),
        ], 400);
    }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $user =User::findOrFail($id)->delete();

             return response()->json([
            "success" => true,
            "message" => 'User deleted successfully',
            ], 200);

            }catch(Exception $e){
                return response()->json([
                    "success" =>false,
                    "message" => 'wrong',
                ], 400);
            }
    }



}
