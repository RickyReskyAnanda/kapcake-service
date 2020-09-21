<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaketEntry extends Model
{
    protected $table = 'paket_entry';
    protected $primaryKey = 'id_paket_entry';
    protected $guarded = [];
}
