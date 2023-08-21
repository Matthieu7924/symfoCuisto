<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Recette;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private $faker;

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
            $faker = Factory::create('fr_FR');
        //ingredients
        $ingredients =[];
            for ($i=0; $i<50;$i++)
            {
                $ingredient = new Ingredient();
                // $ingredient->setName('Ingredient' . $i)
                $ingredient->setName($faker->word())
                ->setPrice(mt_rand(0,100));//Mersenne Twister
                $ingredients[] = $ingredient;
                $manager->persist($ingredient);
            }

        //recettes
            for($j=0; $j<25;$j++)
            {
                $recette = new Recette();
                $recette->setName($faker->word())
                        ->setPrice(mt_rand(0,1) ==1 ? mt_rand(1,1440) : null)
                        ->setNbPeople(mt_rand(0,1) ==1 ? mt_rand(1, 5) :null)
                        ->setDescription($faker->text(300))
                        ->setPrice(mt_rand(0,1) ==1 ? mt_rand(1,1000) : null)
                        ->setIsFavorite(mt_rand(0,1) ==1 ? true : false);
            
                for($k=0; $k<mt_rand(5,15);$k++)
                {
                    $recette->addIngredient($ingredients[mt_rand(0, count($ingredients) -1)]);
                }
            
                $manager->persist($recette);
            }
            
            //Users
            for($i = 0; $i<10; $i++){
                $user = new User();
                $user->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0,1)===1? $this->faker->firstName():null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

                // $hashPassword = $this->hasher->hashPassword($user,'password');
                // $user->setPassword($hashPassword);
                
                $manager->persist($user);
            }
            
        $manager->flush($ingredient);
    }
    
}