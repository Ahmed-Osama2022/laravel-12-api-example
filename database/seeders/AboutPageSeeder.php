<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutPageSeeder extends Seeder
{
  /**
   * Seed (or refresh) the about page and its repeatable sections.
   *
   * The seeder is re-runnable: the page is matched by its unique slug and the
   * child collections are replaced so stale rows never survive a re-seed.
   */
  public function run(): void
  {
    DB::transaction(function (): void {
      $page = Page::query()->updateOrCreate(
        ['slug' => 'about'],
        $this->pageAttributes(),
      );

      $this->replaceCollection($page->stats(), $this->stats());
      $this->replaceCollection($page->values(), $this->values());
      $this->replaceCollection($page->timelineEntries(), $this->timeline());
      $this->replaceCollection($page->teamMembers(), $this->team());
    });
  }

  /**
   * Delete the current rows of a page section and insert the given ones in
   * the order they are defined.
   *
   * @param  \Illuminate\Database\Eloquent\Relations\HasMany<*>  $relation
   * @param  list<array<string, string>>  $rows
   */
  private function replaceCollection(HasMany $relation, array $rows): void
  {
    $relation->delete();

    $relation->createMany(
      collect($rows)
        ->values()
        ->map(fn (array $row, int $index) => $row + ['sort_order' => $index])
        ->all(),
    );
  }

  /**
   * Scalar content of the page.
   *
   * @return array<string, mixed>
   */
  private function pageAttributes(): array
  {
    return [
      'meta_title' => 'About us — Example',
      'hero_badge' => 'About us',
      'hero_title' => 'We build tools that',
      'hero_highlight' => 'get out of the way',
      'hero_description' => 'A small, independent team on a simple mission: make everyday work feel lighter. No bloat, no noise — just software that does its job well.',
      'hero_primary_cta_label' => 'Try the product',
      'hero_primary_cta_url' => '/',
      'hero_secondary_cta_label' => 'Say hello',
      'hero_secondary_cta_url' => null,
      'mission_eyebrow' => 'Our mission',
      'mission_title' => 'Software should feel like a good colleague',
      'mission_paragraphs' => [
        'Most tools ask for more than they give back: more setup, more tabs, more meetings. We started this company because we believed the opposite was possible — a product that quietly handles the busywork so people can focus on the part only they can do.',
        "Today that idea is used by teams in more than forty countries. We're still independent, still profitable, and still shipping every single week.",
      ],
      'mission_quote_text' => "The best compliment we get is that people forget the product is there. That's the whole design goal.",
      'mission_quote_initials' => 'AO',
      'mission_quote_author' => 'Amara Okafor',
      'mission_quote_role' => 'Co-founder & CEO',
      'values_eyebrow' => 'What we believe',
      'values_title' => "Four rules we don't break",
      'timeline_eyebrow' => 'Our story',
      'timeline_title' => 'From side project to daily habit',
      'team_eyebrow' => 'The people',
      'team_title' => 'Small team, big care',
      'team_description' => 'Remote by default, curious by nature. These are the faces behind the product.',
      'cta_title' => "Let's make work feel lighter",
      'cta_description' => 'Questions, ideas, or just want to chat? We read every message and usually reply within a day.',
      'contact_email' => 'hello@example.com',
      'footer_note' => '© 2026 Example Inc. Built with Vue and Tailwind CSS.',
    ];
  }

  /**
   * @return list<array<string, string>>
   */
  private function stats(): array
  {
    return [
      ['value' => '2021', 'label' => 'Founded'],
      ['value' => '12k+', 'label' => 'Happy users'],
      ['value' => '40+', 'label' => 'Countries'],
      ['value' => '99.9%', 'label' => 'Uptime'],
    ];
  }

  /**
   * @return list<array<string, string>>
   */
  private function values(): array
  {
    return [
      [
        'icon' => 'bolt',
        'title' => 'Move fast',
        'text' => 'We ship small, focused improvements every week instead of waiting for the perfect release.',
      ],
      [
        'icon' => 'clock',
        'title' => 'Stay simple',
        'text' => 'Great tools get out of the way. We obsess over removing steps, not adding them.',
      ],
      [
        'icon' => 'users',
        'title' => 'Build together',
        'text' => 'Our roadmap is shaped in the open. Customers are our best product managers.',
      ],
      [
        'icon' => 'shield',
        'title' => 'Earn trust',
        'text' => 'Privacy first, no dark patterns, and a clear price. Your data stays yours.',
      ],
    ];
  }

  /**
   * @return list<array<string, string>>
   */
  private function timeline(): array
  {
    return [
      [
        'year' => '2021',
        'title' => 'A weekend prototype',
        'text' => 'Two friends, one laptop, and a spreadsheet full of complaints about clunky tools.',
      ],
      [
        'year' => '2022',
        'title' => 'First 1,000 users',
        'text' => 'Launched publicly and grew entirely through word of mouth.',
      ],
      [
        'year' => '2024',
        'title' => 'The team grows',
        'text' => 'A small remote team across three time zones, still shipping every week.',
      ],
      [
        'year' => '2026',
        'title' => 'Here and now',
        'text' => 'Over 12,000 teams use the product to do their best work.',
      ],
    ];
  }

  /**
   * @return list<array<string, string>>
   */
  private function team(): array
  {
    return [
      ['name' => 'Amara Okafor', 'role' => 'Co-founder & CEO', 'initials' => 'AO', 'avatar_color' => 'rose'],
      ['name' => 'Liam Chen', 'role' => 'Co-founder & CTO', 'initials' => 'LC', 'avatar_color' => 'sky'],
      ['name' => 'Sofia Marchetti', 'role' => 'Head of Design', 'initials' => 'SM', 'avatar_color' => 'emerald'],
      ['name' => 'Noah Williams', 'role' => 'Lead Engineer', 'initials' => 'NW', 'avatar_color' => 'violet'],
    ];
  }
}
