<?php

namespace App\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

class HomeController extends RestController
{
    /**
     * @Rest\View
     */
    public function getAction()
    {
        return [
            'oauth' => $this->generateUrl('fos_oauth_server_token'),
            'users' => $this->generateUrl('get_users')
        ];
    }
}
