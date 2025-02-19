<?php

use Modules\Users\User\App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\AcademicYear\App\Models\AcademicYear;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('closure_dates', function (Blueprint $table) {
            $table->id();
            $table->date('closure_date');
            $table->date('final_closure_date');
            $table->foreignId('academic_year_id')->constrained(AcademicYear::table);
            $table->integer('version')->default(1);
            $table->foreignId('created_by')->nullable()->constrained(User::table);
            $table->foreignId('updated_by')->nullable()->constrained(User::table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('closure_dates');
    }
};
