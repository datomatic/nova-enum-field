<?php

namespace Suleymanozev\EnumField;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Nova;
use Suleymanozev\EnumField\Traits\EnumFilterTrait;

class EnumFilter extends Filter
{
    use EnumFilterTrait;

    public function __construct(public $name, protected string $column, protected string $class, protected ?\UnitEnum $default = null)
    {
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
