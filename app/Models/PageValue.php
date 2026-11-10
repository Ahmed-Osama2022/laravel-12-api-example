<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageValue extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'page_id',
    'icon',
    'title',
    'text',
    'sort_order',
  ];

  /**
   * The page this value belongs to.
   */
  public function page(): BelongsTo
  {
    return $this->belongsTo(Page::class);
  }
}
