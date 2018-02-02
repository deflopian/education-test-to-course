<?php

namespace Deflopian\EducationTestToCourse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use App\Course;
use Deflopian\EducationTests\Models\Test;

class TestToCourse extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id', 'test_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'course_id', 'test_id'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['course', 'test'];

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
        $this->table = Config::get('education-test-to-course.test_to_course_table');
    }


    /**
     * Return list of a questions for this test.
     *
     * @return Course
     */
    public function getCourseAttribute()
    {
        return Course::select(['id'])->whereId($this->course_id)->firstOrFail();
    }


    /**
     * Return list of a questions for this test.
     *
     * @return Course
     */
    public function getTestAttribute()
    {
        return Test::select(['uuid'])->whereId($this->test_id)->firstOrFail();
    }
}