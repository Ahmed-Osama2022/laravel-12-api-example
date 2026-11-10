<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Models\Page;

class AboutController extends Controller
{
  /**
   * Return the about page document consumed by the public About view.
   *
   * The page is a singleton keyed by its slug, so a missing record is a real
   * 404 (unseeded environment) rather than an empty payload.
   */
  public function __invoke(): AboutResource
  {
    $page = Page::query()
      ->with(['stats', 'values', 'timelineEntries', 'teamMembers'])
      ->where('slug', 'about')
      ->firstOrFail();

    return new AboutResource($page);
  }
}
