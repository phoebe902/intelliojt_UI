<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('active')->index();   // active | completed | cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('phase')->default('before')->index();     // before | during | after
            $table->string('type')->default('document');             // document | participation | received
            $table->string('recurrence')->default('none');           // none | monthly | weekly
            $table->date('due_on')->nullable();                      // one-time requirements only
            $table->boolean('is_required')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('requirement_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requirement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('period')->default('');                   // '' | 2026-10 (month) | 2026-W44 (week)
            $table->string('status')->default('submitted')->index(); // submitted | approved | rejected
            $table->string('file_path')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['requirement_id', 'student_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requirement_submissions');
        Schema::dropIfExists('requirements');
        Schema::dropIfExists('placements');
    }
};