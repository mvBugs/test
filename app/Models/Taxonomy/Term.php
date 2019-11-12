<?php

namespace App\Models\Taxonomy;

use App\Models\Traits\HasMedia\HasMedia;
use App\Models\Traits\HasMedia\HasMediaTrait;
use App\Models\Traits\Safeable;
use App\Models\Traits\UrlAliasable\UrlAliasable;
use App\Models\Traits\UrlAliasable\UrlAliasableContract;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Taxonomy\Term
 *
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Taxonomy\Term[] $children
 * @property-read mixed|null|string $slug
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read \App\Models\Taxonomy\Term $parent
 * @property-write mixed $parent_id
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Taxonomy\Term[] $terms
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Taxonomy\Term[] $termsByMany
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Taxonomy\Term[] $termsToMany
 * @property-read \App\Models\Taxonomy\Vocabulary $txVocabulary
 * @property-read \Fomvasss\UrlAliases\Models\UrlAlias $urlAlias
 * @property-read \Illuminate\Database\Eloquent\Collection|\Fomvasss\UrlAliases\Models\UrlAlias[] $urlAliases
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taxonomy\Vocabulary[] $vocabulariesByMany
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taxonomy\Vocabulary[] $vocabulariesToMany
 * @method static \Illuminate\Database\Eloquent\Builder|\Fomvasss\Taxonomy\Models\Term byTaxonomies($taxonomies, $termKey = 'id')
 * @method static \Illuminate\Database\Eloquent\Builder|\Fomvasss\Taxonomy\Models\Term byVocabulary($vocabulary)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fomvasss\Taxonomy\Models\Term d()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy\Term isPublish()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\Taxonomy\Term newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\Taxonomy\Term newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\Taxonomy\Term query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fomvasss\Taxonomy\Models\Term termsByVocabulary($vocabulary)
 * @mixin \Eloquent
 */
class Term extends \Fomvasss\Taxonomy\Models\Term implements HasMedia, UrlAliasableContract
{
    use UrlAliasable, HasMediaTrait, Safeable;

    protected $mediaFieldsSingle = ['image', 'file'];

    protected $mediaFieldsMultiple = ['images', 'files'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('weight', function (Builder $builder) {
            $builder->orderBy('weight', 'asc')->orderBy('id', 'asc');
        });
    }


    public function scopeIsPublish($query)
    {
        return $query->where('publish', 1);
    }


    /**
     * @return mixed|null|string
     */
    public function getSlugAttribute()
    {
        return $this->system_name ?? '';
    }

    /**
     * @return string
     */
    public function generateUrlAlias(string $rawAliasPath = null): string
    {
        return $this->getUniqueAliasedPathForNestedEntity($this);
    }

    /**
     * @return string
     */
    public function generateUrlSource(): string
    {
//        if (in_array($this->vocabulary, ['product_categories'])) {
//            return trim(route('category.show', $this, false), '/');
//        }

        return '';
    }

    public function generateSystemName()
    {
        if (in_array($this->vocabulary, ['order_statuses', 'payment_statuses', 'product_categories'])) {
            $slugSeparator = $this->vocabulary == 'product_categories' ? '-' : '_';
            return EUS::setEntity($this)
                ->setRawStr($this->name)
                ->setFieldName('system_name')
                ->setSlugSeparator($slugSeparator)
                ->get();
        }

        return '';
    }

    public function statusAdminStr()
    {
        $styleClass = $this->options['admin_style'] ?? "label label-success";

        return "<sbodypan class='$styleClass'> $this->name </sbodypan>";
    }

    public function customMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('table')
            ->format('jpg')->quality(93)
            ->fit('crop', 360, 257);
    }
}
