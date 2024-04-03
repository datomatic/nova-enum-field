<?php

declare(strict_types=1);

namespace Datomatic\Nova\Fields\Enum;

use BackedEnum;
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
                if($value instanceof BackedEnum){
                    return $value->value;
                }
                return $value instanceof UnitEnum ? $value->name : $value;
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
        if (is_callable($class)) {
            $class = $class();
        }

        $key = (is_subclass_of($class, BackedEnum::class)) ? 'value' : 'name';

        if (method_exists($class, 'dynamicByKey')) {
            $this->options(collect($class::dynamicByKey('value', $this->property, $this->cases)));
        } elseif (method_exists($class, $this->property)) {
            $this->options(collect($class::cases())
                ->mapWithKeys(fn ($case) => [
                    $case->{$key} => ['value' => $case->{$key}, 'label' => $case->{$this->property}()],
                ])
                ->values()
                ->all());
        } else {
            $this->options(collect($class::cases())->pluck('name', $key));
        }

        $this->displayUsing(
            function ($value) use ($class) {
                if (is_null($value)) {
                    return null;
                }
                if ($value instanceof UnitEnum) {
                    $parsedValue = $value;
                } else {
                    $rEnum = new \ReflectionEnum($class);

                    if ($rEnum->getBackingType()?->getName() === 'string') {
                        $parsedValue = $class::tryFrom((string) $value);
                    } else {
                        $parsedValue = $class::tryFrom((int) $value);
                    }
                }
                if (method_exists($class, $this->property)
                    || in_array('Datomatic\LaravelEnumHelper\LaravelEnumHelper', class_uses($class))) {
                    return $parsedValue->{$this->property}();
                }

                if ($parsedValue instanceof UnitEnum) {
                    return $parsedValue->name;
                }

                return $value;
            }
        );

        $this->rules[] = new \Illuminate\Validation\Rules\Enum($class);

        if ($this->nullable) {
            $this->rules[] = 'nullable';
        }

        return $this;
    }
}
