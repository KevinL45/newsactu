<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{  


    private const EMAIL ="kevinlokoka@gmail.com";
    private const PASSWORD ="lokoka";
    private ValidatorInterface $validator;

    protected function setUp():void
    {
        $kernel = self::bootKernel();

        $this->validator = $kernel->getContainer()->get('validator');
    }

    public function testUserValid(): void
    {
        $user = new User();

        //Ajoute du mail et mot de passe
        $user
        ->setEmail(self::EMAIL)
        ->setPassword(self::PASSWORD);

        $this->getValidationErrors($user,0);

    }

    private function getValidationErrors(User $user, int $number): ConstraintViolationList
    {
        //Verifie les erreurs de l'utilisateur
        $errors = $this->validator->validate($user);

        //Nombre d'erreur
        $this->assertCount($number,$errors);

        //Retourne l'erreur
        return $errors;
    
    }





}
