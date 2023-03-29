<?php

namespace Gyaaniguy\Upworktest\Model\Users\Guardians;

use Gyaaniguy\Upworktest\Model\Users\AbstractUser;
use Gyaaniguy\Upworktest\Traits\SalutationTrait;

abstract class AbstractGuardian extends AbstractUser
{
    use SalutationTrait;

    public function getFullName(): string
    {
        return $this->getSalutation() . ' ' . parent::getFullName();
    }

}