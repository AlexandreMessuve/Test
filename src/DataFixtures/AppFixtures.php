<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class AppFixtures extends Fixture
{
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
        $users = [];
        for ($i = 0; $i < 15; $i++){
            $user = new User();
            $user->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setUsername($faker->username())
                ->setEmail($faker->email())
                ->setBio(random_int(0,1) === 1 ? $faker->text(): null)
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password')
                ->setDateNaissance(date_create($faker->date()));
            $users[] = $user;
            $manager->persist($user);
        }

        for ($i = 1; $i <= 50; $i++) {
            $comments = new Comments();
            $comments->setContent($faker->realText())
                    ->setUser($users[random_int(0, count($users) -1)])
                    ->setAutor($users[random_int(0, count($users) -1)]);
            $manager->persist($comments);
        }
        //Ingredients
        $ingredients = [];
        for ($i = 1; $i <= 50; $i++){
            $ingredient= new Ingredient();
            $ingredient->setName($faker->word())
                ->setPrice(random_int(0, 100))
                ->setUser($users[random_int(0, count($users) -1)]);
            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }


        //Recipes
        for ($j = 1; $j <= 25; $j++){
            $recipe = new Recipe();
            $recipe->setName($faker->word())
                ->setStepDescription($faker->text(300))
                ->setPrice(random_int(0,1) === 1 ? random_int(1, 1000): null)
                ->setDifficulty(random_int(0,1) === 1 ? random_int(1, 5): null)
                ->setNumberOfPer(random_int(0,1) === 1 ? random_int(1, 50): null)
                ->setMinute(random_int(0,1) === 1 ? random_int(1, 1440): null)
                ->setIsFavorite(random_int(0,1) === 1 ? true : false)
                ->setUser($users[random_int(0, count($users) -1)]);

            for ($k = 0; $k <random_int(5, 15); $k++){
                $recipe->addIngredientsList($ingredients[random_int(0, count($ingredients) -1)]);
            }


            $manager->persist($recipe);
        }


        $manager->flush();
    }
}
