<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 
    // In the migration for TestResult
public function up()
{
    Schema::create('test_results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Updated this line to refer to users table
        $table->integer('score');
        $table->integer('total_points');
        $table->timestamps();
    });    
    
}


    public function down()
    {
        Schema::dropIfExists('test_results');
    }
};
