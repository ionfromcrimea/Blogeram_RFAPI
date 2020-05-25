<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'news',
        'image',
        'list1',
//        'list2',
//        'list3',
//        'list4',
//        'list5',
//        'list6',
//        'status',
    ];

    public function blogers()
    {
        return $this->belongsToMany('App\Bloger', 'Bloger_news', 'news_id', 'bloger_id');
    }

}

