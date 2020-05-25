<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Query\Builder;

class Bloger extends Model
{
    protected $fillable = [
        'login',
        'password',
        'status',
//        'check',
//        'follower',
//        'post',
//        'firstname',
//        'lastname',
//        'birthday',
//        'phone',
//        'city',
//        'email',
//        'comment',

    ];

    public function news()
    {
        return $this->belongsToMany('App\News', 'Bloger_news', 'bloger_id', 'news_id');
    }
    /*
     * образец применения "Scope filters"
     *  // GET /blogers?filter[scopeStatus]=3
     * см. AllowedFilter в методе index контроллера Blogers
     */

    public function scopeScopeStatus(Builder $query, $limit1, $limit2): Builder
    {
//        return $query->where('status', '<=', $limit1)->where('status', '<=', $limit2);
        return $query->whereBetween('status', [$limit1, $limit2]);
    }
}
//whereNotBetween('votes', [1, 100])
