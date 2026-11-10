<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageTimelineEntry extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'page_id',
    'year',
    'title',
    'text',
    'sort_order',
  ];

  /**
   * The page this timeline entry belongs to.
   */
  public function page(): BelongsTo
  {
    return $this->belongsTo(Page::class);
  }
}
