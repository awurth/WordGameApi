<?php

namespace App\GameBundle\Controller;

use App\CoreBundle\Controller\RestController;
use App\UserBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;

class UserGameController extends RestController
{
    /**
     * @Rest\View
     * @Rest\QueryParam(name="order", requirements="asc|desc", default="asc")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     * @Rest\QueryParam(name="per_page", requirements="\d+", default="15")
     */
    public function getGamesAction($id, ParamFetcher $params)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->findOrFail('UserBundle:User', $id);

        if ($user !== $this->getUser() && !$this->isAdmin()) {
            throw $this->createAccessDeniedException('You can\'t see another user\'s games');
        }

        return $this->getEntityManager()
            ->getRepository('GameBundle:Game')
            ->getByUser(
                $id,
                $params->get('per_page'),
                $params->get('page'),
                $params->get('order')
            );
    }
}
