<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'slug',
    'meta_title',
    'hero_badge',
    'hero_title',
    'hero_highlight',
    'hero_description',
    'hero_primary_cta_label',
    'hero_primary_cta_url',
    'hero_secondary_cta_label',
    'hero_secondary_cta_url',
    'mission_eyebrow',
    'mission_title',
    'mission_paragraphs',
    'mission_quote_text',
    'mission_quote_initials',
    'mission_quote_author',
    'mission_quote_role',
    'values_eyebrow',
    'values_title',
    'timeline_eyebrow',
    'timeline_title',
    'team_eyebrow',
    'team_title',
    'team_description',
    'cta_title',
    'cta_description',
    'contact_email',
    'footer_note',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'mission_paragraphs' => 'array',
    ];
  }

  /**
   * Key figures shown under the hero section.
   */
  public function stats(): HasMany
  {
    return $this->hasMany(PageStat::class)->orderBy('sort_order');
  }

  /**
   * The principles listed in the "what we believe" section.
   */
  public function values(): HasMany
  {
    return $this->hasMany(PageValue::class)->orderBy('sort_order');
  }

  /**
   * The company story entries, oldest first.
   */
  public function timelineEntries(): HasMany
  {
    return $this->hasMany(PageTimelineEntry::class)->orderBy('sort_order');
  }

  /**
   * The people listed in the team section.
   */
  public function teamMembers(): HasMany
  {
    return $this->hasMany(PageTeamMember::class)->orderBy('sort_order');
  }
}
