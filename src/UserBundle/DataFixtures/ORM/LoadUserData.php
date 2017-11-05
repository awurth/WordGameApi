<?php

namespace App\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $manipulator = $this->container->get('fos_user.util.user_manipulator');

        $admin = $manipulator->create('admin', 'admin', 'admin@domain.com', true, true);
        $moderator = $manipulator->create('moderator', 'moderator', 'moderator@domain.com', true, false);
        $author = $manipulator->create('author', 'author', 'author@domain.com', true, false);
        $user = $manipulator->create('awurth', 'awurth', 'awurth@domain.com', true, false);

        $manipulator->addRole($moderator, 'ROLE_MODERATOR');
        $manipulator->addRole($author, 'ROLE_AUTHOR');

        $this->addReference('admin', $admin);
        $this->addReference('moderator', $moderator);
        $this->addReference('author', $author);
        $this->addReference('user', $user);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
