<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * Получение Students привязанных к Groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function students()
    {
        return $this->hasMany('App\Student');
    }

    /**
     * Получение Course, привязанного к Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    /**
     * Получение Direction, привязанного к Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function direction()
    {
        return $this->belongsTo('App\Direction');
    }
}
