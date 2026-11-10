<?php

namespace App\Http\Resources;

use App\Models\PageStat;
use App\Models\PageTeamMember;
use App\Models\PageTimelineEntry;
use App\Models\PageValue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Page
 */
class AboutResource extends JsonResource
{
  /**
   * Shape the about page document exactly as the Vue frontend expects it.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'meta' => [
        'title' => $this->meta_title,
      ],
      'hero' => [
        'badge' => $this->hero_badge,
        'title' => $this->hero_title,
        'highlight' => $this->hero_highlight,
        'description' => $this->hero_description,
        'primaryCta' => $this->transformCta(
          $this->hero_primary_cta_label,
          $this->hero_primary_cta_url,
        ),
        'secondaryCta' => $this->transformCta(
          $this->hero_secondary_cta_label,
          $this->hero_secondary_cta_url,
        ),
      ],
      'stats' => $this->whenLoaded('stats', fn () => $this->stats
        ->map(fn (PageStat $stat) => [
          'value' => $stat->value,
          'label' => $stat->label,
        ])
        ->values()
        ->all()),
      'mission' => [
        'eyebrow' => $this->mission_eyebrow,
        'title' => $this->mission_title,
        'paragraphs' => $this->mission_paragraphs ?? [],
        'quote' => $this->mission_quote_text === null ? null : [
          'text' => $this->mission_quote_text,
          'initials' => $this->mission_quote_initials,
          'author' => $this->mission_quote_author,
          'role' => $this->mission_quote_role,
        ],
      ],
      'valuesSection' => [
        'eyebrow' => $this->values_eyebrow,
        'title' => $this->values_title,
      ],
      'values' => $this->whenLoaded('values', fn () => $this->values
        ->map(fn (PageValue $value) => [
          'icon' => $value->icon,
          'title' => $value->title,
          'text' => $value->text,
        ])
        ->values()
        ->all()),
      'timelineSection' => [
        'eyebrow' => $this->timeline_eyebrow,
        'title' => $this->timeline_title,
      ],
      'timeline' => $this->whenLoaded('timelineEntries', fn () => $this->timelineEntries
        ->map(fn (PageTimelineEntry $entry) => [
          'year' => $entry->year,
          'title' => $entry->title,
          'text' => $entry->text,
        ])
        ->values()
        ->all()),
      'teamSection' => [
        'eyebrow' => $this->team_eyebrow,
        'title' => $this->team_title,
        'description' => $this->team_description,
      ],
      'team' => $this->whenLoaded('teamMembers', fn () => $this->teamMembers
        ->map(fn (PageTeamMember $member) => [
          'name' => $member->name,
          'role' => $member->role,
          'initials' => $member->initials,
          'avatarColor' => $member->avatar_color,
        ])
        ->values()
        ->all()),
      'cta' => [
        'title' => $this->cta_title,
        'description' => $this->cta_description,
      ],
      'contact' => [
        'email' => $this->contact_email,
        'footerNote' => $this->footer_note,
      ],
    ];
  }

  /**
   * Build a call to action object, omitting optional values (e.g. secondary
   * buttons have a label but no destination).
   *
   * @return array<string, string>
   */
  private function transformCta(?string $label, ?string $url): array
  {
    return array_filter([
      'label' => $label,
      'to' => $url,
    ], fn (?string $value) => $value !== null);
  }
}
