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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->findOrFail('UserBundle:User', $id);
    }

    /**
     * @Rest\Get(path="/user")
     * @Rest\View
     */
    public function getCurrentUserAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->getUser();
    }

    /**
     * @Rest\Get(path="/user/role/{role}")
     * @Rest\View
     */
    public function getHasRoleAction($role)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (empty($role)) {
            throw $this->createNotFoundException();
        }

        $role = strtoupper($role);
        if (0 !== strpos($role, 'ROLE_')) {
            $role = 'ROLE_' . $role;
        }

        if (!$this->isGranted($role)) {
            throw $this->createNotFoundException();
        }

        return null;
    }
}
