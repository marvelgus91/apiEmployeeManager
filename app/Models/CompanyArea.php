<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyArea extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function companyDepartment()
    {
        return $this->hasMany(CompanyDepartment::class);
    }
}
