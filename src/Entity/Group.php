<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Pd\UserBundle\Model\Group as BaseGroup;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user_group")
 * @ORM\Entity
 * @UniqueEntity(fields="name", message="group_already_taken")
 */
class Group extends BaseGroup
{

}