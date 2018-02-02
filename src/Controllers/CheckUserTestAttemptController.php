<?php
namespace Deflopian\EducationTestToCourse\Controllers;

use Deflopian\EducationTestToCourse\Models\TestToCourse;
use Deflopian\EducationTestToCourse\Models\TestToLesson;
use Deflopian\EducationTests\Models\UserTestAttempt;
use Deflopian\EducationTests\Models\Test;
use Deflopian\EducationTests\Models\Question;
use App\UserCourse;
use App\UserLesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckUserTestAttemptController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $user_attempt = UserTestAttempt::select(['test_id', 'passed', 'result'])
                            ->whereUuid($request->post('uuid'))
                            ->whereUserId($user->id)->first();

        if (!$user_attempt) {
            return response()->json([
                'message' => 'Attempt not found'
            ], 404);
        }

        if (!$user_attempt->passed) {
            return response()->json([
                'message' => 'Attempt failed',
                'passed' => false,
                'result' => $user_attempt->result
            ], 200);
        }

        $test = Test::select(['allowable_mistakes_count'])->find($user_attempt->test_id);
        $count_questions = Question::whereTestId($user_attempt->test_id)->count();
        $mistakes_count = $count_questions - $user_attempt->result;

        if ($test->allowable_mistakes_count !== 0) {
            $success_percentage = 1 - ($mistakes_count / $test->allowable_mistakes_count);
        } else {
            $success_percentage = $mistakes_count > 0 ? 0 : 1;
        }


        $test_to_courses = TestToCourse::whereTestId($user_attempt->test_id)->get();
        foreach ($test_to_courses as $test_to_course) {
            $user_course = UserCourse::whereCourseId($test_to_course->course_id)->first();
            if (isset($user_course->result) && $user_course->result < $success_percentage) {
                $user_course->result = $success_percentage;
                $user_course->save();
            }
        }

        $test_to_lessons = TestToLesson::whereTestId($user_attempt->test_id)->get();

        foreach ($test_to_lessons as $test_to_lesson) {

            $user_lesson = UserLesson::whereLessonId($test_to_lesson->lesson_id)->first();
            if (isset($user_lesson->result) && $user_lesson->result < $success_percentage) {
                $user_lesson->result = $success_percentage;
                $user_lesson->save();
            }
        }

        return response()->json([
            'message' => 'Attempt saved',
            'passed' => true,
            'result' => $user_attempt->result,
            'percentage' => $success_percentage,
        ], 201);
    }
}