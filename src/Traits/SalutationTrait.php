<?php

namespace Gyaaniguy\Upworktest\Traits;

trait SalutationTrait
{
    protected string $salutation = '';

    public function getSalutation(): string
    {
        return $this->salutation;
    }

    /**
     * @param string $salutation
     */
    public function setSalutation(string $salutation): void
    {
        $this->salutation = $salutation;
    }
}