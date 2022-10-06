<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Client;
use Faker\Factory;

class ClientFixture extends Fixture
{
    private $faker;

    public function __construct() {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 27; $i++) {
            $manager->persist($this->getClient());
        }
        $manager->flush();    
    }

    private function getClient(){
        $client = new Client();

        $client->setName($this->faker->firstName());
        $client->setLastname($this->faker->lastName());
        $client->setPhone($this->faker->phoneNumber());
        $client->setEmail($this->faker->email());
        $client->setBirthday($this->faker->dateTime($max = 'now'));

        return $client;
    }
}
