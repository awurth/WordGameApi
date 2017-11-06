<?php

namespace App\GameBundle\Controller;

use App\CoreBundle\Controller\RestController;
use App\GameBundle\Entity\Subject;
use App\GameBundle\Form\Type\SubjectType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;

class SubjectController extends RestController
{
    /**
     * @Rest\View
     * @Rest\QueryParam(name="order", requirements="asc|desc", default="asc")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     * @Rest\QueryParam(name="per_page", requirements="\d+", default="15")
     */
    public function getSubjectsAction(ParamFetcher $params)
    {
        return $this->getEntityManager()
            ->getRepository('GameBundle:Subject')
            ->getCollection(
                $params->get('per_page'),
                $params->get('page'),
                $params->get('order')
            );
    }

    /**
     * @Rest\View
     */
    public function getSubjectAction($id)
    {
        return $this->findOrFail('GameBundle:Subject', $id);
    }

    /**
     * @Rest\View
     */
    public function postSubjectAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->processForm(new Subject(), $request, SubjectType::class, 'get_subject');
    }

    /**
     * @Rest\View
     */
    public function putSubjectAction($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        /** @var Subject $subject */
        $subject = $this->findOrFail('GameBundle:Subject', $id);

        return $this->processForm($subject, $request, SubjectType::class);
    }

    /**
     * @Rest\View
     */
    public function deleteSubjectAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getEntityManager();

        /** @var Subject $subject */
        $subject = $this->findOrFail('GameBundle:Subject', $id);

        $em->remove($subject);
        $em->flush();

        return null;
    }
}
