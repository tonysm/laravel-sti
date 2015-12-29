<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class SingleTableInheritanceScope implements Scope
{
    /**
     * @var string
     */
    private $scopedClass;

    /**
     * @var string
     */
    private $inheritanceAttribute;

    /**
     * SingleTableInheritanceScope constructor.
     *
     * @param string $inheritanceAttribute
     * @param string $scopedClass
     */
    public function __construct($inheritanceAttribute, $scopedClass)
    {
        $this->scopedClass = $scopedClass;
        $this->inheritanceAttribute = $inheritanceAttribute;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where($this->inheritanceAttribute, '=', $this->scopedClass);
    }
}