<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as EloquentUser;

trait SingleTableInheritance
{
    /**
     * @var string
     */
    protected $inheritanceAt = 'type';

    /**
     * @return bool
     */
    protected static function isImediateChildOfEloquent()
    {
        return in_array(get_parent_class(static::class), [
            EloquentUser::class, Model::class
        ]);
    }

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

    /**
     * Adds the single table inheritance global scope for all child models.
     */
    public static function bootSingleTableInheritance()
    {
        if (! self::isImediateChildOfEloquent()) {
            static::addGlobalScope(new SingleTableInheritanceScope(static::class));
        }
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes)
    {
        // Adds the default type when creating child models.
        if (!static::isImediateChildOfEloquent() && !isset($attributes['type'])) {
            $attributes += ['type' => static::class];
        }

        return parent::fill($attributes);
    }
}