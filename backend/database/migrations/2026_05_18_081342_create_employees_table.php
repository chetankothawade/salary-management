<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | User Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Employee Details
            |--------------------------------------------------------------------------
            */

            $table->string('employee_code', 50)->unique();

            $table->foreignId('department_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('country_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('job_title', 150);

            $table->decimal('salary', 15, 2);

            $table->enum('employment_type', [
                'full_time',
                'part_time',
                'contract',
                'intern'
            ])->default('full_time');

            $table->enum('status', [
                'active',
                'inactive'
            ])->default('active');

            $table->date('joining_date');

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('country_id');
            $table->index('department_id');
            $table->index('job_title');
            $table->index('salary');

            $table->index([
                'country_id',
                'job_title'
            ]);

            $table->index([
                'country_id',
                'salary'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};