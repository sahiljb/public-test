<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leads;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Determine the validation rules based on the loan type
        $rules = [
            'lead_id' => [
                'required',
                'exists:leads,id', // Ensure lead_id exists in the leads table
                function ($attribute, $value, $fail) {
                    // Check if the assigned_userid matches the logged-in user
                    $lead = Leads::find($value);
                    if ($lead && $lead->assigned_userid != Auth::id()) {
                        $fail('You are not authorized to create a proposal for this lead.');
                    }
                },
            ],
            'loan_type' => 'required|string|in:new_car,old_car,home_loan',
            // Validation for profile_details
            'salaried' => 'required|string|in:Yes,No',
            'govt_pvt' => 'required|string',
            'company' => 'required|string',
            'designation' => 'required|string',
            'salary_amt' => 'required|numeric',
            'cash_bank' => 'required|string|in:cash,bank',
            'salaryslip_formsixteen' => 'required|string|in:Yes,No',
            'business' => 'required|string|in:Yes,No',
            'type_of_biz' => 'required|string',
            'itr' => 'required|string|in:Yes,No',
            'gst_biz_proof' => 'required|string|in:Yes,No',
            // Validation for common_question
            'purpose_of_loan' => 'required|string',
            'any_other_asset' => 'required|string',
            'any_other_loan' => 'required|string',
            'monthly_income' => 'required|numeric',
            // Validation for reference_detail (optional)
            'ref_email' => 'nullable|email',
            'ref_phone' => 'nullable|string',
            'ref_name' => 'nullable|string',
        ];

        // Add validation rules based on loan_type to store in type_details
        if ($request->loan_type == 'new_car') {
            $rules['model'] = 'required|string';
        } elseif ($request->loan_type == 'old_car') {
            $rules['model'] = 'required|string';
            $rules['mfg_year'] = 'required|integer';
            $rules['owner'] = 'required|string';
            $rules['passing'] = 'required|string';
            $rules['financer'] = 'required|string';
            $rules['emi_paid'] = 'required|numeric';
            $rules['emi_pending'] = 'required|numeric';
            $rules['existing_loan_amt'] = 'required|numeric';
            $rules['loan_requirement'] = 'required|numeric';
        } elseif ($request->loan_type == 'home_loan') {
            $rules['types_loan'] = 'required|in:new_purchase,mortgage';
            $rules['market_value'] = 'required|numeric';
            $rules['agreement_amt'] = 'required|numeric';
            $rules['financer_name'] = 'required|string';
            $rules['loan_required'] = 'required|numeric';
        }

        // Validate the incoming request using the Validator facade
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ]); 
        }

        // Store validated data in arrays and convert them to JSON
        $profileDetails = [
            'salaried' => $request->salaried,
            'govt_pvt' => $request->govt_pvt,
            'company' => $request->company,
            'designation' => $request->designation,
            'salary_amt' => $request->salary_amt,
            'cash_bank' => $request->cash_bank,
            'salaryslip_formsixteen' => $request->salaryslip_formsixteen,
            'business' => $request->business,
            'type_of_biz' => $request->type_of_biz,
            'itr' => $request->itr,
            'gst_biz_proof' => $request->gst_biz_proof,
        ];

        $commonQuestion = [
            'purpose_of_loan' => $request->purpose_of_loan,
            'any_other_asset' => $request->any_other_asset,
            'any_other_loan' => $request->any_other_loan,
            'monthly_income' => $request->monthly_income,
        ];

        $referenceDetail = [
            'email' => ($request->ref_email && $request->ref_email != '') ? $request->ref_email : '',
            'phone' => ($request->ref_phone && $request->ref_phone != '') ? $request->ref_phone : '',
            'name' => ($request->ref_name && $request->ref_name != '') ? $request->ref_name : '',
        ];

        $typeDetails = [];
        if ($request->loan_type == 'new_car') {
            $typeDetails = [
                'model' => $request->model,
            ];
        } elseif ($request->loan_type == 'old_car') {
            $typeDetails = [
                'model' => $request->model,
                'mfg_year' => $request->mfg_year,
                'owner' => $request->owner,
                'passing' => $request->passing,
                'financer' => $request->financer,
                'emi_paid' => $request->emi_paid,
                'emi_pending' => $request->emi_pending,
                'existing_loan_amt' => $request->existing_loan_amt,
                'loan_requirement' => $request->loan_requirement,
            ];
        } elseif ($request->loan_type == 'home_loan') {
            $typeDetails = [
                'types_loan' => $request->types_loan,
                'market_value' => $request->market_value,
                'agreement_amt' => $request->agreement_amt,
                'financer_name' => $request->financer_name,
                'loan_required' => $request->loan_required,
            ];
        }

        $typeDetailsJson = json_encode($typeDetails);
        $profileDetailsJson = json_encode($profileDetails);
        $commonQuestionJson = json_encode($commonQuestion);
        $referenceDetailJson = json_encode($referenceDetail);

        // Store the data in the database
        $proposal = Proposal::create([
            'lead_id' => $request->lead_id,
            'loan_type' => $request->loan_type,
            'type_details' => $typeDetailsJson, // Store as JSON
            'profile_details' => $profileDetailsJson, // Store as JSON
            'common_question' => $commonQuestionJson, // Store as JSON
            'reference_detail' => $referenceDetailJson, // Store as JSON
        ]);

        $responseData = [
            'lead_id' => $proposal->lead_id,
            'loan_type' => $proposal->loan_type,
            'type_details' => json_decode($proposal->type_details, true), // Decode JSON to array
            'profile_details' => json_decode($proposal->profile_details, true), // Decode JSON to array
            'common_question' => json_decode($proposal->common_question, true), // Decode JSON to array
            'reference_detail' => json_decode($proposal->reference_detail, true), // Decode JSON to array
            'updated_at' => $proposal->updated_at,
            'created_at' => $proposal->created_at,
            'id' => $proposal->id,
        ];


        // Return a success response
        return response()->json([
            'status' => true,
            'message' => 'Proposal saved successfully',
            'data' => $responseData
        ], 201);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
