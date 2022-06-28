<?php

declare(strict_types=1);

namespace Datomatic\Nova\Fields\Enum\Traits;

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
        if (method_exists($this->class, 'dynamicAsSelect')) {
            try {
                return array_flip($this->class::dynamicAsSelect($this->property, $this->cases));
            } catch (\Exception) {
            }
        }

        return collect(call_user_func([$this->class, 'cases']))->pluck('value', 'name')->toArray();
    }
}
