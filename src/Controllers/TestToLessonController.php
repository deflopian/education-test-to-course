<?php
namespace Deflopian\EducationTestToCourse\Controllers;

use Deflopian\EducationTestToCourse\Models\TestToLesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lesson;
use Deflopian\EducationTests\Models\Test;

class TestToLessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['show']]);
    }

    public function show($lesson_id)
    {
        $test_to_lessons = TestToLesson::whereLessonId($lesson_id)->get();
        if (!$test_to_lessons) {
            return response()->json('Not found', 404);
        }

        $results = [];
        foreach ($test_to_lessons as $test_to_lesson) {
            $test = Test::select(['uuid'])->find($test_to_lesson->test_id);
            $results[] = [
                'lesson_id' => $lesson_id,
                'test_uuid' => $test->uuid
            ];
        }
        return $results ? $results : response()->json('Not found', 404);
    }
}