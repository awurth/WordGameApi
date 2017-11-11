<?php

namespace App\GameBundle\Controller;

use App\CoreBundle\Controller\RestController;
use App\GameBundle\Entity\Game;
use App\GameBundle\Form\Type\GameType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;

class GameController extends RestController
{
    /**
     * @Rest\View
     * @Rest\QueryParam(name="order", requirements="asc|desc", default="asc")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     * @Rest\QueryParam(name="per_page", requirements="\d+", default="15")
     */
    public function getGamesAction(ParamFetcher $params)
    {
        return $this->getEntityManager()
            ->getRepository('GameBundle:Game')
            ->getCollection(
                $params->get('per_page'),
                $params->get('page'),
                $params->get('order')
            );
    }

    /**
     * @Rest\View
     */
    public function getGameAction($id)
    {
        return $this->findOrFail('GameBundle:Game', $id);
    }

    /**
     * @Rest\View
     */
    public function postGameAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $game = new Game();
        $game->setCreator($user)->addUser($user);

        return $this->processForm($game, $request, GameType::class, 'get_game');
    }

    /**
     * @Rest\View
     */
    public function putGameAction($id, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var Game $game */
        $game = $this->findOrFail('GameBundle:Game', $id);

        if ($game->getCreator() !== $this->getUser() && !$this->isAdmin()) {
            throw $this->createAccessDeniedException('Access Denied: This is not your game.');
        }

        return $this->processForm($game, $request, GameType::class);
    }

    /**
     * @Rest\View
     */
    public function deleteGameAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $em = $this->getEntityManager();

        /** @var Game $game */
        $game = $this->findOrFail('GameBundle:Game', $id);

        if ($game->getCreator() !== $this->getUser() && !$this->isAdmin()) {
            throw $this->createAccessDeniedException('Access Denied: This is not your game.');
        }

        $em->remove($game);
        $em->flush();

        return null;
    }
}
