<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    //protected $guarded = [];
    protected $fillable = ['nama','nama_panggilan','status','ucapan','baca'];

    protected $table = 'fotos';

    protected $appends = ['created_at'];

    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])
            ->format('d M Y H:i');
    }

    
}
