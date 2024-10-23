<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validate request inputs
            $credentials = $request->only('email', 'password');

            // Attempt to authenticate the user
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                    'error' => 'Invalid credentials'
                ], 401);
            }

            // Fetch authenticated user
            $user = Auth::user();

            if($user->status == 'deactive'){

                Auth::logout();
                return response()->json([
                    'status' => false,
                    'message' => 'Your account deactivated',
                ], 500);
            }

            // Generate JWT token for the authenticated user
            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 500);
            }
            $getData = new User();
            $userDetails = $getData->getUsersDetails($user->id);

            // Return success response with token and user details
            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'data' => $userDetails,
                'token_type' => 'bearer',
                'access_token' => $token
            ], 200);

        } catch (Exception $e) {
            // Handle any unexpected exceptions
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function logout()
    {
        try {
            // Invalidate the token to ensure it cannot be used after logout
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out',
            ], 200);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token is already invalid or cannot be found',
                'error' => $e->getMessage()
            ], 401); // Use 401 for invalid token issues
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while logging out',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {


            $id = Auth::id();

            // Define validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($id)
                ],
                'phone' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('users', 'phone')->ignore($id)
                ],
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
            ];

            // Define custom validation messages
            $messages = [
                'name.required' => 'The name field is required.',
                'name.string' => 'The name must be a string.',
                'name.max' => 'The name may not be greater than 255 characters.',
                'email.required' => 'The email field is required.',
                'email.email' => 'The email must be a valid email address.',
                'email.max' => 'The email may not be greater than 255 characters.',
                'email.unique' => 'The email has already been taken.',
                'phone.required' => 'The phone field is required.',
                'phone.string' => 'The phone must be a string.',
                'phone.max' => 'The phone may not be greater than 20 characters.',
                'phone.unique' => 'The phone has already been taken.',
                'profile_photo.image' => 'The profile photo must be an image.',
                'profile_photo.mimes' => 'The profile photo must be a file of type: jpeg, png, jpg, gif.',
                'profile_photo.max' => 'The profile photo may not be greater than 8 MB.',
            ]; 

            // Validate the request
            $validator = Validator::make($request->all(), $rules, $messages);

            // Handle validation failure
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // If validation passes, update the user's profile
            $user = User::find(Auth::id());
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;

            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $filePath = $file->store('profile_photos', 'public'); 
                if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                    Storage::disk('public')->delete($user->profile);
                }
                $user->profile = $filePath;
                
            }

            // Save updated user details
            $user->save();

            $getData = new User();
            $userDetails = $getData->getUsersDetails($user->id);

            // Return a success response
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => $userDetails
            ], 200);

        } catch (Exception $e) {
            // Handle any unexpected exceptions
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request){


        try{

            $rules = [
                'password' => 'required|min:4|max:20'
            ];
    
            // Define custom validation messages
            $messages = [
                'password.required' => 'The password field is required.',
                'password.min' => 'The password may not be less than 4 characters.',
                'password.max' => 'The password may not be greater than 20 characters.'
            ];

            // Validate the request
            $validator = Validator::make($request->all(), $rules, $messages);

            // Handle validation failure
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::find(Auth::id());
            $user->update([
                'password' => Hash::make($request->upassword),
            ]);

            $getData = new User();
            $userDetails = $getData->getUsersDetails($user->id);

            // Return a success response
            return response()->json([
                'status' => true,
                'message' => 'Password updated successfully',
                'data' => $userDetails
            ], 200);


        } catch (Exception $e) {
            // Handle any unexpected exceptions
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }

    }


    
}
