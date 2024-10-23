<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;

    protected $fillable = [
        'assigned_userid',
        'name',
        'phone',
        'city',
        'priority',
        'status',
        'proposal_status',
        'reminder_date'
    ];

    public function getLeadDetails($length, $start, $searchValue)
    {
        // Start with the base query
        $query = Leads::query(); 
        $query->whereNull('leads.assigned_userid');

        // Apply search filter if any search value is provided
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('phone', 'like', "%{$searchValue}%")
                    ->orWhere('city', 'like', "%{$searchValue}%");
            });
        }

        // Get the total number of records before pagination
        $total = $query->count();

        // Apply pagination and sorting
        $data = $query->skip($start)
                    ->take($length)
                    ->orderBy('priority', 'desc')
                    ->get();

        // Return the data along with total record count
        return [
            'data' => $data,
            'total' => $total
        ];
    }

    public function getAssignedLeadDetails($length, $start, $searchValue)
    {
        // Start with the base query
        $query = Leads::query(); 
        $query->whereNotNull('leads.assigned_userid');
        $query->join('users','users.id','=','leads.assigned_userid');

        // Apply search filter if any search value is provided
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('leads.name', 'like', "%{$searchValue}%")
                    ->orWhere('leads.phone', 'like', "%{$searchValue}%")
                    ->orWhere('leads.city', 'like', "%{$searchValue}%");
            });
        }

        $query->select('leads.*','users.name As userName','users.phone As userPhone','users.email As userEmail','users.profile');

        // Get the total number of records before pagination
        $total = $query->count();

        // Apply pagination and sorting
        $data = $query->skip($start)
                    ->take($length)
                    ->orderBy('priority', 'desc')
                    ->get();

        // Return the data along with total record count
        return [
            'data' => $data,
            'total' => $total
        ];
    }

    public function getDuplicateLeadDetails($length, $start, $searchValue)
    {
        // Start with the base query
        $query = DuplicateLead::query(); 

        // Apply search filter if any search value is provided
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('phone', 'like', "%{$searchValue}%")
                    ->orWhere('city', 'like', "%{$searchValue}%");
            });
        }

        // Get the total number of records before pagination
        $total = $query->count();

        // Apply pagination and sorting
        $data = $query->skip($start)
                    ->take($length)
                    ->orderBy('id', 'desc')
                    ->get();

        // Return the data along with total record count
        return [
            'data' => $data,
            'total' => $total
        ];
    }


}
