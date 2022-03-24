<?php

namespace Suleymanozev\EnumField;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Nova;

class EnumFilter extends Filter
{
    protected string $column;
    protected string $class;
    protected ?\UnitEnum $default = null;

    public function __construct(string $name, string $column, string $class, ?\UnitEnum $default = null)
    {
        $this->column = $column;
        $this->class = $class;
        $this->default = $default;
        $this->name = $name;
    }

    /**
     * Apply the filter to the given query.
     *
     * @param Request $request
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    public function apply(Request $request, $query, $value): Builder
    {
        return $query->where($this->column, $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param Request $request
     * @return array
     */
    public function options(Request $request): array
    {
        return collect(call_user_func([$this->class, 'cases']))->pluck('value', 'name')->toArray();
    }

    public function key(): string
    {
        return 'enum_filter_' . $this->column;
    }

    public function default()
    {
        if (is_null($this->default)) {
            return parent::default();
        }
        return $this->default->value;
    }

}
