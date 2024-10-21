<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'is_logged_in',
        'contact_info',
        'profile_picture',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class, "model_has_roles", "model_id", "role_id");
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, "model_has_permisions", "model_id", "permission_id");
    }

    public function expenseHeader()
    {
        return $this->hasMany(ExpenseHeader::class, 'created_by');
    }

    public function customerDesignRequestHeader()
    {
        return $this->hasMany(DesignRequestHeader::class, 'customer_id');
    }

    public function supervisedDesignRequestHeader()
    {
        return $this->hasMany(DesignRequestHeader::class, 'supervisor_id');
    }

    public function designRequests()
    {
        return $this->hasMany(DesignRequest::class, 'assigned_designer_id');
    }

    public function machineOperationsAsOperator()
    {
        return $this->hasMany(MachineOperation::class, 'operator_id');
    }

    public function machineOperationsAsAssistant()
    {
        return $this->hasMany(MachineOperation::class, 'assistant_id');
    }

    public function qcOperations()
    {
        return $this->hasMany(QcOperation::class, 'qc_id');
    }

    public function transactionHeaders()
    {
        return $this->hasMany(TransactionHeader::class, 'customer_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }
}
