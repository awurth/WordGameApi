<?php

namespace App\OAuthBundle\DataFixtures\ORM;

use App\GameBundle\Entity\Game;
use App\GameBundle\Entity\Subject;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadGameData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $game = new Game();
        $game->setName('Fixture Game');
        $game->setCreator($this->getReference('user'));

        $game->addSubject((new Subject())->setName('Animal'));
        $game->addSubject((new Subject())->setName('First name'));
        $game->addSubject((new Subject())->setName('Anatomy'));

        $manager->persist($game);
        $manager->flush();

        $this->addReference('game', $game);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
