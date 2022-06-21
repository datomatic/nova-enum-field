<?php

declare(strict_types=1);

namespace Datomatic\Nova\Fields\Enum\Traits;

use Illuminate\Http\Request;

trait EnumFilterTrait
{
    use EnumPropertiesTrait;

    public function options(Request $request): array
    {
        if (method_exists($this->class, 'dynamicAsSelect')) {
            try {
                return array_flip($this->class::dynamicAsSelect($this->property, $this->cases));
            } catch (\Exception) {
            }
        }

        return collect(call_user_func([$this->class, 'cases']))->pluck('value', 'name')->toArray();
    }
}
