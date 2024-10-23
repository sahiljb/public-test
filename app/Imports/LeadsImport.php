<?php

namespace App\Imports;

use App\Models\DuplicateLead;
use App\Models\Leads;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class LeadsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model (array $row)
    {
        if (trim(strtolower($row[0])) == 'name' || trim(strtolower($row[0])) == 'phone' || trim(strtolower($row[0])) == 'city') {
            return null;
        }

        // Check if phone already exists in Leads table
        $existingLead = Leads::where('phone', $row[1])->first();

        if ($existingLead) {
            // If the phone exists, add the entry to the DuplicateLead table
            return new DuplicateLead([
                'name'  => $row[0] ?? '',
                'phone' => $row[1] ?? '',
                'city'  => $row[2] ?? '',
            ]);
        }

        // If the phone doesn't exist, add the entry to the Leads table
        return new Leads([
            'name'  => $row[0] ?? '',
            'phone' => $row[1] ?? '',
            'city'  => $row[2] ?? '',
        ]);
    }
}
