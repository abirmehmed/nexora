<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name');
            $table->text('bio')->nullable()->after('username');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('cover_image')->nullable()->after('avatar');
            $table->string('website')->nullable()->after('cover_image');
            $table->string('location')->nullable()->after('website');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'bio', 'avatar', 'cover_image', 'website', 'location']);
        });
    }
};
