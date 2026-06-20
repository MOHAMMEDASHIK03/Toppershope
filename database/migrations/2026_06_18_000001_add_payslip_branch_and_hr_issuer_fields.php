<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hr_users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->foreignId('generated_by')
                ->nullable()
                ->after('employee_id')
                ->constrained('hr_users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropConstrainedForeignId('generated_by');
        });

        Schema::table('hr_users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
