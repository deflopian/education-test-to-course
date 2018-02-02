<?php
namespace Deflopian\EducationTestToCourse\Controllers;

use Deflopian\EducationTestToCourse\Models\TestToCourse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestToCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['show']]);
        $this->middleware('admin', ['except' => ['show']]);
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

    public function update(Request $request, TestToCourse $test_to_course)
    {
        $test_to_course->update($request->all());

        return response()->json($test_to_course, 200);
    }

    public function delete(TestToCourse $test_to_course)
    {
        $test_to_course->delete();

        return response()->json(null, 204);
    }
}