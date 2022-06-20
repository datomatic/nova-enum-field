<?php

declare(strict_types=1);

namespace Datomatic\Nova\Fields\Enum\Traits;

trait EnumPropertiesTrait
{
    private string $property = 'description';
    private ?array $cases = null;

    public function property(string $property): static
    {
        $this->property = $property;

        return $this;
    }

    public function cases(array $cases): static
    {
        $this->cases = $cases;

        return $this;
    }
}
