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
       Schema::table('users', function (Blueprint $table) {
            
            $table->integer('reputation_score')->default(0)->after('password');
            $table->boolean('is_banned')->default(false)->after('reputation_score');
            $table->enum('global_role', ['user','admin'])->default('user')->after('is_banned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reputation_score', 'is_banned', 'global_role']);
        });
    }
};
