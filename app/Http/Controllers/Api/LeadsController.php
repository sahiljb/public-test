<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leads;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeadsController extends Controller
{
    public function index() {
        try {
            $allData = Leads::where('assigned_userid', Auth::id())
            ->whereNotNull('assigned_userid')
            ->where('status', 'cold')
            ->orderBy('priority', 'desc')
            ->get()
            ->map(function($lead) {
                // Set reminder_date to empty string if it's null
                $lead->reminder_date = $lead->reminder_date ?? '';
                return $lead;
            });
            

            return response()->json(['status' => true,'data' => $allData], 200);
        } catch (Exception $e) {
            // Handle any unexpected exceptions
            return response()->json(['status' => false,'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'lead_id' => [
                'required',
                'exists:leads,id', // Ensure lead_id exists in the leads table
                function ($attribute, $value, $fail) {
                    // Check if the assigned_userid matches the logged-in user
                    $lead = Leads::find($value);
                    if ($lead && $lead->assigned_userid != Auth::id()) {
                        $fail('You are not authorized to update this lead.');
                    }
                },
            ],
            'proposal_status' => 'required|in:interested,not_interested,phone_switch_off,remind_me',
            'reminder_date' => 'nullable|date|required_if:proposal_status,remind_me|date_format:Y-m-d H:i|after_or_equal:today',
            'status' => 'required|in:cold,calling,called', // Ensure status is valid
        ], [
            'lead_id.required' => 'Lead ID is required.',
            'lead_id.exists' => 'The selected lead ID is invalid.',
            'proposal_status.required' => 'Proposal status is required.',
            'proposal_status.in' => 'The selected proposal status is invalid.',
            'reminder_date.required_if' => 'Reminder date is required when proposal status is "remind me".',
            'reminder_date.date' => 'Reminder date must be a valid date.',
            'reminder_date.date_format' => 'Reminder date must be in the format Y-m-d H:i.',
            'reminder_date.after_or_equal' => 'Reminder date must be today or a future date.',
            'status.required' => 'Status is required.',
            'status.in' => 'The selected status is invalid.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ]);
        }

        // Proceed with updating the lead details
        $leadDetail = Leads::where('id', $request->lead_id)->first();
        $leadDetail->proposal_status = $request->proposal_status;
        $leadDetail->reminder_date = $request->reminder_date;
        $leadDetail->status = $request->status;
        $leadDetail->save();

        return response()->json([
            'status' => true,
            'message' => 'Lead updated successfully',
            'data' => $leadDetail,
        ]);
    }

    


}
