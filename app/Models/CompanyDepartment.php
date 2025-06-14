<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDepartment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'company_area_id'];

    public function area()
    {
        return $this->belongsTo(CompanyArea::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
