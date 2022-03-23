<?php

namespace Suleymanozev\EnumField;

use Illuminate\Database\Eloquent\Builder;
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

    public function name($name = null): EnumBooleanFilter
    {
        if (!is_null($name)) {
            $this->name = $name;
        }

        $this->name = $this->name ?: Nova::humanize(Str::camel($this->column));

        return $this;
    }

    public function apply(Request $request, $query, $value): Builder
    {
        $enums = array_keys(array_filter($value));

        if (empty($enums)) {
            return $query;
        }

        return $query->whereIn($this->column, $enums);
    }

    public function options(Request $request): array
    {
        return collect(call_user_func([$this->class, 'cases']))->pluck('value', 'name')->toArray();
    }

    public function default(): EnumBooleanFilter
    {
        if (isset(func_get_args()[0])) {
            $this->default = collect(is_array(func_get_args()[0]) ? func_get_args()[0] : [func_get_args()[0]])
                ->map(function ($value, $key) {
                    return $value instanceof \UnitEnum ? $value->value : $value;
                })->all();

            return $this;
        }

        if (is_null($this->default)) {
            $this->default = parent::default();
            return $this;
        }

        $this->default = collect($this->default)->mapWithKeys(function ($option) {
                return [$option => true];
            })->all() + parent::default();
        return $this;
    }
}
