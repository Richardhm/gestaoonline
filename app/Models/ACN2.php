<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ACN2 extends Model
{
    protected $table = "acn2s";

    protected $fillable = ['ac_id','nome','tipo','situacao'];

    public function ac()
    {
        return $this->belongsTo(AC::class, 'ac_id');
    }

    public function ars()
    {
        return $this->hasMany(AR::class, 'acn2_id');
    }

}
