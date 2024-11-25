<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController //méthode symfony
{
    // je créé une route pour ma méthode home (celle en dessous)
    // c'est ma méthode home qui sera appelée dans l'url /
    #[Route ('/', name: 'home')]
    // je créé une méthode home qui retourne une instance de la class Response de Symfony
    //qui prendra en paramètre le contenu que je lui ai mis (le h1 dans ce cas là)
    public function home()
    {
        return $this->render("home.html.twig"); // rend mon fichier dans templates
    }

}