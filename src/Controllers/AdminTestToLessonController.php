<?php
namespace Deflopian\EducationTestToCourse\Controllers;

use Deflopian\EducationTestToCourse\Models\TestToLesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lesson;

class AdminTestToLessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
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

    public function delete($test_to_lesson_id)
    {
        TestToLesson::find($test_to_lesson_id)->delete();

        return response()->json(null, 204);
    }
}