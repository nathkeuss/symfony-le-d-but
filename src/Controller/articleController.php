<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class articleController extends AbstractController
{
 #[Route('/articles', name: 'articles_list')]

 public function articles(): Response {

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





 // défini l'url avec variable id, si je fais /article/1
 // il m'affichera l'article correspondant
 // je peux évidemment mettre une autre variable (content, title...)
 #[Route('/article/{id}', 'article_show', ['id' => '\d+'])]

 // je mets en paramètre la variable correspondant à ma route
 public function showArticle(int $id): Response
 {

     //appelle de la méthode createFromGlobals
     //cette méthode permet d'initialiser notre variable ($request dans ce cas)
     //avec toutes les données de requête donc GET, POST, SESSION, etc
     //$request = Request::createFromGlobals();
     //utilisation de la propriété query qui permet de récupérer les données GET
     //$id = $request->query->get('id');

     $articles = [
         [
             'id' => 1,
             'title' => 'Article 1',
             'content' => 'cr7 siuu',
             'image' => 'https://www.gamechampions.com/media/hhafyrmd/the-ronaldo-siuu-celebration-in-ea-fc-fifa.webp',
             'createdAt' => new \DateTime('2030-01-01 00:00:00')
             ],
         [
             'id' => 2,
             'title' => 'Article 2',
             'content' => 'cristiano ronaldo',
             'image' => 'https://media4.giphy.com/media/hryis7A55UXZNCUTNA/200w.gif?cid=6c09b952qfhtvyxcwarzi2lz9kv81vozr19sviizrewu4rui&ep=v1_gifs_search&rid=200w.gif&ct=g',
             'createdAt' => new \DateTime('2030-01-01 00:00:00')
             ],
         [
             'id' => 3,
             'title' => 'Article 3',
             'content' => 'cristiano',
             'image' => 'https://cdn-www.konbini.com/files/2018/11/afp-cristiano-ronaldo-juventus-e1548255667175.jpg?width=3840&quality=75&format=webp',
             'createdAt' => new \DateTime('2030-01-01 00:00:00')
             ],
         [
             'id' => 4,
             'title' => 'Article 4',
             'content' => 'ronaldo',
             'image' => 'https://media.baamboozle.com/uploads/images/38404/1634143284_350112_gif-url.gif',
             'createdAt' => new \DateTime('2030-01-01 00:00:00')
             ],
         [
             'id' => 5,
             'title' => 'Article 5',
             'content' => 'siuuuu',
             'image' => 'https://media.tenor.com/NPFbJouWeHQAAAAe/cr7-siuu.png',
             'createdAt' => new \DateTime('2030-01-01 00:00:00')
         ]

     ];

//variable pour contenir mon article
     $articleFound = null;

     //je boucle sur les articles jusqu'à tomber sur l'id que je lui ai demandé
     // si il trouve il la stock dans la variable qui est null, l'article en question
     foreach ($articles as $article) {
         if ($article['id'] === $id) {
             $articleFound = $article;
         }
     }

     if (!$articleFound) { // si article = null
         return $this->redirectToRoute('error'); //redirige vers ma page error (grâce à symfony)
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






 #[Route('/articles/search-results', name: 'article_search_results')]
 //plutôt qu'instancier la class Request manuellement, j'utilise le système d'instanciation
 //automatique de symfony, je lui passe en paramètre de méthode le type de la classe voulue
 //suivie d'une variable dans laquelle je veux que symfony stocke l'instance de la classe
 public function articleSearchResults(Request $request): Response
 {
     $search = $request->query->get('search');
     return $this->render('article_search_results.html.twig',[
         'search' => $search
     ]);
 }



}