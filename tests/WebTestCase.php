<?php

namespace Tests;

use Doctrine\ORM\EntityManager;
use Exception;
use FOS\OAuthServerBundle\Model\ClientInterface as OAuthClient;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;
use App\UserBundle\Entity\User;

class WebTestCase extends BaseWebTestCase
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * Asserts that a PHP value corresponds to the given JSON.
     *
     * @param mixed  $expected
     * @param string $actual
     */
    public function assertEqualsJson($expected, $actual)
    {
        $serializer = $this->getContainer()
            ->get('jms_serializer');

        $this->assertEquals($serializer->serialize($expected, 'json'), $actual);
    }

    /**
     * Asserts that the response status code is 400 (Bad Request).
     *
     * @param Response $response
     */
    public function assertIsBadRequest(Response $response)
    {
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Asserts that the response status code is 201 (Created).
     *
     * @param Response $response
     * @param bool     $checkLocationHeader
     */
    public function assertIsCreated(Response $response, $checkLocationHeader = true)
    {
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        if ($checkLocationHeader) {
            $this->assertTrue($response->headers->has('Location'));
        }
    }

    /**
     * Asserts that the response status code is 403.
     *
     * @param Response $response
     */
    public function assertIsForbidden(Response $response)
    {
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * Asserts that the response status code is 204 (No Content).
     *
     * @param Response $response
     * @param bool     $checkContent
     */
    public function assertIsNoContent(Response $response, $checkContent = true)
    {
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        if ($checkContent) {
            $this->assertEmpty($response->getContent());
        }
    }

    /**
     * Asserts that the response status code is 404 (Not Found).
     *
     * @param Response $response
     */
    public function assertIsNotFound(Response $response)
    {
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * Asserts that the response status code is 200.
     *
     * @param Response $response
     */
    public function assertIsOk(Response $response)
    {
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Asserts that the response status code is 401.
     *
     * @param Response $response
     */
    public function assertIsUnauthorized(Response $response)
    {
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     * Asserts that the response's Content-Type is JSON.
     *
     * @param Response $response
     * @param bool     $checkContent
     */
    public function assertJsonResponse(Response $response, $checkContent = true)
    {
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        if ($checkContent) {
            $this->assertJson($response->getContent());
        }
    }

    /**
     * Asserts that JSON response contains the right number of resources.
     *
     * @param Response $response
     * @param int      $count
     */
    public function assertJsonResourcesCount(Response $response, $count)
    {
        $serializer = $this->getContainer()->get('jms_serializer');

        $deserializedContent = $serializer->deserialize($response->getContent(), 'array', 'json');

        $this->assertCount($count, $deserializedContent);
    }

    /**
     * Creates a new User with SUPER_ADMIN role.
     *
     * @return User
     */
    public function createAdmin()
    {
        return $this->createUser('test-admin', 'admin', 'test-admin@domain.com', ['ROLE_ADMIN']);
    }

    /**
     * Returns a client logged in as an ADMIN.
     *
     * @return Client
     */
    public function createAdminClient()
    {
        $this->loginAs($this->createAdmin(), 'main');

        return $this->makeClient();
    }

    /**
     * Returns a client logged in as a normal user.
     *
     * @param array  $roles
     * @param string $username
     * @param string $password
     * @param string $email
     *
     * @return Client
     */
    public function createLoggedClient(array $roles = [], $username = 'user', $password = 'user', $email = 'user@domain.com')
    {
        $user = $this->createUser($username, $password, $email, $roles);

        $this->loginAs($user, 'main');

        return $this->makeClient();
    }

    /**
     * Creates a new OAuth Client.
     *
     * @return OAuthClient|mixed
     */
    public function createOAuthClient()
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');

        $client = $clientManager->createClient();
        $client->setAllowedGrantTypes(['password', 'refresh_token']);

        $clientManager->updateClient($client);

        return $client;
    }

    /**
     * Creates a new User.
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param array  $roles
     *
     * @return User
     */
    public function createUser($username, $password, $email, array $roles = [])
    {
        $manipulator = $this->getContainer()->get('fos_user.util.user_manipulator');

        $user = $manipulator->create($username, $password, $email, true, false);

        foreach ($roles as $role) {
            $manipulator->addRole($username, $role);
        }

        return $user;
    }

    /**
     * Sends a DELETE request.
     *
     * @param Client $client
     * @param string $uri
     * @param string $accessToken
     * @param array  $headers
     * @param array  $parameters
     * @param array  $files
     *
     * @return Response|null
     */
    public function delete(Client $client, $uri, $accessToken = null, array $headers = [], array $parameters = [], array $files = [])
    {
        return $this->request($client, 'DELETE', $uri, '', $accessToken, $headers, $parameters, $files);
    }

    /**
     * Deletes all rows of a table.
     *
     * @param string $name
     */
    public function emptyTable($name)
    {
        $this->em->getConnection()->executeUpdate('DELETE FROM ' . $name);
    }

    /**
     * Finds the first entity in a table.
     *
     * @param string $entityName
     *
     * @return object|null
     */
    public function findFirst($entityName)
    {
        return $this->em->getRepository($entityName)->findOneBy([]);
    }

    /**
     * Sends a GET request.
     *
     * @param Client $client
     * @param string $uri
     * @param string $accessToken
     * @param array  $headers
     * @param array  $parameters
     * @param array  $files
     *
     * @return Response|null
     */
    public function get(Client $client, $uri, $accessToken = null, array $headers = [], array $parameters = [], array $files = [])
    {
        return $this->request($client, 'GET', $uri, '', $accessToken, $headers, $parameters, $files);
    }

    /**
     * Get a data fixture's fully qualified class name.
     *
     * @param string $bundle
     * @param string $entityName
     *
     * @return string
     */
    public function getFixtureClass($bundle, $entityName)
    {
        return $bundle . 'Bundle\\DataFixtures\\ORM\\Load' . $entityName . 'Data';
    }

    /**
     * Loads a data fixture.
     *
     * @param string $bundle
     * @param string $entityName
     */
    public function loadFixture($bundle, $entityName)
    {
        $this->loadFixtures([$this->getFixtureClass($bundle, $entityName)]);
    }

    /**
     * Log in with username and password and get an OAuth Access Token.
     *
     * @param Client      $client
     * @param string      $username
     * @param string      $password
     * @param OAuthClient $oauthClient
     *
     * @return string
     *
     * @throws Exception
     */
    public function logIn(Client $client, $username, $password, OAuthClient $oauthClient = null)
    {
        if (null === $oauthClient) {
            $oauthClient = $this->createOAuthClient();
        }

        $content = '{
            "grant_type": "password",
            "client_id": "' . $oauthClient->getPublicId() . '",
            "client_secret": "' . $oauthClient->getSecret() . '",
            "username": "' . $username . '",
            "password": "' . $password . '"
        }';

        $response = $this->post($client, '/oauth/v2/token', $content);

        $data = json_decode($response->getContent(), true);
        if (!isset($data['access_token'])) {
            throw new Exception('Invalid response');
        }

        return $data['access_token'];
    }

    /**
     * Sends a POST request.
     *
     * @param Client $client
     * @param string $uri
     * @param string $content
     * @param string $accessToken
     * @param array  $headers
     * @param array  $parameters
     * @param array  $files
     *
     * @return Response|null
     */
    public function post(Client $client, $uri, $content, $accessToken = null, array $headers = [], array $parameters = [], array $files = [])
    {
        return $this->request($client, 'POST', $uri, $content, $accessToken, $headers, $parameters, $files);
    }

    /**
     * Sends a PUT request.
     *
     * @param Client $client
     * @param string $uri
     * @param string $content
     * @param string $accessToken
     * @param array  $headers
     * @param array  $parameters
     * @param array  $files
     *
     * @return Response|null
     */
    public function put(Client $client, $uri, $content, $accessToken = null, array $headers = [], array $parameters = [], array $files = [])
    {
        return $this->request($client, 'PUT', $uri, $content, $accessToken, $headers, $parameters, $files);
    }

    /**
     * Sends a request.
     *
     * @param Client $client
     * @param string $method
     * @param string $uri
     * @param string $content
     * @param string $accessToken
     * @param array  $headers
     * @param array  $parameters
     * @param array  $files
     *
     * @return Response|null
     */
    public function request(Client $client, $method, $uri, $content, $accessToken = null, array $headers = [], array $parameters = [], array $files = [])
    {
        if (!isset($headers['CONTENT_TYPE'])) {
            $headers['CONTENT_TYPE'] = 'application/json';
        }

        if (null !== $accessToken) {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $accessToken;
        }

        $client->request($method, $uri, $parameters, $files, $headers, $content);

        return $client->getResponse();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
