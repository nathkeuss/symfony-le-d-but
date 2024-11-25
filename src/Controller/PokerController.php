<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokerController extends AbstractController
{

    #[Route ('/poker', name: 'poker')]
    public function poker() {
        //appelle de la méthode createFromGlobals
        //cette méthode permet d'initialiser notre variable ($request dans ce cas)
        //avec toutes les données de requête donc GET, POST, SESSION, etc
        $request = Request::createFromGlobals();
        //utilisation de la propriété query qui permet de récupérer les données GET
        $age = $request->query->get('age');

        // mettre dans l'url /poker?age=X




        if($age < 18) {
            return $this->render('poker/poker_accessdenied.html.twig');
        } else {
            return $this->render('poker/poker_access.html.twig');
        }

    }

}