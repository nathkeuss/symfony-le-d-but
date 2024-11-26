<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'error')]
    public function notFound(): Response
    {
        $view = $this->renderView('error.html.twig'); // génère le contenu html
        return new Response($view, 404); // crée une réponse HTTP
    }

}