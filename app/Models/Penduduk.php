<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    use HasFactory;

    protected $guarded = ['nik'];
    
    protected $primaryKey = 'nik';
    public $incrementing = false;

    public function penerimaan_bansos(){
        return $this->belongsTo(PenerimaanBansos::class, 'nik');
    }
}
