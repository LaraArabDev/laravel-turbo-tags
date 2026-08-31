<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LaraArabDev\TurboTags\Models\Tag;

return new class extends Migration
{
    public function up(): void
    {
        $tagsTable = config('laravel-turbo-tags.tables.tags', 'tags');
        $taggablesTable = config('laravel-turbo-tags.tables.taggables', 'taggables');

        Schema::create($tagsTable, function (Blueprint $table) use ($tagsTable) {
            $table->id();
            $table->json('name');
            $table->string('slug')->unique();
            $table->string('type')->nullable()->index();
            $table->integer('order_column')->nullable();
            $table->json('metadata')->nullable();
            $table->nullableMorphs('owner');
            $table->foreignIdFor(Tag::class, 'parent_id')->nullable()->constrained($tagsTable)->nullOnDelete()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create($taggablesTable, function (Blueprint $table) use ($tagsTable) {
            $table->id();
            $table->foreignIdFor(Tag::class)->constrained($tagsTable)->cascadeOnDelete();
            $table->morphs('taggable');
            $table->timestamps();

            $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
        });
    }

    public function down(): void
    {
        $taggablesTable = config('laravel-turbo-tags.tables.taggables', 'taggables');
        $tagsTable = config('laravel-turbo-tags.tables.tags', 'tags');

        Schema::dropIfExists($taggablesTable);
        Schema::dropIfExists($tagsTable);
    }
};
