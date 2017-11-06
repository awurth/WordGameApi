<?php

namespace App\UserBundle\Controller;

use App\CoreBundle\Controller\RestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends RestController
{
    /**
     * @Rest\View
     */
    public function getUsersAction()
    {
        return $this->getDoctrine()
            ->getManager()
            ->getRepository('UserBundle:User')
            ->findAll();
    }

    /**
     * @Rest\View
     */
    public function getUserAction($id)
    {
        return $this->findOrFail('UserBundle:User', $id);
    }
}
