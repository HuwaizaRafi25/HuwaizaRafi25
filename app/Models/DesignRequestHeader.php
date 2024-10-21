<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignRequestHeader extends Model
{
    use HasFactory;
    protected $fillable = [
        "customer_id",
        "supervisor_id",
        "status",
    ];

    public function customer(){
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function supervisor(){
        return $this->belongsTo(User::class,'supervisor_id');
    }

    public function designRequests()
    {
        return $this->hasMany(DesignRequest::class, 'design_request_header_id');
    }
}
