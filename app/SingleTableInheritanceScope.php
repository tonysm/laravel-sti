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
     * SingleTableInheritanceScope constructor.
     *
     * @param string $scopedClass
     */
    public function __construct($scopedClass)
    {
        $this->scopedClass = $scopedClass;
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
        $builder->where('type', '=', $this->scopedClass);
    }
}