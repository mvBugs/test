<?php

namespace App\Models\Menu;

use App\Models\Traits\Safeable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Menu\Menu
 *
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Menu\MenuItem[] $items
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu\Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu\Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu\Menu query()
 * @mixin \Eloquent
 */
class Menu extends Model
{
    use Safeable;

    protected $guarded = ['id'];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Default cache time, sec.
     *
     * @var int
     */
    protected $cacheTime = 60;

    protected static function boot()
    {
        parent::boot();

        static::saved(function (Model $model) {
            \Illuminate\Support\Facades\Cache::forget("menu:$model->system_name");
        });
        static::deleted(function (Model $model) {
            \Illuminate\Support\Facades\Cache::forget("menu:$model->system_name");
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }

    /**
     * @param string $systemName
     * @return mixed
     */
    public static function findByName(string $systemName)
    {
        return self::where('system_name', $systemName)->first();
    }

    /**
     * @param string $bladeTemplate
     * @return array|string
     * @throws \Throwable
     */
    public function render(string $bladeTemplate)
    {
        if ($this->cache) {
            $menuItems = \Illuminate\Support\Facades\Cache::remember("menu:$this->system_name", $this->cache, function () {
                return $this->items()->with('descendants'/*, 'urlAlias'*/)->get();
            });

        } else {
            $menuItems = $this->items()->with('descendants'/*, 'urlAlias'*/)->get();
        }

        return view($bladeTemplate, ['menu' => $this, 'menuItems' => $menuItems])->render();
    }

    /**
     * @param string $systemName
     * @param string $bladeTemplate
     * @return string
     */
    public static function renderByName(string $systemName, string $bladeTemplate)
    {
        if ($menu = self::findByName($systemName)) {
            return $menu->render($bladeTemplate);
        }

        return '';
    }
}
