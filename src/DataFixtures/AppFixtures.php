<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
            $faker = Factory::create('fr_FR');
        
            for ($i=0; $i<50;$i++)
            {
                $ingredient = new Ingredient();
                // $ingredient->setName('Ingredient' . $i)
                $ingredient->setName($faker->word())
                ->setPrice(mt_rand(0,100));//Mersenne Twister
                $manager->persist($ingredient);
            }
        $manager->flush($ingredient);
    }
}
