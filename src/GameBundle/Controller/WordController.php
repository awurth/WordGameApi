<?php

namespace App\GameBundle\Controller;

use App\CoreBundle\Controller\RestController;
use App\GameBundle\Entity\Word;
use App\GameBundle\Form\Type\WordType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;

class WordController extends RestController
{
    /**
     * @Rest\View
     * @Rest\QueryParam(name="order", requirements="asc|desc", default="asc")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     * @Rest\QueryParam(name="per_page", requirements="\d+", default="15")
     */
    public function getWordsAction(ParamFetcher $params)
    {
        return $this->getEntityManager()
            ->getRepository('GameBundle:Word')
            ->getCollection(
                $params->get('per_page'),
                $params->get('page'),
                $params->get('order')
            );
    }

    /**
     * @Rest\View
     */
    public function getWordAction($id)
    {
        return $this->findOrFail('GameBundle:Word', $id);
    }

    /**
     * @Rest\View
     */
    public function postWordAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $word = new Word();
        $word->setUser($this->getUser());

        return $this->processForm($word, $request, WordType::class, 'get_word');
    }

    /**
     * @Rest\View
     */
    public function putWordAction($id, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var Word $word */
        $word = $this->findOrFail('GameBundle:Word', $id);

        if ($word->getUser() !== $this->getUser() && !$this->isAdmin()) {
            throw $this->createAccessDeniedException('Access Denied: This is not your word.');
        }

        return $this->processForm($word, $request, WordType::class);
    }

    /**
     * @Rest\View
     */
    public function deleteWordAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $em = $this->getEntityManager();

        /** @var Word $word */
        $word = $this->findOrFail('GameBundle:Word', $id);

        if ($word->getUser() !== $this->getUser() && !$this->isAdmin()) {
            throw $this->createAccessDeniedException('Access Denied: This is not your word.');
        }

        $em->remove($word);
        $em->flush();

        return null;
    }
}
