<?php

namespace Deflopian\EducationTestToCourse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class TestToLesson extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lesson_id', 'test_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('education-test-to-course.test_to_lesson_table');
    }
}
