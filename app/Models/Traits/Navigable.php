<?php
/**
 * Created by PhpStorm.
 * User: fomvasss
 * Date: 04.02.19
 * Time: 11:13
 */

namespace App\Models\Traits;

trait Navigable
{
    public function previous(array $where = [])
    {
        $sortField = $this->getKeyName();
        return static::where($sortField, '<', $this->getKey())->where($where)->orderByDesc($sortField)
            ->first();
    }

    public function next(array $where = [])
    {
        $sortField = $this->getKeyName();
        return static::where($sortField, '>', $this->getKey())->where($where)->orderBy($sortField)
            ->first();
    }
}