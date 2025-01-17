<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\FrontendUserBundle\Tests\Controller\Admin;

use Aisel\ResourceBundle\Tests\AbstractBackendWebTestCase;

/**
 * ApiControllerTest
 *
 * @author Ivan Proskuryakov <volgodark@gmail.com>
 */
class ApiControllerTest extends AbstractBackendWebTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testPostUserAction()
    {

        $users = $this
            ->dm
            ->getRepository('Aisel\FrontendUserBundle\Document\FrontendUser')
            ->findBy(['username' => 'test_frontend_user_aisel']);

        foreach ($users as $user) {
            $this->dm->remove($user);
        }
        $this->dm->flush();

        $data = [
            'username' => 'test_frontend_user_aisel',
            'email' => 'test_frontend_user_aisel@aisel.co',
            'plain_password' => 'test_frontend_user_aisel',
        ];

        $this->client->request(
            'POST',
            '/'. $this->api['backend'] . '/frontenduser/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();

        $this->client->request(
            'POST',
            '/'. $this->api['backend'] . '/frontenduser/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();
        $result = json_decode($content, true);

        // @todo: handle errors
        $this->assertEquals($result['code'], 500);
        $this->assertEquals($result['message'], 'Duplicate key error');
        $this->assertTrue(500 === $statusCode);
    }

    public function testGetUsersAction()
    {
        $this->client->request(
            'GET',
            '/'. $this->api['backend'] . '/frontenduser/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();

        $this->assertTrue(200 === $statusCode);
        $this->assertJson($content);
    }

    public function testGetUserAction()
    {
        $user = $this
            ->dm
            ->getRepository('Aisel\FrontendUserBundle\Document\FrontendUser')
            ->findOneBy(['username' => 'test_frontend_user_aisel']);
        $id = $user->getId();


        $this->client->request(
            'GET',
            '/'. $this->api['backend'] . '/frontenduser/' . $id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();
        $result = json_decode($content, true);

        $this->assertTrue(200 === $statusCode);
        $this->assertEquals($result['id'], $user->getId());
    }

    public function testPutUserAction()
    {
        $user = $this
            ->dm
            ->getRepository('Aisel\FrontendUserBundle\Document\FrontendUser')
            ->findOneBy(['username' => 'test_frontend_user_aisel']);
        $id = $user->getId();

        $data = array(
            'id' => $id,
            'created_at' => '2015-05-26T05:04:54-0700',
            'updated_at' => '2015-05-26T05:04:54-0700',
            'last_login' => '2015-05-26T05:04:54-0700',
            'phone' => '+123',
            'enabled' => true,
            'locked' => false,
            'orders' => array(),
            'cart' => array(),
        );

        $this->client->request(
            'PUT',
            '/'. $this->api['backend'] . '/frontenduser/' . $id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();

        $this->dm->clear();

        $user = $this
            ->dm
            ->getRepository('Aisel\FrontendUserBundle\Document\FrontendUser')
            ->findOneBy(['username' => 'test_frontend_user_aisel']);

        $this->assertTrue(204 === $statusCode);
        $this->assertEmpty($content);
        $this->assertNotNull($user);
    }

    public function testDeletePageNodeAction()
    {
        $user = $this
            ->dm
            ->getRepository('Aisel\FrontendUserBundle\Document\FrontendUser')
            ->findOneBy(['username' => 'test_frontend_user_aisel']);
        $id = $user->getId();

        $this->client->request(
            'DELETE',
            '/'. $this->api['backend'] . '/frontenduser/' . $id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();

        $user = $this
            ->dm
            ->getRepository('Aisel\FrontendUserBundle\Document\FrontendUser')
            ->findOneBy(['username' => 'test_frontend_user_aisel']);

        $this->assertTrue(204 === $statusCode);
        $this->assertEmpty($content);
        $this->assertNull($user);
    }

}
