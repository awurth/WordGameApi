<?php

namespace App\UserBundle\Controller;

use App\CoreBundle\Controller\RestController;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use UserBundle\Entity\User;

class UserController extends RestController
{
    /**
     * @View
     * @SWG\Response(
     *     response=200,
     *     description="Returns all users",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=User::class)
     *     )
     * )
     * @SWG\Tag(name="user")
     */
    public function getUsersAction()
    {
        return $this->getDoctrine()
            ->getManager()
            ->getRepository('UserBundle:User')
            ->findAll();
    }

    /**
     * @View
     * @SWG\Response(
     *     response=200,
     *     description="Gets a user by it's id",
     *     @Model(type=User::class)
     * )
     * @SWG\Parameter(
     *     name="id",
     *     description="The user id",
     *     in="path",
     *     type="integer",
     *     required=true
     * )
     * @SWG\Tag(name="user")
     */
    public function getUserAction($id)
    {
        $user = $this->getDoctrine()
            ->getManager()
            ->getRepository('UserBundle:User')
            ->find($id);

        if (null === $user) {
            throw $this->createNotFoundException();
        }

        return $user;
    }
}
