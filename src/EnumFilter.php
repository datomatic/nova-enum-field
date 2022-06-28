<?php

declare(strict_types=1);

namespace Datomatic\Nova\Fields\Enum;

use BackedEnum;
use Datomatic\Nova\Fields\Enum\Traits\EnumFilterTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class EnumFilter extends Filter
{
    use EnumFilterTrait;

    protected ?\UnitEnum $default = null;

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

    public function default()
    {
        if (isset(func_get_args()[0])) {
            $this->default = is_subclass_of(func_get_args()[0], \UnitEnum::class) ? func_get_args()[0] : null;

            return $this;
        }

        if (is_null($this->default)) {
            return parent::default();
        }

        return $this->default instanceof BackedEnum ? $this->default->value : $this->default->name;
    }
}
