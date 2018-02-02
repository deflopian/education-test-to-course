<?php echo '<?php' ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EducationTestToCourseSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up() {
        // Create table for storing roles
        Schema::create('{{ $test_to_course_table }}', function (Blueprint $table) {
            $table->increments('id'); // inner id
            $table->integer('course_id')->unsigned(); // specific public id
            $table->integer('test_id')->unsigned();
            $table->timestamps();

            $table->foreign('test_id')->references('id')->on('{{ $tests_table }}')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('{{ $courses_table }}')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('{{ $test_to_lesson_table }}', function (Blueprint $table) {
            $table->increments('id'); // inner id
            $table->integer('lesson_id')->unsigned(); // specific public id
            $table->integer('test_id')->unsigned();
            $table->timestamps();

            $table->foreign('test_id')->references('id')->on('{{ $tests_table }}')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('{{ $lessons_table }}')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down() {
        Schema::drop('{{ $test_to_course_table }}');
        Schema::drop('{{ $test_to_lesson_table }}');
    }
}