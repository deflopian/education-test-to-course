<?php
namespace Deflopian\EducationTestToCourse\Controllers;

use Deflopian\EducationTestToCourse\Models\TestToCourse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Course;
use Deflopian\EducationTests\Models\Test;

class TestToCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['show']]);
    }

    public function show($course_id)
    {
        $test_to_courses = TestToCourse::whereCourseId($course_id)->get();
        if (!$test_to_courses) {
            return response()->json('Not found', 404);
        }

        $results = [];
        foreach ($test_to_courses as $test_to_course) {
            $test = Test::select(['uuid'])->find($test_to_course->test_id);
            $results[] = [
                'course_id' => $course_id,
                'test_uuid' => $test->uuid
            ];
        }


        return $results ? $results : response()->json('Not found', 404);
    }
}