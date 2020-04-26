<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Pd\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="email_already_taken")
 */
class User extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
    }
}