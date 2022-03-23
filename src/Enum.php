<?php

namespace Suleymanozev\EnumField;

use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Enum extends Select
{
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);
        $this->resolveUsing(
            function ($value) {
                return $value instanceof \UnitEnum ? $value->name : $value;
            }
        );

        $this->displayUsing(
            function ($value) {
                return $value instanceof \UnitEnum  ? $value->name : $value;
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
        $this->options(collect($class::cases())->pluck('name', 'value'));
        return $this;
    }
}
