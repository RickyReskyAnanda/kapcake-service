<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PemesananKeDapur extends Model
{
    protected $table = 'pemesanan_ke_dapur';
    protected $primaryKey = 'id_pemesanan_ke_dapur';
    protected $guarded = [];
}
