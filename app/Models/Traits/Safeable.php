<?php
/**
 * Created by PhpStorm.
 * User: fomvasss
 * Date: 14.01.19
 * Time: 23:02
 */

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait Safeable
{
    protected $safeableEvents = [
        //'deleting', 'creating' //TODO
    ];

    protected static function bootHasSafe()
    {
        //static::updating(function (Model $model) {
        //    $model->checkIsSafe() ? abort(403) : null;
        //});
        static::deleting(function (Model $model) {
            if (! app()->runningInConsole()) {
                $model->checkIsSafe() ? abort(403) : null;
            }
        });
    }

    public function checkIsSafe(): bool
    {
        if ($this->safe !== null) {
            return (bool) $this->safe;
        }

        return false;
    }
}