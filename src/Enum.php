<?php

namespace Datomatic\Nova\Fields\Enum;

use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use UnitEnum;

class Enum extends Select
{
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
        if(method_exists($class,'descriptionsArray')){
            $this->options(collect($class::descriptionsArray()));
        }else{
            $this->options(collect($class::cases())->pluck('name', 'value'));
        }

        $this->displayUsing(
            function ($value) use ($class) {
                $parsedValue = $class::tryFrom($value);
                if(method_exists($class,'description')){
                    return $parsedValue->description();
                }
                if ($parsedValue instanceof UnitEnum) {
                    return $parsedValue->name;
                }
                return $value;
            }
        );

        $this->rules = [new \Illuminate\Validation\Rules\Enum($class)];
        return $this;
    }
}
