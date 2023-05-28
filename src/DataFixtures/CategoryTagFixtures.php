<?php

namespace App\DataFixtures;

use App\Entity\Post\Category;
use App\Entity\Post\Tag;
use App\Repository\Post\PostRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryTagFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private PostRepository $postRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $posts = $this->postRepository->findAll();

        // Category
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($faker->words(1, true) . ' ' . $i)
                ->setDescription(
                    mt_rand(0, 1) === 1 ? $faker->realText(254) : null
                );

            $manager->persist($category);
            $categories[] = $category;
        }

        foreach ($posts as $post) {
            for ($i = 0; $i < mt_rand(1, 5); $i++) {
                $post->addCategory(
                    $categories[mt_rand(0, count($categories) - 1)]
                );
            }
        }

        // Tag
        $tags = [];
        for ($i = 0; $i < 10; $i++) {
            $tag = new Tag();
            $tag->setName($faker->words(1, true) . ' ' . $i)
                ->setDescription(
                    mt_rand(0, 1) === 1 ? $faker->realText(254) : null
                );

            $manager->persist($tag);
            $tags[] = $tag;
        }

        foreach ($posts as $post) {
            for ($i = 0; $i < mt_rand(1, 5); $i++) {
                $post->addTag(
                    $tags[mt_rand(0, count($tags) - 1)]
                );
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [PostFixtures::class];
    }
}
