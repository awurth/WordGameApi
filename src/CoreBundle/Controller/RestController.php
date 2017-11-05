<?php

namespace App\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestController extends FOSRestController
{
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
    protected function processForm($entity, Request $request, $formType, $redirectRoute = null)
    {
        $form = $this->createForm($formType, $entity, ['method' => $request->getMethod()]);

        if ($form->handleRequest($request)->isValid()) {
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
