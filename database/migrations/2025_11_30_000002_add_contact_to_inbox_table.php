<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inbox', function (Blueprint $table) {
            $table->string('contact', 255)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('inbox', function (Blueprint $table) {
            $table->dropColumn('contact');
        });
    }
};
