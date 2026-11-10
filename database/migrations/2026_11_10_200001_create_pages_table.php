<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * A page is a single editor-managed document. Its scalar sections (hero,
   * mission, section headings, call to action and contact) live here, while
   * repeatable content lives in the page_* child tables.
   */
  public function up(): void
  {
    Schema::create('pages', function (Blueprint $table) {
      $table->id();
      $table->string('slug')->unique();
      $table->string('meta_title');

      // Hero
      $table->string('hero_badge')->nullable();
      $table->string('hero_title');
      $table->string('hero_highlight')->nullable();
      $table->text('hero_description');
      $table->string('hero_primary_cta_label');
      $table->string('hero_primary_cta_url');
      $table->string('hero_secondary_cta_label')->nullable();
      $table->string('hero_secondary_cta_url')->nullable();

      // Mission
      $table->string('mission_eyebrow')->nullable();
      $table->string('mission_title');
      $table->json('mission_paragraphs');
      $table->text('mission_quote_text')->nullable();
      $table->string('mission_quote_initials', 10)->nullable();
      $table->string('mission_quote_author')->nullable();
      $table->string('mission_quote_role')->nullable();

      // Section headings
      $table->string('values_eyebrow')->nullable();
      $table->string('values_title');
      $table->string('timeline_eyebrow')->nullable();
      $table->string('timeline_title');
      $table->string('team_eyebrow')->nullable();
      $table->string('team_title');
      $table->text('team_description')->nullable();

      // Call to action and contact details
      $table->string('cta_title');
      $table->text('cta_description')->nullable();
      $table->string('contact_email');
      $table->string('footer_note')->nullable();

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('pages');
  }
};
