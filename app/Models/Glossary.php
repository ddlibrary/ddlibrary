<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $string, string $string1)
 * @method static where(string $string, $id)
 *
 * @property mixed name_en
 * @property mixed name_fa
 * @property mixed name_ps
 * @property mixed subject
 * @property bool|mixed flagged_for_review
 */
class Glossary extends Model
{
    use HasFactory;

    protected $table = 'glossary';

    protected $guarded = [];

    public function glossarySubject(): BelongsTo
    {
        return $this->belongsTo(GlossarySubject::class, 'subject', 'id');
    }
}
