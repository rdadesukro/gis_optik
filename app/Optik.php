<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Optik extends Model
{
    //protected $guarded = [];
    protected $fillable = ['nama','nama_panggilan','status','ucapan','baca'];

    protected $table = 'optiks';

    protected $appends = ['created_at'];

    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])
            ->format('d M Y H:i');
    }

}
