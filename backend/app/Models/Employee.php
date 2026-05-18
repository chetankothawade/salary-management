<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmployeeEmploymentType;
use App\Enums\EmployeeStatus;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_code',
        'department_id',
        'country_id',
        'job_title',
        'salary',
        'employment_type',
        'status',
        'joining_date',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'employment_type' => EmployeeEmploymentType::class,
        'status' => EmployeeStatus::class,
        'joining_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(): ?string
    {
        return $this->user?->name;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
