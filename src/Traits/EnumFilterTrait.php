<?php

namespace Datomatic\Nova\Fields\Enum\Traits;

use Illuminate\Http\Request;

trait EnumFilterTrait
{
    public function options(Request $request): array
    {
        if (method_exists($this->class, 'descriptionsArray')) {
            return array_flip($this->class::descriptionsArray());
        }
        return collect(call_user_func([$this->class, 'cases']))->pluck('value', 'name')->toArray();
    }
}
