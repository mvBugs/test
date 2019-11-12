<?php

namespace App\Models\Taxonomy;

/**
 * App\Models\Taxonomy\Vocabulary
 *
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Taxonomy\Term[] $terms
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Taxonomy\Term[] $termsByMany
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Taxonomy\Term[] $termsToMany
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taxonomy\Vocabulary[] $vocabulariesByMany
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taxonomy\Vocabulary[] $vocabulariesToMany
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy\Vocabulary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy\Vocabulary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy\Vocabulary query()
 * @mixin \Eloquent
 */
class Vocabulary extends \Fomvasss\Taxonomy\Models\Vocabulary
{
    //...
}
