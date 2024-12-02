<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'articles_list')]
    //classe ArticleRepository est générée autmatiquement lors
        // de la génération de l'entité Article
        //elle contient plusieurs méthode pour faire des requêtes de type SELECT
        //sur la table article, j'utilise l'autowire pour instancier cette classe
    public function articles(ArticleRepository $articleRepository): Response
    {

        // méthode findAll du repo, récupère tous les article de la table Article
        $articles = $articleRepository->findAll();

        return $this->render('articles.html.twig', [
            'articles' => $articles]);
    }





    // défini l'url avec variable id, si je fais /article/1
    // il m'affichera l'article correspondant
    // je peux évidemment mettre une autre variable (content, title...)
    #[Route('/article/{id}', 'article_show', ['id' => '\d+'])]
    // je mets en paramètre la variable correspondant à ma route
    public function showArticle(int $id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        // méthode find($id) du repo, récupère les articles suivant leur id
        $articleFound = $articleRepository->find($id);

        // j'instancie l'objet  Comment pour créer un nouveau commentaire
        $comment = new Comment();
        // associe le commentaire à l'article
        $comment->setArticle($articleFound);

        // crée un formulaire basé sur la classe CommentType, lié à l'objet Comment
        $form = $this->createForm(CommentType::class, $comment);

        // traite les données soumises dans la requête et les associe au formulaire.
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $entityManager->persist($comment);
            $entityManager->flush();

        }

        $formView = $form->createView();

        $comments = $articleFound->getComments()->toArray();
        // récupère tous les commentaires associés à l'article et les convertit en tableau.
        $comments = array_reverse($comments);
        // inverse l'ordre des commentaires pour afficher les plus récents en premier.

        return $this->render('article_show.html.twig',
            ['formView' => $formView,
            'articles' => $articleFound,
                'comments' => $comments,
        ]);



        //--------------------------------------------------------------------------//
        //appelle de la méthode createFromGlobals
        //cette méthode permet d'initialiser notre variable ($request dans ce cas)
        //avec toutes les données de requête donc GET, POST, SESSION, etc
        //$request = Request::createFromGlobals();
        //utilisation de la propriété query qui permet de récupérer les données GET
        //$id = $request->query->get('id');


        //variable pour contenir mon article
        // $articleFound = null;

        //je boucle sur les articles jusqu'à tomber sur l'id que je lui ai demandé
        // si il trouve il la stock dans la variable qui est null, l'article en question
        //foreach ($articles as $article) {
        //    if ($article['id'] === $id) {
        //        $articleFound = $article;
        //    }
        //}
        //--------------------------------------------------------------------------//

        //if (!$articleFound) { // si article = null
        //    return $this->redirectToRoute('error'); //redirige vers ma page error (grâce à symfony)
        //}

        // la méthode render de la classe AbtractController
        // récupère le fichier twig passé en paramètre
        // dans le dossier template
        // elle le convertit en HTML
        // elle créé une réponse HTTP valide
        // avec en status 200
        // et en body, le HTML généré
        //return $this->render('article_show.html.twig', [
        //    'articles' => $articleFound
        //]);
    }


    #[Route('/articles/search-results', name: 'article_search_results')]
    //plutôt qu'instancier la class Request manuellement, j'utilise le système d'instanciation
        //automatique de symfony, je lui passe en paramètre de méthode le type de la classe voulue
        //suivie d'une variable dans laquelle je veux que symfony stocke l'instance de la classe
    public function articleSearchResults(Request $request, ArticleRepository $articleRepository): Response
    {

        //récupère la valeur du paramètre "search" depuis la requête
        $search = $request->query->get('search');

        //appelle la méthode search de l'ArticleRepository pour trouver les articles correspondants
        $articles = $articleRepository->search($search);

        // rend la template twig avec la valeur de search, et le résultat des articles
        return $this->render('article_search_results.html.twig', [
            'search' => $search,
            'articles' => $articles,
        ]);
    }

    #[Route('/article/create', name: 'article_create')]
    //entitymanager permet de save/delete des entités en bdd
    public function createArticle(EntityManagerInterface $entityManager, Request $request): Response
    {

        //j'instancie mon entité article pour en créer un nouveau
        $article = new Article();

        // créer un formulaire basé sur mon Article
        //la classe articletype définit les champs du form
        //le formulaire est lié à $article pour ques les valeurs saisies soient associées
        $form = $this->createForm(ArticleType::class, $article);


        // je demande au formulaire de symfony
        // de récupérer les données de la requête
        // et de remplir automatiquement l'entité $article avec
        // donc de récupérer les données de chaque input
        // et de les stocker dans les propriétés de l'entité (setTitle() etc)
        $form->handleRequest($request);


        // je vérifie que les données ont été envoyées
        if ($form->isSubmitted()) {
            // je mets automatiquement la date de création de mon article
            // car je ne veux pas que ce soit choisi par l'utilisateur
            $article->setCreatedAt(new \DateTime());
            // j'enregistre l'entité article dans ma bdd
            $entityManager->persist($article);
            $entityManager->flush();
        }

        //crée une vue du formulaire
        $formView = $form->createView();

        return $this->render('article_create_form.html.twig',
            ['formView' => $formView]);


        //if ($request->isMethod('POST')) {
        //    $title = $request->request->get('title');
        //    $content = $request->request->get('content');
        //    $image = $request->request->get('image');

        //    $article = new Article();

        //    $article->setTitle($title);
        //    $article->setContent($content);
        //    $article->setImage($image);
        //    $article->setCreatedAt(new \DateTime());

        //préparations de l'enregistrement (le commit de github)
        //   $entityManager->persist($article);
        //exécute les opérations dans la bdd (le push de github)
        //   $entityManager->flush();

        //   return $this->redirectToRoute('articles_list');//redirige vers ma liste d'article
        //}

        //j'utilise les méthodes setX pour remplir les propriétés

        //$article->setTitle('bg');
        //$article->setContent("t'es bg");
        //$article->setCreatedAt(new \DateTime('now'));
    }


    #[Route('/article/delete/{id}', name: 'article_delete', requirements: ['id' => '\d+'])]
    public function deleteArticle(int $id, EntityManagerInterface $entityManager, ArticleRepository $articleRepository): Response
    {
        //dd('salut');
        //je récupe l'article que je veux supprimer via l'id de l'url article/delete/{id}
        $article = $articleRepository->find($id);

        //si l'article existe pas/plus, il redirige vers la plage d'erreur
        if (!$article) {
            return $this->redirectToRoute('error');
        }

        //utilisation de la méthode remove de l'entitymanager pour delete l'article
        $entityManager->remove($article);
        $entityManager->flush();

        //redirige vers la liste d'articles (sans celui que j'ai supprimé du coup)
        return $this->redirectToRoute('articles_list');

    }

    #[Route('/article/update/{id}', name: 'article_update', requirements: ['id' => '\d+'])]
    public function updateArticle(int $id, EntityManagerInterface $entityManager, ArticleRepository $articleRepository, Request $request): Response
    {
        //dd('salu'); je vérifie ma route là


        $article = $articleRepository->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            ;
            $entityManager->persist($article);
            $entityManager->flush();
        }

        //crée une vue du formulaire
        $formView = $form->createView();

        return $this->render('article_update.html.twig',
            ['formView' => $formView,
                'article' => $article
            ]);

    }



    //récupère en bdd les valeurs des propriétés par rapport à l'id
    //$article = $articleRepository->find($id);

    //si c'est post
    //if ($request->isMethod('POST')) {
    //récupe les données envoyées via le formulaire
    //    $title = $request->request->get('title');
    //    $content = $request->request->get('content');
    //    $image = $request->request->get('image');


    //modifie les données avec les valeurs du formulaire
    //   $article->setTitle($title);
    //   $article->setContent($content);
    //   $article->setImage($image);

    //maj des valeurs en bdd
    //   $entityManager->persist($article);
    //   $entityManager->flush();

    // return $this->redirectToRoute('articles_list');//redirige vers ma liste d'article
    //}

    //j'envoie au formulaire twig les valeurs déjà existantes en bdd, pour préremplir les champs
    //return $this->render('article_update.html.twig', [
    //    'article' => $article
    //]);


    //}


}