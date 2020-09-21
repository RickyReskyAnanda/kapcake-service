<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Excel extends Model
{
    protected $table = 'trendline_fb_share';
    protected $primaryKey = 'id';

    protected $guarded = [];
}
