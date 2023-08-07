<?php

declare(strict_types=1);

namespace Datomatic\Nova\Fields\Enum\Traits;

use BackedEnum;
use Datomatic\LaravelEnumHelper\Exceptions\TranslationMissing;
use Illuminate\Http\Request;
use Laravel\Nova\Nova;

trait EnumFilterTrait
{
    use EnumPropertiesTrait;

    public function name($name = null)
    {
        if (is_null($name)) {
            return $this->name ?: Nova::humanize($this->column);
        }

        $this->name = $name;

        return $this;
    }

    public function options(Request $request): array
    {
        $key = (is_subclass_of($this->class, BackedEnum::class)) ? 'value' : 'name';

        if (method_exists($this->class, 'dynamicByKey')) {
            try {
                return array_flip($this->class::dynamicByKey('value', $this->property, $this->cases));
            } catch (TranslationMissing $e) {
                throw $e;
            } catch (\Exception) {
            }
        } elseif (method_exists($this->class, $this->property)) {
            return collect(call_user_func([$this->class, 'cases']))
                ->mapWithKeys(fn($case) => [
                    $case->{$this->property}() => $case->{$key},
                ])
                ->toArray();
        }

        return collect(call_user_func([$this->class, 'cases']))->pluck($key, 'name')->toArray();
    }
}
