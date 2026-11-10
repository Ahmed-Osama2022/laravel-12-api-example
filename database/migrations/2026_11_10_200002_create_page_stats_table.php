<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('page_stats', function (Blueprint $table) {
      $table->id();
      $table->foreignId('page_id')->constrained()->cascadeOnDelete();
      $table->string('value');
      $table->string('label');
      $table->unsignedInteger('sort_order')->default(0);
      $table->timestamps();

      $table->index(['page_id', 'sort_order']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('page_stats');
  }
};
