<?php

namespace Suleymanozev\EnumField;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Nova;

class EnumBooleanFilter extends BooleanFilter
{
    protected string $column;
    protected string $class;

    public function __construct(string $column, string $class)
    {
        $this->column = $column;
        $this->class = $class;
    }

    public function name($name = null)
    {
        if (is_null($name)) {
            return $this->name ?: Nova::humanize(Str::camel($this->column));
        }

        $this->name = $name;

        return $this;
    }

    public function apply(Request $request, $query, $value)
    {
        $enums = array_keys(array_filter($value));

        if (empty($enums)) {
            return $query;
        }

        return $query->whereIn($this->column, $enums);
    }

    public function options(Request $request)
    {
        return collect(call_user_func([$this->class, 'cases']))->pluck('value', 'name')->toArray();
    }
}
