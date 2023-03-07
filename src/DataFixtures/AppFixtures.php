<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 50; $i++){
            $ingredient= new Ingredient();
            $ingredient->setName('Ingredient'. $i)
                ->setPrice(random_int(0, 100));
            $manager->persist($ingredient);
        }


        $manager->flush();
    }
}
