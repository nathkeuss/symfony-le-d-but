<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class articleController extends AbstractController
{
 #[Route('/articles', name: 'articles_list')]

 public function articles() {

     $articles = [
         [
             'id' => 1,
             'title' => 'Article 1',
             'content' => 'cr7 siuu',
             'image' => 'https://www.gamechampions.com/media/hhafyrmd/the-ronaldo-siuu-celebration-in-ea-fc-fifa.webp',
         ],
         [
             'id' => 2,
             'title' => 'Article 2',
             'content' => 'cristiano ronaldo',
             'image' => 'https://media4.giphy.com/media/hryis7A55UXZNCUTNA/200w.gif?cid=6c09b952qfhtvyxcwarzi2lz9kv81vozr19sviizrewu4rui&ep=v1_gifs_search&rid=200w.gif&ct=g',
         ],
         [
             'id' => 3,
             'title' => 'Article 3',
             'content' => 'cristiano',
             'image' => 'https://cdn-www.konbini.com/files/2018/11/afp-cristiano-ronaldo-juventus-e1548255667175.jpg?width=3840&quality=75&format=webp',
         ],
         [
             'id' => 4,
             'title' => 'Article 4',
             'content' => 'ronaldo',
             'image' => 'https://media.baamboozle.com/uploads/images/38404/1634143284_350112_gif-url.gif',
         ],
         [
             'id' => 5,
             'title' => 'Article 5',
             'content' => 'siuuuu',
             'image' => 'https://media.tenor.com/NPFbJouWeHQAAAAe/cr7-siuu.png',
         ]

     ];

     return $this->render('articles.html.twig',[
         'articles' => $articles ]);
 }

 #[Route('/article', 'article_show')]
 public function showArticle() {

     //appelle de la méthode createFromGlobals
     //cette méthode permet d'initialiser notre variable ($request dans ce cas)
     //avec toutes les données de requête donc GET, POST, SESSION, etc
     $request = Request::createFromGlobals();
     //utilisation de la propriété query qui permet de récupérer les données GET
     $id = $request->query->get('id');

     $articles = [
         [
             'id' => 1,
             'title' => 'Article 1',
             'content' => 'cr7 siuu',
             'image' => 'https://www.gamechampions.com/media/hhafyrmd/the-ronaldo-siuu-celebration-in-ea-fc-fifa.webp',
         ],
         [
             'id' => 2,
             'title' => 'Article 2',
             'content' => 'cristiano ronaldo',
             'image' => 'https://media4.giphy.com/media/hryis7A55UXZNCUTNA/200w.gif?cid=6c09b952qfhtvyxcwarzi2lz9kv81vozr19sviizrewu4rui&ep=v1_gifs_search&rid=200w.gif&ct=g',
         ],
         [
             'id' => 3,
             'title' => 'Article 3',
             'content' => 'cristiano',
             'image' => 'https://cdn-www.konbini.com/files/2018/11/afp-cristiano-ronaldo-juventus-e1548255667175.jpg?width=3840&quality=75&format=webp',
         ],
         [
             'id' => 4,
             'title' => 'Article 4',
             'content' => 'ronaldo',
             'image' => 'https://media.baamboozle.com/uploads/images/38404/1634143284_350112_gif-url.gif',
         ],
         [
             'id' => 5,
             'title' => 'Article 5',
             'content' => 'siuuuu',
             'image' => 'https://media.tenor.com/NPFbJouWeHQAAAAe/cr7-siuu.png',
         ]

     ];

//variable pour contenir mon article
     $articleFound = null;

     //je boucle sur les articles jusqu'à tomber sur l'id que je lui ai demandé
     // si il trouve il stock dans la variable qui est null, l'article en question
     foreach ($articles as $article) {
         if ($article['id'] === (int)$id) {
             $articleFound = $article;
         }
     }

     // la méthode render de la classe AbtractController
     // récupère le fichier twig passé en paramètre
     // dans le dossier template
     // elle le convertit en HTML
     // elle créé une réponse HTTP valide
     // avec en status 200
     // et en body, le HTML généré
     return $this->render('article_show.html.twig',[
         'articles' => $articleFound
     ]);
 }



}