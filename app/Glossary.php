<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $string, string $string1)
 * @method static where(string $string, $id)
 * @property mixed name_en
 * @property mixed name_fa
 * @property mixed name_ps
 * @property mixed subject
 * @property bool|mixed flagged_for_review
 */
class Glossary extends Model
{
    protected $table = "glossary";
    //
}
