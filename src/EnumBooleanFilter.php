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
    protected array $default;

    public function __construct(string $column, string $class, $default = [])
    {
        $this->column = $column;
        $this->class = $class;
        $this->default = $default;
    }

    public function name($name = null)
    {
        if (!is_null($name)) {
            $this->name = $name;
        }

        $this->name = $this->name ?: Nova::humanize(Str::camel($this->column));
        return $this->name;
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

    public function key(): string
    {
        return 'enum_boolean_filter_' . $this->column;
    }

    public function default()
    {
        if (!empty($this->default)) {
            $this->default = collect($this->default)
                ->map(function ($value, $key) {
                    return $value instanceof \UnitEnum ? $value->value : $value;
                })->all();
        }

        return collect($this->default)->mapWithKeys(function ($option) {
                return [$option => true];
            })->all() + parent::default();
    }
}
