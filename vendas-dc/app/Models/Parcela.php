<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
   public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

}
