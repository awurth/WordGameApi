<?php

namespace App\GameBundle\Controller;

use App\CoreBundle\Controller\RestController;
use App\GameBundle\Entity\Round;
use App\GameBundle\Form\Type\RoundType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;

class RoundController extends RestController
{
    /**
     * @Rest\View
     * @Rest\QueryParam(name="order", requirements="asc|desc", default="asc")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     * @Rest\QueryParam(name="per_page", requirements="\d+", default="15")
     */
    public function getRoundsAction(ParamFetcher $params)
    {
        return $this->getEntityManager()
            ->getRepository('GameBundle:Round')
            ->getCollection(
                $params->get('per_page'),
                $params->get('page'),
                $params->get('order')
            );
    }

    /**
     * @Rest\View
     */
    public function getRoundAction($id)
    {
        $round = $this->getEntityManager()
            ->getRepository('GameBundle:Round')
            ->find($id);

        if (null === $round) {
            throw $this->createNotFoundException();
        }

        return $round;
    }

    /**
     * @Rest\View
     */
    public function postRoundAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->processForm(new Round(), $request, RoundType::class, 'get_round', ['validation_groups' => ['Default', 'post_round']]);
    }

    /**
     * @Rest\View
     */
    public function putRoundAction($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $round = $this->getEntityManager()
            ->getRepository('GameBundle:Round')
            ->find($id);

        if (null === $round) {
            throw $this->createNotFoundException();
        }

        return $this->processForm($round, $request, RoundType::class);
    }

    /**
     * @Rest\View
     */
    public function deleteRoundAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getEntityManager();

        $round = $em->getRepository('GameBundle:Round')->find($id);

        if (null === $round) {
            throw $this->createNotFoundException();
        }

        $em->remove($round);
        $em->flush();

        return null;
    }
}
