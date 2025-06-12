<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AR extends Model
{
    protected $table = "ars";

    protected $fillable = [
        'acn2_id',
        'nome',
        'tipo',
        'situacao',
        'open',
    ];

    public function acn2()
    {
        return $this->belongsTo(ACN2::class, 'acn2_id');
    }


}
