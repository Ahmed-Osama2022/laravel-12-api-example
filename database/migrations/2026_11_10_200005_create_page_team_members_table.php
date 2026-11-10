<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('page_team_members', function (Blueprint $table) {
      $table->id();
      $table->foreignId('page_id')->constrained()->cascadeOnDelete();
      $table->string('name');
      $table->string('role');
      $table->string('initials', 10);
      $table->string('avatar_color');
      $table->unsignedInteger('sort_order')->default(0);
      $table->timestamps();

      $table->index(['page_id', 'sort_order']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('page_team_members');
  }
};
