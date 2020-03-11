<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function saveUser(User $user)
    {
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(null);

        $this->em->persist($user);
        $this->em->flush();
    }
}