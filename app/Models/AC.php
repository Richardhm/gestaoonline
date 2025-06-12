<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AC extends Model
{
    protected $table = 'acs';
    protected $fillable = ['nome','telefone','situacao'];

    public function acn2s() {
        return $this->hasMany(ACN2::class, 'ac_id');
    }

}
