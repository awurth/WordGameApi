<?php

namespace App\CoreBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestController extends FOSRestController
{
    /**
     * Finds an entity by its id or throws a NotFoundException.
     *
     * @param string $repositoryClass
     * @param mixed  $id
     *
     * @return object
     */
    protected function findOrFail($repositoryClass, $id)
    {
        $entity = $this->getEntityManager()
            ->getRepository($repositoryClass)
            ->find($id);

        if (null === $entity) {
            throw $this->createNotFoundException();
        }

        return $entity;
    }

    /**
     * Gets the Doctrine EntityManager.
     *
     * @return ObjectManager|object
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Gets an Entity repository.
     *
     * @param string $className
     *
     * @return ObjectRepository
     */
    protected function getRepository($className)
    {
        return $this->getDoctrine()->getManager()->getRepository($className);
    }

    /**
     * Returns whether the currents user is an Admin.
     *
     * @return bool
     */
    protected function isAdmin()
    {
        return $this->isGranted('ROLE_ADMIN');
    }

    /**
     * Processes a form.
     *
     * @param object  $entity
     * @param Request $request
     * @param string  $formType
     * @param string  $redirectRoute
     *
     * @return View|Form|null
     */
    protected function processForm($entity, Request $request, $formType, $redirectRoute = null, array $options = [])
    {
        if (!isset($options['method'])) {
            $options['method'] = $request->getMethod();
        }

        $form = $this->createForm($formType, $entity, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

            if (null === $redirectRoute) {
                return null;
            }

            return $this->routeRedirectView($redirectRoute, [
                'id' => $entity->getId()
            ], Response::HTTP_CREATED);
        }

        return $form;
    }
}
