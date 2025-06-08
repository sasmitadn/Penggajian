<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvance extends Model
{
    use HasFactory;

    public function details()
    {
        return $this->hasMany(CashAdvanceDetail::class, 'id_cash_advances');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'id_payroll');
    }
}
