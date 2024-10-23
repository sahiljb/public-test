<?php

namespace App\Http\Controllers\siteadmin;

use App\Http\Controllers\Controller;
use App\Models\Leads;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AssignLeadsController extends Controller
{
    public function assignedLeads(Request $request){
        $title = 'Dashboard';
        $breadcrumb = 'Assigned Leads';

        $start = $request->input('start', 0);
        $length = $request->input('length', getPaginationCount());
        $searchValue = $request->input('search.value', null);

        $userList = new Leads();
        $leadListQry = $userList->getAssignedLeadDetails($length, $start, $searchValue);

        if ($request->ajax()) {
            return response()->json([
                'data' => $leadListQry['data'],
                'draw' => $request->get('draw'),
                'recordsTotal' => $leadListQry['total'],
                'recordsFiltered' => $leadListQry['total'],
            ]);
        }

        $routeName = route('leads.assigned');

        return view('pages.leads.assigned', compact('title', 'breadcrumb', 'leadListQry','routeName'));
    }

    public function dragLeads(Request $request){
        $title = 'Assign Leads';
        $breadcrumb = 'Leads List';
        $breadcrumb_url = route('leads.list');
        $allColdLeads = Leads::whereNull('assigned_userid')->orderBy('priority','desc')->get();

        $roleName = 'staff';
        $role = Role::where('name', $roleName)->first();
        $query = $role->users();
        $query->where('status','active');
        $allEmployee = $query->orderBy('users.id', 'desc')->get();
        return view('pages.leads.drag.index', compact('title', 'breadcrumb','breadcrumb_url','allColdLeads','allEmployee'));
    }

    public function processAssign(Request $request)
    {

        try{

            // Validate the input
            $request->validate([
                'employee_id' => 'required|exists:users,id',
                'lead_ids' => 'required|array',
                'lead_ids.*' => 'exists:leads,id',
            ]);

            // Assign the selected leads to the selected employee
            Leads::whereIn('id', $request->lead_ids)->update(['assigned_userid' => $request->employee_id]);

            // Redirect back with a success message
            return redirect()->route('leads.assign-lead')->with('success', 'Leads assigned successfully.');

        }catch(Exception $ex){
            return redirect()->route('leads.assign-lead')->with('error', $ex->getMessage())->withInput($request->all());
        }
        
    }




}
