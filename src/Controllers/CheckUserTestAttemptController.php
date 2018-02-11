<?php
namespace Deflopian\EducationTestToCourse\Controllers;

use Deflopian\EducationTestToCourse\Models\TestToCourse;
use Deflopian\EducationTestToCourse\Models\TestToLesson;
use Deflopian\EducationTests\Models\UserTestAttempt;
use Deflopian\EducationTests\Models\Test;
use Deflopian\EducationTests\Models\Question;
use App\UserCourse;
use App\Lesson;
use App\Course;
use App\UserLesson;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
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
        $send_mail = Config::get('education-test-to-course.send_success_mail');
        $admin_mail = Config::get('education-test-to-course.admin_email');
        $admin_name = Config::get('education-test-to-course.admin_name');

        foreach ($test_to_lessons as $test_to_lesson) {

            $user_lesson = UserLesson::whereLessonId($test_to_lesson->lesson_id)->first();
            if (isset($user_lesson->result) && $user_lesson->result < $success_percentage) {
                $user_lesson->result = $success_percentage;
                $user_lesson->save();
            }

            if ($send_mail) {
                $lesson = Lesson::find($test_to_lesson->lesson_id);

                Mail::send('mail.lesson_test_passed_success', [
                    'lesson_title' => $lesson->title,
                    'lesson_link' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/lessons/' . $lesson->id,
                    'course_title' => $lesson->course ? $lesson->course->title : '',
                    'course_link' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/courses/' . $lesson->course_id,

                    'allowable_mistakes_count' => $test->allowable_mistakes_count,
                    'right_answers' => $user_attempt->result,
                    'total_questions_count' => $count_questions,
                    'success_percentage' => round($user_attempt->result * 100 / $count_questions, 1),

                    'first_name' => $user->first_name,
                    'second_name' => $user->second_name,
                    'surname' => $user->surname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ], function($message) use ($admin_mail, $admin_name) {
                    $message->to($admin_mail, $admin_name)
                        ->subject('Пользователь успешно завершил тест');
                });
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