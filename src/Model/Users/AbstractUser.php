<?php

namespace Gyaaniguy\Upworktest\Model\Users;

use Exception;
use Gyaaniguy\Upworktest\Helpers\Validation;

abstract class AbstractUser
{
    protected int $userId;
    protected string $first_name;
    protected string $last_name;
    protected string $email;
    protected string $profile_photo;

    private string $defaultProfilePic = '/images/default-profile-pic.jpg';

    public function __construct($id, $first_name, $last_name, $email, $profile_photo = '')
    {
        $this->userId = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->setProfilePicture($profile_photo);
    }

    /**
     * @param $profile_photo
     * @return mixed|string
     */
    public function setProfilePicture(string $profile_photo)
    {
        $this->profile_photo = $profile_photo ?: $this->defaultProfilePic;
    }

    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @throws Exception
     */
    public function save(): bool
    {
        if (!$this->validateUser()) {
            throw new Exception("Could not validate User");
        }
        // Proceed to save to DB
        return true;
    }

    public function validateUser(): bool
    {
        if (!Validation::hasJpg($this->getProfilePhoto())) {
            return false;
        }
        if (!Validation::isEmail($this->email)) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getProfilePhoto(): string
    {
        return $this->profile_photo;
    }
}