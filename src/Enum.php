<?php

declare(strict_types=1);

namespace Datomatic\Nova\Fields\Enum;

use Datomatic\Nova\Fields\Enum\Traits\EnumPropertiesTrait;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use UnitEnum;

class Enum extends Select
{
    use EnumPropertiesTrait;

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);
        $this->resolveUsing(
            function ($value) {
                return $value instanceof UnitEnum ? $value->value : $value;
            }
        );

        $this->fillUsing(
            function (NovaRequest $request, $model, $attribute, $requestAttribute) {
                if ($request->exists($requestAttribute)) {
                    $model->{$attribute} = $request[$requestAttribute];
                }
            }
        );
    }

    public function attach($class): static
    {
        try {
            $this->options(collect($class::dynamicAsSelect($this->property, $this->cases)));
        } catch (\Exception) {
            $this->options(collect($class::cases())->pluck('name', 'value'));
        }

        $this->displayUsing(
            function ($value) use ($class) {
                $parsedValue = $class::tryFrom($value);
                try {
                    return $parsedValue->description();
                } catch (\Exception) {
                    if ($parsedValue instanceof UnitEnum) {
                        return $parsedValue->name;
                    }

                    return $value;
                }
            }
        );

        $this->rules = [new \Illuminate\Validation\Rules\Enum($class)];

        return $this;
    }
}
