<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_id',
        'designer_id',
        'design_name',
        'design_files',
        'status'
    ];

    public function designRequest()
    {
        return $this->belongsTo(DesignRequest::class, 'request_id'); // Assuming 'request_id' is the foreign key
    }

    public function designer(){
        return $this->belongsTo(User::class, 'designer_id'); // Assuming 'designer_id' is the foreign key
    }

    public function machineOperations()
    {
        return $this->hasMany(MachineOperation::class, 'design_id');
    }
    public function qcOperation()
    {
        return $this->hasMany(QcOperation::class, 'design_id');
    }
}
