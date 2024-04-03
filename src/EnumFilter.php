<?php

declare(strict_types=1);

namespace Datomatic\Nova\Fields\Enum;

use Datomatic\Nova\Fields\Enum\Traits\EnumFilterTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use UnitEnum;

class EnumFilter extends Filter
{
    use EnumFilterTrait;

    protected ?UnitEnum $default = null;

    public function __construct(protected string $column, protected string $class)
    {
    }

    /**
     * Apply the filter to the given query.
     *
     * @param Builder $query
     */
    public function apply(Request $request, $query, $value): Builder
    {
        return $query->where($this->column, $value);
    }

    public function key(): string
    {
        return 'enum_filter_' . $this->column;
    }

    public function default(?UnitEnum $default = null): null|self|UnitEnum|string
    {
        if (is_null($default)) {
            return $this->default ?: parent::default();
        }

        $this->default = $default;

        return $this;
    }
}
