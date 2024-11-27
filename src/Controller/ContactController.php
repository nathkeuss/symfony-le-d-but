<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{

    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function contact(Request $request): Response
    {
        //initialisation de la variable à null
        //elle contiendra le message de confirmation
        $messageSend = null;

        //vérif si la requête est de type POST
        if ($request->isMethod('POST')) {
            //récup du champ name dans le form
            $name = $request->request->get('name');
            //récup du champ name dans le form
            $message = $request->request->get('message');
            //message de confirmation contenant le nom de l'user et le message envoyé
            $messageSend = "merci $name pour ton message. Voici ton message : $message";
        }
        //retourne la vue contact
        return $this->render('contact.html.twig',
            ['messageSend' => $messageSend,]); //message envoyé à twig

    }



}