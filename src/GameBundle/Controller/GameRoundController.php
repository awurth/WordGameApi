<?php

namespace App\GameBundle\Controller;

use App\CoreBundle\Controller\RestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;

class GameRoundController extends RestController
{
    /**
     * @Rest\View
     * @Rest\QueryParam(name="order", requirements="asc|desc", default="asc")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     * @Rest\QueryParam(name="per_page", requirements="\d+", default="15")
     */
    public function getRoundsAction($id, ParamFetcher $params)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->findOrFail('GameBundle:Game', $id);

        return $this->getEntityManager()
            ->getRepository('GameBundle:Round')
            ->getByGame(
                $id,
                $params->get('per_page'),
                $params->get('page'),
                $params->get('order')
            );
    }
}
