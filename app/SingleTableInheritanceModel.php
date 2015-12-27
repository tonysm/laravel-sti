<?php

namespace App;

trait SingleTableInheritanceModel
{
    /**
     * @var string
     */
    protected $inheritanceAt = 'type';

    /**
     * @param array $attributes
     * @param null $connection
     * @return mixed
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $attributes = (array) $attributes;
        $childClass = array_get($attributes, $this->inheritanceAt, null);

        if ($this->inheritanceAt && $childClass && $childClass != static::class) {
            if (!class_exists($childClass)) {
                throw new \RuntimeException("The class {$childClass} does not exist");
            }

            $model = new $childClass($attributes);

            return $model->newFromBuilder($attributes, $connection);
        }

        return parent::newFromBuilder($attributes, $connection);
    }
}