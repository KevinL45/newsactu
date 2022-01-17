<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    private SluggerInterface $sluggerInterface;

    public function __construct(SluggerInterface $sluggerInterface)
    {
        $this->sluggerInterface = $sluggerInterface;
    }
    public function load(ObjectManager $manager): void
    {
        $categories = ['Politique','Cinéma','Economie','Environement','Sport','Santé'];

        foreach ($categories as $cat) {
            $category = new Category();

            $category->setName($cat);
            $category->setAlias($this->sluggerInterface->slug($cat));

        }

        $manager->flush();
    }
}
