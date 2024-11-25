<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokerController
{

    #[Route ('/poker', name: 'poker')]
    public function poker() {
        //appelle de la méthode createFromGlobals
        //cette méthode permet d'initialiser notre variable ($request dans ce cas)
        //avec toutes les données de requête donc GET, POST, SESSION, etc
        $request = Request::createFromGlobals();
        //utilisation de la propriété query qui permet de récupérer les données GET
        $age = $request->query->get('age');

        var_dump($age); die;

        return new Response('<p>le poker</p>');
    }

}