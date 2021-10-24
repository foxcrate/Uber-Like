<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{



/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'teacher_id',
        'semester_id',
        'name_ar',
        'name_en',
        'photo',
        'video',
        'price_G',
        'price',
        'number_stu',
        'note_ar',
        'note_en',

    ];
    protected $hidden = [
         'created_at', 'updated_at'
    ];











    public function semester(){
        return $this->belongsTo('App\Semester','semester_id');
    }
    public function teacher(){
        return $this->belongsTo('App\Teacher','teacher_id');
    }


}
