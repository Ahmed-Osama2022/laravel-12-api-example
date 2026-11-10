<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageTeamMember extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'page_id',
    'name',
    'role',
    'initials',
    'avatar_color',
    'sort_order',
  ];

  /**
   * The page this team member belongs to.
   */
  public function page(): BelongsTo
  {
    return $this->belongsTo(Page::class);
  }
}
