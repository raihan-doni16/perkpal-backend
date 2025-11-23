<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('journal_posts', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('content');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('og_title')->nullable()->after('meta_description');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('og_image')->nullable()->after('og_description');
            $table->string('twitter_title')->nullable()->after('og_image');
            $table->text('twitter_description')->nullable()->after('twitter_title');
        });
    }

    public function down(): void
    {
        Schema::table('journal_posts', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description',
                'og_title',
                'og_description',
                'og_image',
                'twitter_title',
                'twitter_description',
            ]);
        });
    }
};
