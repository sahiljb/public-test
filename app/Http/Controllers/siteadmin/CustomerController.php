<?php

namespace App\Http\Controllers\siteadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $roleName)
    {
        $title = 'User List';
        $breadcrumb = ucfirst($roleName);

        if($roleName == 'admin'){
            return redirect()->back();
        }

        $start = $request->input('start', 0);
        $length = $request->input('length', getPaginationCount());
        $searchValue = $request->input('search.value', null);

        $userList = new User();
        $userListQry = $userList->getUserRoleList($roleName, $length, $start, $searchValue);


        if ($request->ajax()) {

            return response()->json([
                'data' => $userListQry['data'],
                'draw' => $request->get('draw'),
                'recordsTotal' => $userListQry['total'],
                'recordsFiltered' => $userListQry['total'],
            ]);
        }

        return view('pages.users.list', compact('title', 'breadcrumb', 'userListQry', 'roleName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create User';
        $breadcrumb = 'User LIst';
        $breadcrumb_url = route('customer.list',['staff']);
        return view('pages.users.create', compact('title', 'breadcrumb','breadcrumb_url'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Define validation rules
        $rules = [
            'uname' => 'required|string|max:255',
            'uemail' => 'required|email|max:255|unique:users,email',
            'uphone' => 'required|string|max:20|unique:users,phone',
            'upassword' => 'required|min:4|max:20',
            'ustatus' => 'required|in:active,deactive',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
        ];

        // Define custom validation messages
        $messages = [
            'uname.required' => 'The name field is required.',
            'uname.string' => 'The name must be a string.',
            'uname.max' => 'The name may not be greater than 255 characters.',
            'uemail.required' => 'The email field is required.',
            'uemail.email' => 'The email must be a valid email address.',
            'uemail.max' => 'The email may not be greater than 255 characters.',
            'uemail.unique' => 'The email has already been taken.',
            'uphone.required' => 'The phone field is required.',
            'uphone.string' => 'The phone must be a string.',
            'uphone.max' => 'The phone may not be greater than 20 characters.',
            'uphone.unique' => 'The phone has already been taken.',
            'upassword.required' => 'The password field is required.',
            'upassword.min' => 'The password may not be less than 4 characters.',
            'upassword.max' => 'The password may not be greater than 20 characters.',
            'ustatus.required' => 'The status field is required.',
            'ustatus.in' => 'The selected status is invalid.',
            'profile_photo.image' => 'The profile photo must be an image.',
            'profile_photo.mimes' => 'The profile photo must be a file of type: jpeg, png, jpg, gif.',
            'profile_photo.max' => 'The profile photo may not be greater than 8 MB.',
        ];

        // Validate the incoming request data with custom messages
        $validatedData = $request->validate($rules, $messages);

        $filePath = '';
        // Handle file upload if present
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filePath = $file->store('profile_photos', 'public'); 
        }

        $system_ip = getHostByName(getHostName());

        $user = User::create([
            'name' => $request->uname,
            'email' => $request->uemail,
            'phone'=>$request->uphone,
            'password' => Hash::make($request->upassword),
            'status' => $request->ustatus,
            'ip_addr'=> $system_ip,
            'profile' => $filePath,
        ]);

        $user->assignRole('staff'); 

        // Redirect or return a response
        return redirect()->route('customer.list',['staff'])->with('success', 'User created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Update User';
        $breadcrumb = 'User LIst';
        $breadcrumb_url = route('customer.list',['staff']);
        $userDetail = User::findOrFail($id);
        return view('pages.users.edit', compact('title', 'breadcrumb','breadcrumb_url','userDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // Define validation rules
        $rules = [
            'uname' => 'required|string|max:255',
            'uemail' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($id)
            ],
            'uphone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($id)
            ],
            'upassword' => 'nullable|min:4|max:20',
            'ustatus' => 'required|in:active,deactive',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
        ];

        // Define custom validation messages
        $messages = [
            'uname.required' => 'The name field is required.',
            'uname.string' => 'The name must be a string.',
            'uname.max' => 'The name may not be greater than 255 characters.',
            'uemail.required' => 'The email field is required.',
            'uemail.email' => 'The email must be a valid email address.',
            'uemail.max' => 'The email may not be greater than 255 characters.',
            'uemail.unique' => 'The email has already been taken.',
            'uphone.required' => 'The phone field is required.',
            'uphone.string' => 'The phone must be a string.',
            'uphone.max' => 'The phone may not be greater than 20 characters.',
            'uphone.unique' => 'The phone has already been taken.',
            'upassword.required' => 'The password field is required.',
            'upassword.min' => 'The password may not be less than 4 characters.',
            'upassword.max' => 'The password may not be greater than 20 characters.',
            'ustatus.required' => 'The status field is required.',
            'ustatus.in' => 'The selected status is invalid.',
            'profile_photo.image' => 'The profile photo must be an image.',
            'profile_photo.mimes' => 'The profile photo must be a file of type: jpeg, png, jpg, gif.',
            'profile_photo.max' => 'The profile photo may not be greater than 8 MB.',
        ];

        // Validate the incoming request data with custom messages
        $validatedData = $request->validate($rules, $messages);

        $user = User::find($id);
        $filePath = $user->profile;
        // Handle file upload if present
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filePath = $file->store('profile_photos', 'public'); 
            if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                Storage::disk('public')->delete($user->profile);
            }
            
        }

        $system_ip = getHostByName(getHostName());

        $user = User::find($id);

       
        $user->update([
            'name' => $request->uname,
            'email' => $request->uemail,
            'phone'=>$request->uphone,
            'status' => $request->ustatus,
            'ip_addr'=> $system_ip,
            'profile' => $filePath,
        ]);

        if(trim($request->upassword) != ''){
            $user->update([
                'password' => Hash::make($request->upassword),
            ]);
        }
       
        // Redirect or return a response
        return redirect()->route('customer.update',[$id])->with('success', 'User saved successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $user = User::findOrFail($id);
            if($user->profile != ''){
                Storage::disk('public')->delete($user->profile);
            }
            $user->delete();
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting user.']);
        }

    }
}
