<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Contribution\App\Models\Contribution;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('saved_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(User::table);
            $table->foreignId('contribution_id')->constrained(Contribution::table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_contributions');
    }
};
