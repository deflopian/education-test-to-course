<?php
namespace Deflopian\EducationTestToCourse\Controllers;

use Deflopian\EducationTestToCourse\Models\TestToLesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestToLessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['show']]);
        $this->middleware('admin', ['except' => ['show']]);
    }

    public function show($lesson_id)
    {
        $test_to_lessons = TestToLesson::whereLessonId($lesson_id)->get();
        return $test_to_lessons ?: response()->json('Not found', 404);
    }

    public function store(Request $request)
    {
        $test_to_lesson = TestToLesson::create($request->all());

        return response()->json($test_to_lesson, 201);
    }

    public function update(Request $request, TestToLesson $test_to_lesson)
    {
        $test_to_lesson->update($request->all());

        return response()->json($test_to_lesson, 200);
    }

    public function delete(TestToLesson $test_to_lesson)
    {
        $test_to_lesson->delete();

        return response()->json(null, 204);
    }
}