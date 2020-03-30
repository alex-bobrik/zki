<?php


namespace App\Service;


use App\Entity\LabMaterial;
use App\Entity\LabResult;
use App\Entity\TestResult;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $em;

    private $passwordEncoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createUser(User $user)
    {
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword('1');
        $user->setPassword($this->encodePassword($this->passwordEncoder, $user));


        $this->em->persist($user);
        $this->em->flush();
    }

    public function encodePassword(UserPasswordEncoderInterface $passwordEncoder, User $user): string
    {
        return $passwordEncoder->encodePassword($user, $user->getPassword());
    }

    public function updateUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

//    /**
//     * @param User $user
//     * @return Collection|LabResult[]
//     */
    public function getStudentLabsStats(User $user): array
    {
        return $this->em->getRepository(LabResult::class)->findBy(['user' => $user]);
//        dump($this->em->getRepository(LabResult::class)->findBy(['user' => $user])); die;
    }

//    /**
//     * @param User $user
//     * @return Collection|TestResult[]
//     */
    public function getStudentTestsStats(User $user): array
    {

        return $this->em->getRepository(TestResult::class)->findBy(['students' => $user]);
    }
}