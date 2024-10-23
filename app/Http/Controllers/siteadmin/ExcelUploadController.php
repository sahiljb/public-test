<?php

namespace App\Http\Controllers\siteadmin;

use App\Http\Controllers\Controller;
use App\Models\Leads;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
//use Spatie\SimpleExcel\SimpleExcelReader;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LeadsImport; // Create this import class
use App\Models\DuplicateLead;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ExcelUploadController extends Controller
{

    public function index(Request $request){
        $title = 'Dashboard';
        $breadcrumb = 'Cold Leads';

        $start = $request->input('start', 0);
        $length = $request->input('length', getPaginationCount());
        $searchValue = $request->input('search.value', null);

        $userList = new Leads();
        $leadListQry = $userList->getLeadDetails($length, $start, $searchValue);

        if ($request->ajax()) {
            return response()->json([
                'data' => $leadListQry['data'],
                'draw' => $request->get('draw'),
                'recordsTotal' => $leadListQry['total'],
                'recordsFiltered' => $leadListQry['total'],
            ]);
        }

        $routeName = route('leads.list');

        return view('pages.leads.list', compact('title', 'breadcrumb', 'leadListQry','routeName'));
    }

    

    public function duplicateLeads(Request $request){
        $title = 'Dashboard';
        $breadcrumb = 'Leads';

        $start = $request->input('start', 0);
        $length = $request->input('length', getPaginationCount());
        $searchValue = $request->input('search.value', null);

        $userList = new Leads();
        $leadListQry = $userList->getDuplicateLeadDetails($length, $start, $searchValue);

        if ($request->ajax()) {
            return response()->json([
                'data' => $leadListQry['data'],
                'draw' => $request->get('draw'),
                'recordsTotal' => $leadListQry['total'],
                'recordsFiltered' => $leadListQry['total'],
            ]);
        }

        $routeName = route('leads.duplicate');

        return view('pages.leads.duplicate', compact('title', 'breadcrumb', 'leadListQry','routeName'));
    }

    public function upload()
    {
        $title = 'Upload Leads';
        $breadcrumb = 'Leads List';
        $breadcrumb_url = route('leads.list');
        return view('pages.leads.upload', compact('title', 'breadcrumb','breadcrumb_url'));
    }

    public function create()
    {
        $title = 'Create Leads';
        $breadcrumb = 'Leads List';
        $breadcrumb_url = route('leads.list');
        return view('pages.leads.create', compact('title', 'breadcrumb','breadcrumb_url'));
    }

    public function store(Request $request)
    {
        try {
            // Validator for validation and custom error messages
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:xlsx,xls',
            ]);

            // Return validation errors if they exist
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // If validation passes, process the file
            $file = $request->file('file');

            if ($file) {
                // Import the data using Maatwebsite Excel
                Excel::import(new LeadsImport, $file);

                return response()->json(['message' => 'File uploaded and processed successfully.']);
            } else {
                return response()->json(['message' => 'No file uploaded.'], 400);
            }

        } catch (Exception $e) {
            $message = $e->getMessage();
            return response()->json(['message' => $message], 500);
        }
    }

    public function edit($id)
    {
        $title = 'Edit Leads';
        $breadcrumb = 'Leads List';
        $breadcrumb_url = route('leads.list');
        $editQuery = Leads::find($id);
        return view('pages.leads.edit', compact('title', 'breadcrumb','breadcrumb_url','editQuery'));
    }

    public function singleStore(Request $request)
    {

        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:leads,phone',
            'city' => 'required|string|max:255',
            'priority' => 'required|in:normal,moderate,hot',
        ];

        // Define custom validation messages
        $messages = [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'city.required' => 'The city field is required.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city may not be greater than 255 characters.',
            'phone.required' => 'The phone field is required.',
            'phone.string' => 'The phone must be a string.',
            'phone.max' => 'The phone may not be greater than 20 characters.',
            'phone.unique' => 'The phone has already been taken.',
            'priority.required' => 'The priority field is required.',
            'priority.in' => 'The priority field is must be normal,moderate or hot.',
        ];

        // Validate the incoming request data with custom messages
        $validatedData = $request->validate($rules, $messages);

        $itemQry = new Leads();
       
        $itemQry->create([
            'name' => $request->name,
            'city' => $request->city,
            'phone'=>$request->phone,
            'priority'=>$request->priority,
        ]);

        // Redirect or return a response
        return redirect()->route('leads.create')->with('success', 'User saved successfully.');

    }

    public function update(Request $request, string $id)
    {

        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:20', 'unique:leads,phone,' . $id],
            'city' => 'required|string|max:255',
            'priority' => 'required|in:normal,moderate,hot',
            
        ];

        // Define custom validation messages
        $messages = [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'city.required' => 'The city field is required.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city may not be greater than 255 characters.',
            'phone.required' => 'The phone field is required.',
            'phone.string' => 'The phone must be a string.',
            'phone.max' => 'The phone may not be greater than 20 characters.',
            'phone.unique' => 'The phone has already been taken.',
            'priority.required' => 'The priority field is required.',
            'priority.in' => 'The priority field is must be normal,moderate or hot.',
        ];

        // Validate the incoming request data with custom messages
        $validatedData = $request->validate($rules, $messages);

        $itemQry = Leads::find($id);
       
        $itemQry->update([
            'name' => $request->name,
            'city' => $request->city,
            'phone'=>$request->phone,
            'priority'=>$request->priority,
        ]);

        // Redirect or return a response
        return redirect()->route('leads.edit',[$id])->with('success', 'User saved successfully.');

    }

    public function destroy(string $id)
    {

        try{
            $itemQry = Leads::findOrFail($id);
            $itemQry->delete();
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting user.']);
        }

    }

    public function duplicateDestroy(string $id)
    {

        try{
            $itemQry = DuplicateLead::findOrFail($id);
            $itemQry->delete();
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting user.']);
        }

    }

    
}
