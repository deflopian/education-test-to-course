<?php
namespace Deflopian\EducationTestToCourse;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class MigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'education-test-to-course:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration following the Education Tests specifications.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->laravel->view->addNamespace('education-test-to-course', substr(__DIR__, 0, -8).'views');

        $test_to_course_table = Config::get('education-test-to-course.test_to_course_table');
        $test_to_lesson_table = Config::get('education-test-to-course.test_to_lesson_table');

        $this->line('');
        $this->info( "Tables: $test_to_course_table, $test_to_lesson_table" );

        $message = "A migration that creates '$test_to_course_table', '$test_to_lesson_table'".
        " tables will be created in database/migrations directory";

        $this->comment($message);
        $this->line('');

        if ($this->confirm("Proceed with the migration creation? [Yes|no]", "Yes")) {

            $this->line('');

            $this->info("Creating migration...");
            if ($this->createMigration($test_to_course_table, $test_to_lesson_table)) {

                $this->info("Migration successfully created!");
            } else {
                $this->error(
                    "Couldn't create migration.\n Check the write permissions".
                    " within the database/migrations directory."
                );
            }

            $this->line('');

        }
    }

    /**
     * Create the migration.
     *
     * @param string $test_to_course_table
     * @param string $test_to_lesson_table
     *
     * @return bool
     */
    protected function createMigration($test_to_course_table, $test_to_lesson_table)
    {
        $migration_file = base_path("/database/migrations")."/".date('Y_m_d_His')."_education_test_to_course_setup_tables.php";

        $tests_table = Config::get('education-tests.tests_table');
        $courses_table = Config::get('education-courses.courses_table');
        $lessons_table = Config::get('education-courses.lessons_table');

        $data = compact('tests_table', 'test_to_course_table', 'test_to_lesson_table', 'tests_table', 'courses_table', 'lessons_table');

        $output = $this->laravel->view->make('education-test-to-course::generators.migration')->with($data)->render();

        if (!file_exists($migration_file) && $fs = fopen($migration_file, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }
}
