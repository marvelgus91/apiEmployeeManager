<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'rfc'];

    public function companyAreas()
    {
        return $this->hasMany(CompanyArea::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
