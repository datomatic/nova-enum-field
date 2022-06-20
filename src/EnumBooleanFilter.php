<?php

declare(strict_types=1);

namespace Datomatic\Nova\Fields\Enum;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;
use Datomatic\Nova\Fields\Enum\Traits\EnumFilterTrait;
use UnitEnum;

class EnumBooleanFilter extends BooleanFilter
{
    use EnumFilterTrait;

    public function __construct(public $name, protected string $column, protected string $class, protected array $default = [])
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

    public function default()
    {
        if (! empty($this->default)) {
            $this->default = collect($this->default)
                ->map(function ($value) {
                    return $value instanceof UnitEnum ? $value->value : $value;
                })->all();
        }

        return collect($this->default)->mapWithKeys(function ($option) {
            return [$option => true];
        })->all() + parent::default();
    }
}
