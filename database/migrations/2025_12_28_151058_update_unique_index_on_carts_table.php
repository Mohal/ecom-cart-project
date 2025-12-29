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
        Schema::table('carts', function (Blueprint $table) {
            // Drop the foreign key constraint on user_id
            $table->dropForeign(['user_id']);

            // Drop the old composite unique index
            $table->dropUnique(['user_id', 'is_active']);

            // Re-add the foreign key
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            // Add the new conditional unique index (only enforce when is_active = true)
            $table->unique('user_id')->where('is_active', true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Drop the conditional unique index
            $table->dropUnique(['user_id']);

            // Drop and re-add the foreign key
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            // Restore the old composite unique index
            $table->unique(['user_id', 'is_active']);
        });
    }
};
