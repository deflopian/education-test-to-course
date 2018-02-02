<?php
namespace Deflopian\EducationTestToCourse\Controllers;

use Deflopian\EducationTestToCourse\Models\TestToCourse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Course;

class AdminTestToCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function show($course_id)
    {
        $test_to_courses = TestToCourse::whereCourseId($course_id)->get();

        return $test_to_courses ?: response()->json('Not found', 404);
    }

    public function store(Request $request)
    {
        $test_to_course = TestToCourse::create($request->all());

        return response()->json($test_to_course, 201);
    }

    public function delete($test_to_course_id)
    {
        TestToCourse::find($test_to_course_id)->delete();

        return response()->json(null, 204);
    }
}