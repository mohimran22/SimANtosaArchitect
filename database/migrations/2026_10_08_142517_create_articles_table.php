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
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->longText('content');
        $table->string('excerpt', 300)->nullable();
        $table->string('featured_image')->nullable();
        $table->foreignUuid('author_id')->constrained('users');
        $table->enum('status', ['draft', 'published'])->default('draft');
        $table->timestamp('published_at')->nullable();

        // Field SEO
        $table->string('meta_title', 70)->nullable();
        $table->string('meta_description', 170)->nullable();
        $table->string('focus_keyword')->nullable();
        $table->string('canonical_url')->nullable();
        $table->boolean('noindex')->default(false);
        $table->string('og_image')->nullable();
        $table->unsignedTinyInteger('seo_score')->nullable();
        $table->unsignedTinyInteger('readability_score')->nullable();
        $table->unsignedInteger('views')->default(0);
        $table->softDeletes();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
