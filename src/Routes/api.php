<?php

use Deflopian\EducationTestToCourse\Controllers\CheckUserTestAttemptController;
use Deflopian\EducationTestToCourse\Controllers\TestToCourseController;
use Deflopian\EducationTestToCourse\Controllers\TestToLessonController;

Route::apiResource('api/course-tests', TestToCourseController::class);
Route::apiResource('api/lesson-tests', TestToLessonController::class);
Route::apiResource('api/check-attempt', CheckUserTestAttemptController::class);