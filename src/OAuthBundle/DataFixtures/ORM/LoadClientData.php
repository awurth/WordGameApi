<?php

namespace App\OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadClientData extends AbstractFixture implements ContainerAwareInterface
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
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');

        $client = $clientManager->createClient();

        $client->setAllowedGrantTypes(['password', 'refresh_token']);
        $client->setRandomId('62o6a6q1a4kkoc0g0wcco4w04so8g4ko4kcwk4wowkow48c4k4');
        $client->setSecret('3terzhc7wnoko4wkgk48o0oww04gk0s8owkgog000gk4k88cgg');

        $clientManager->updateClient($client);

        $this->addReference('oauth-client', $client);
    }
}
