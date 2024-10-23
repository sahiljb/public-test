<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'loan_type',
        'type_details',
        'profile_details',
        'common_question',
        'reference_detail'
    ];

}
