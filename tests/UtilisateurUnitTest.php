<?php

namespace App\Tests;

use App\Entity\Utilisateur;
use PHPUnit\Framework\TestCase;

class UtilisateurUnitTest extends TestCase
{
    public function testIsTrue()
    {
        $user = new Utilisateur();

        $user->setEmail('true@test.com')
            ->setPassword('password')
            ->setNomUser('nom')
            ->setPrenomUser('prenom');

        $this->assertTrue($user->getEmail() === 'true@test.com');
        $this->assertTrue($user->getPassword() === 'password');
        $this->assertTrue($user->getNomUser() === 'nom');
        $this->assertTrue($user->getPrenomUser() === 'prenom');
    }
    public function testIsFalse()
    {
        $user = new Utilisateur();

        $user->setEmail('true@test.com')
            ->setPassword('password')
            ->setNomUser('nom')
            ->setPrenomUser('prenom');

        $this->assertFalse($user->getEmail() === 'false@test.com');
        $this->assertFalse($user->getPassword() === 'false');
        $this->assertFalse($user->getNomUser() === 'false');
        $this->assertFalse($user->getPrenomUser() === 'false');
    }

    public function testIsEmpty()
    {
        $user = new Utilisateur();

        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPassword());
        $this->assertEmpty($user->getNomUser());
        $this->assertEmpty($user->getPrenomUser());

    }
}
