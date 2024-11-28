<?php

namespace App\Controller;

use App\Entity\Article;
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
    public function showArticle(int $id, ArticleRepository $articleRepository): Response
    {
        // méthode find($id) du repo, récupère les articles suivant leur id
        $articleFound = $articleRepository->find($id);

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
        return $this->render('article_show.html.twig', [
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
        return $this->render('article_search_results.html.twig', [
            'search' => $search
        ]);
    }

    #[Route('/article/create', name: 'article_create', requirements: ['id' => '\d+'])]
    //entitymanager permet de save/delete des entités en bdd
    public function createArticle(EntityManagerInterface $entityManager): Response
    {
        //création instance de l'entité article
        $article = new Article();

        //j'utilise les méthodes setX pour remplir les propriétés
        $article->setTitle('bg');
        $article->setContent("t'es bg");
        $article->setCreatedAt(new \DateTime('now'));
        $article->setImage('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT07W2KZlaxx-4ng3KBQnkRwcD0YBZw5hno1Q&s');

        //préparations de l'enregistrement (le commit de github)
        $entityManager->persist($article);
        //exécute les opérations dans la bdd (le push de github)
        $entityManager->flush();

        return $this->redirectToRoute('articles_list');//redirige vers ma liste d'article
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
    public function updateArticle(int $id, EntityManagerInterface $entityManager, ArticleRepository $articleRepository): Response
    {
        //dd('salu'); je vérifie ma route là

        //récupère l'article correspond à l'id dans l'url
        $article = $articleRepository->find($id);

        // si l'article existe pas, redirige vers la page d'erreur
        if (!$article) {
            return $this->redirectToRoute('error');
        }

        //je modifie les valeurs de mes propriétés
        $article->setTitle("bg max MAJ");
        $article->setContent("trop bg MAJ");

        // je re-sauvegarde l'article dans ma bdd, vu qu'il voit que l'id existe déjà
        // il va juste modifier plutôt que créer
        $entityManager->persist($article);
        $entityManager->flush();

        return $this->redirectToRoute('articles_list');


    }


}