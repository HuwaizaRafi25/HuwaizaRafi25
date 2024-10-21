<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignRequest extends Model
{
    use HasFactory;
    protected $casts = [
        'completed_at' => 'datetime',
    ];
    protected $fillable = [
        "design_request_header_id",
        "assigned_designer_id",
        "supervisor_id",
        "reference_image",
        "name",
        "size",
        "color",
        "price_per_piece",
        "total_pieces",
        "status",
        "description",
        "completed_at",
        "estimated_completion_at",
    ];

    public function designRequestHeader(){
        return $this->belongsTo(DesignRequestHeader::class, "design_request_header_id");
    }

    public function assignedDesigner(){
        return $this->belongsTo(User::class, "assigned_designer_id");
    }

    public function payrollJob(){
        return $this->hasOne(PayrollJob::class, "design_request_id");
    }

    public function design(){
        return $this->hasOne(Design::class, "request_id");
    }
}
