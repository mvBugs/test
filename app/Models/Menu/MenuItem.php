<?php

namespace App\Models\Menu;

use App\Models\Traits\HasMedia\HasMedia;
use App\Models\Traits\HasMedia\HasMediaTrait;
use Fomvasss\UrlAliases\Facades\UrlAlias;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

/**
 * App\Models\Menu\MenuItem
 *
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Menu\MenuItem[] $children
 * @property \Illuminate\Contracts\Routing\UrlGenerator|string $path
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read \App\Models\Menu\Menu $menu
 * @property-read \App\Models\Menu\MenuItem $parent
 * @property-write mixed $parent_id
 * @property-read \Fomvasss\UrlAliases\Models\UrlAlias $urlAlias
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu\MenuItem byMenu($systemName)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu\MenuItem d()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\Menu\MenuItem newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\Menu\MenuItem newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\Menu\MenuItem query()
 * @mixin \Eloquent
 */
class MenuItem extends Model implements HasMedia
{
    use NodeTrait, HasMediaTrait;

    /** @var int URL-path */
    const PATH_TYPE_PATH = 1;

    /** @var int URL-alias (related id) */
    const PATH_TYPE_URL_ALIAS = 2;

    protected $guarded = ['id'];

    protected $casts = [
        'data' => 'array',
    ];

    protected $mediaFieldsSingle = [
        'image',
    ];

    protected $mediaFieldsValidation = [
        'image' => 'nullable|image|file|max:1024',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (Model $model) {
            \Illuminate\Support\Facades\Cache::forget("menu:".$model->menu->system_name);
        });
        static::deleted(function (Model $model) {
            \Illuminate\Support\Facades\Cache::forget("menu:".$model->menu->system_name);
        });

        static::addGlobalScope('weight', function (Builder $builder) {
            $builder->orderBy('weight', 'asc')->orderBy('id', 'asc');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Relation with url-alias
     * https://github.com/fomvasss/laravel-url-aliases
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function urlAlias()
    {
        return $this->belongsTo(config('url-aliases.model', \Fomvasss\UrlAliases\Models\UrlAlias::class));
    }

    /**
     * @param $query
     * @param string $systemName
     * @return mixed
     */
    public function scopeByMenu($query, string $systemName)
    {
        return $query->whereHas('menu', function ($items) use ($systemName) {
            return $items->where('system_name', $systemName);
        });
    }

    /**
     * @param $value
     */
    public function setPathAttribute($value)
    {
        $this->attributes['path'] = rtrim(Str::replaceFirst(Request::root(), '', $value), '/');
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getPathAttribute()
    {
        if ($this->attributes['path']) {
            return url($this->attributes['path']);
        }

        return '';
    }

    /**
     * @return string
     */
    public function getTargetHtml()
    {
        return $this->target ? 'target='.$this->target : '';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getUrl()
    {
        if ($this->path_type == self::PATH_TYPE_PATH && $this->path) {
            return url($this->path);
        } elseif ($this->path_type == self::PATH_TYPE_URL_ALIAS && $this->urlAlias) {
            return url($this->urlAlias->alias);
        }

        return '';
    }

    /**
     * @param null $item
     * @return bool
     */
    public function isActive($item = null): bool
    {
        $item = $item ?: $this;

        $urlAlias = $_SERVER['REQUEST_URI'];

        if (! empty($item->data['pattern_url'])) {
            $pattern = (trim($item->data['pattern_url'], '/'));
            try {
                return preg_match("/$pattern/", Request::fullUrl()) || preg_match("/$pattern", $urlAlias);
            } catch (\Exception $e) {
                \Log::error(__METHOD__ . " Menu-item active url pattern is not correct!");
            }
        }

        if ($item->getUrl() && ($pattern = trim($item->getUrl(), '/'))) {
            return (strpos(Request::url(), $pattern) !== false) || (strpos($urlAlias, $pattern) !== false);
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isTreeviewOpen(): bool
    {
        foreach ($this->descendants as $descendant) {
            if ($this->isActive($descendant)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param null $item
     * @return bool
     */
    public function hasAccessToItem($item = null): bool
    {
        $item = $item ?: $this;

        if (!config('menu.check_permissions', true)) {
            return true;
        }

        if (Auth::check() && ($user = Auth::user())) {

            if ($user->hasRole('admin')) { // use spatie/laravel-permission
                return true;
            }

            if (isset($item->data['permissions'])) {
                $permissions =  is_array($item->data['permissions']) ? $item->data['permissions'] : [$item->data['permissions']];

                if (count($permissions)) {
                    return $user->hasAnyPermission($permissions); // use spatie/laravel-permission
                }
            }
        }

        return false;
    }

    /**
     * @param null $item
     * @return bool
     */
    public function hasAccessToTreeview($item = null): bool
    {
        $item = $item ?: $this;

        if (!config('menu.check_permissions', true)) {
            return true;
        }

        if (Auth::check() && ($user = Auth::user())) {

            if ($user->hasRole('admin')) {
                return true;
            }

            if (! empty($item->data['permissions']) || ! empty($item->data['roles'])) {
                if ($this->hasAccessToItem($item) ) {
                    return true;
                }
            }

            foreach ($item->descendants as $descendant) {
                if ($this->hasAccessToItem($descendant) ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param null $item
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function itemSuffix($item = null): string
    {
        $item = $item ?: $this;

        $suffixes = config('menu.suffix_classes', []);

        $suffix = $item->data['suffix'] ?? null;

        if ($suffix && isset($suffixes[$suffix]) && class_exists($suffixes[$suffix])) {
            return app()->make($suffixes[$suffix])->get($item);
        }

        return '';
    }
}
