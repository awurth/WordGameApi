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
            'oauth' => [
                'access_token' => $this->generateUrl('fos_oauth_server_token')
            ],
            'user' => [
                'users' => $this->generateUrl('get_users')
            ],
            'game' => [
                'subjects' => $this->generateUrl('get_subjects'),
                'games'    => $this->generateUrl('get_games'),
                'rounds'   => $this->generateUrl('get_rounds'),
                'words'    => $this->generateUrl('get_words')
            ]
        ];
    }
}
