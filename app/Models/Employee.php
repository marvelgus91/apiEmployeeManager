<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullName',
        'company_department_id',
        'position',
        'photo',
        'startDate',
        'status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function area()
    {
        return $this->belongsTo(CompanyArea::class);
    }

    public function department()
    {
        return $this->belongsTo(CompanyDepartment::class);
    }
}
