<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * @Depends({"CategoryFixtures"})
 */

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        //Puis ici nous demandons à la Factory de nous fournir un Faker
        $faker = Factory::create();
        /**
         * L'objet $faker que tu récupère est l'outil qui va te permettre 
         * de te générer toutes les données que tu souhaites
         */
        for ($i = 0; $i < 50; $i++) {
            $program = new Program();
            //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base
            $program->setTitle($faker->sentence());
            $program->setSynopsis($faker->sentence(1, true));
            $program->setPoster($faker->imageUrl(640, 480));
            $program->setCountry($faker->country());
            $program->setYear($faker->year());
            $program->setCategory($this->getReference('category_' . rand(0, 14)));
            $manager->persist($program);
            $this->addReference('program_' . $i, $program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
