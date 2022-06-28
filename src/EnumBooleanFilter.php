<?php

declare(strict_types=1);

namespace Datomatic\Nova\Fields\Enum;

use BackedEnum;
use Datomatic\Nova\Fields\Enum\Traits\EnumFilterTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Filters\BooleanFilter;
use UnitEnum;

class EnumBooleanFilter extends BooleanFilter
{
    use EnumFilterTrait;

    /** @var array<UnitEnum>  */
    protected array $default = [];

    public function __construct(protected string $column, protected string $class)
    {
    }

    public function apply(Request $request, $query, $value): Builder
    {
        $enums = array_keys(array_filter($value));

        if (empty($enums)) {
            return $query;
        }

        return $query->whereIn($this->column, $enums);
    }

    public function key(): string
    {
        return 'enum_boolean_filter_' . $this->column;
    }

    public function default(null|array|UnitEnum $default = null): self|array|null
    {
        if (is_null($default)) {
            return collect(Arr::wrap($this->default))->mapWithKeys(function ($enum) {
                    return [($enum instanceof BackedEnum ? $enum->value : $enum->name) => true];
                })->all() + parent::default();
        }

        $this->default = Arr::wrap($default);

        return $this;
    }
}
