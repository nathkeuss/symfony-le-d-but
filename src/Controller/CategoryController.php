<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    //route pour afficher la liste des catégories via /categories
    #[Route('/categories', name: 'categories')]
    // le CategoryRepository est une classe générée par doctrine pour
        // gérer les requêtes sur la table Category. L'instance $categoryRepository
        // permet d'accéder aux méthodes comme find()
    public function categories(CategoryRepository $categoryRepository): Response
    {
        // méthode findAll du repo, récupère toutes les catégories de la table Category
        $categories = $categoryRepository->findAll();

        return $this->render('categories.html.twig',
            ['categories' => $categories]);
        // rend le fichier twig en lui passant les catégories récupérées
    }

    #[Route('/categories/search-results', name: 'category_search_results')]
    public function categorySearchResults(Request $request, CategoryRepository $categoryRepository): Response {
        $search = $request->get('search');

        $categories = $categoryRepository->search($search);

        return $this->render('category_search_results.html.twig', [
            'search' => $search,
            'categories' => $categories,
        ]);
    }

    #[Route('/category/{id}', name: 'category_show', requirements: ['id' => '\d+'])]
    //route pour afficher la catégorie je lui ai demandé suivant son id, via /category/id
    public function showCategory(int $id, CategoryRepository $categoryRepository): Response
    {
        // méthode find($id) du repo, récupère la catégorie suivant son id
        $category = $categoryRepository->find($id);
        //j'appelle ma fonction qui retourne les articles qui sont dans une caté spécifique
        $articles = $category->getArticles();

        if (!$category) { // si category = null
            return $this->redirectToRoute('error'); //redirige vers ma page error (grâce à symfony)
        }
        return $this->render('category_show.html.twig',
            ['category' => $category
            , 'articles' => $articles]);
        // rend le fichier twig en lui passant la catégorie récupérée

    }

    #[Route('/category/create', name: 'category_create')]
    public function createCategory(EntityManagerInterface $entityManager, Request $request): Response
    {

        //j'instancie mon entité category pour en créer un nouveau
        $category = new Category();

        // créer un formulaire basé sur ma Category
        //la classe categorytype définit les champs du form
        //le formulaire est lié à $article pour ques les valeurs saisies soient associées
        $form = $this->createForm(CategoryType::class, $category);

        //symfony récupère les données que j'ai entré dans la requête
        $form->handleRequest($request);

        //si le form est submit, il enregistre dans la bdd
        if ($form->isSubmitted()) {
            $entityManager->persist($category);
            $entityManager->flush();
        }
        //crée une vue du formulaire
        $formView = $form->createView();

        return $this->render('category_create_form.html.twig',
            ['formView' => $formView]);


        //vérifie si la requête est post
        //if ($request->isMethod('POST')) {
        // //   // récupère les données envoyées par le formulaire
        //   $title = $request->request->get('title');
        //    $color = $request->request->get('color');

        //    //crée une nouvelle instance de l'entité Category
        //    $category = new Category();

        //rempli les propriétés avec les données récupérées
        //    $category->setTitle($title);
        //    $category->setColor($color);

        //pré sauvegarde mes entités
        //   $entityManager->persist($category);
        //execute la requête sql dans la bdd
        //   $entityManager->flush();

        //  return $this->redirectToRoute('categories'); // je redirige vers ma liste de catégories
        //}

        //return $this->render('category_create_form.html.twig');


        // je créé une instance de l'entité Category
        // car c'est elle qui représente les catégories dans mon application
        //$category = new Category();

        // j'utilise les méthodes set pour remplir
        // les propriétés de mon article
        //$category->setTitle('film');
        //$category->setColor('orange');

        // à ce moment, la variable $article
        // contient une instance de la classe Article avec
        // les données voulues(sauf l'id car il sera généré par la BDD)

        // j'utilise l'instance de la classe
        // EntityManager. C'est cette classe qui me permet de sauver / supprimer
        // des entités en BDD
        // L'entity manager et Doctrine savent que l'entité correspond à la table article
        // et que la propriété title correspond à la colonne title grâce aux annotations
        // donc L'entity manager sait comment faire correspondre mon instance d'entité à un
        // enregistrement dans la table
        //$entityManager->persist($category);
        //$entityManager->flush();

        // persist permet de pre-sauvegarder mes entités
        // flush execute la requête SQL dans ma BDD
        // pour créer un enregistrement d'article dans la table
        //return $this->redirectToRoute('categories'); // je redirige vers ma liste de catégories


    }

    #[Route('/category/delete/{id}', name: 'category_delete', requirements: ['id' => '\d+'])]
    public function deleteCategory(int $id, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        //dd('yo');
        // je récupère la catégorie que je veux supprimer
        // le repository fait la requête SQL et reconstruit une instance de la classe Category
        // je dois récupérer une instance de classe car pour la suppression Doctrine a besoin d'une instance de classe
        $category = $categoryRepository->find($id);

        if (!$category) {
            return $this->redirectToRoute('error');
        }

        // j'utilise la méthode remove de l'entity manager pour supprimer l'article
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('categories');
    }

    #[Route('/category/update/{id}', name: 'category_update', requirements: ['id' => '\d+'])]
    public function updateCategory(int $id, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, Request $request): Response
    {

        $category = $categoryRepository->find($id);

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {;
            $entityManager->persist($category);
            $entityManager->flush();
        }

        //crée une vue du formulaire
        $formView = $form->createView();

        return $this->render('category_update.html.twig',
            ['formView' => $formView,
                'category' => $category
            ]);

    }
        // je récupère en bdd la catégorie liéé à l'id de l'url
        //doctrine créé une instance de Categorie avec les données de la catégorie
        //$category = $categoryRepository->find($id);

        //si c'est une requête post
        //if ($request->isMethod('POST')) {
        // récupère les données envoyées par le formulaire
        //    $title = $request->request->get('title');
        //    $color = $request->request->get('color');

        //modifie les propriétés avec les données récupérées
        //    $category->setTitle($title);
        //    $category->setColor($color);

        //maj de la caté en bdd
        //   $entityManager->persist($category);
        //   $entityManager->flush();

        //    return $this->redirectToRoute('categories'); // je redirige vers ma liste de catégories
        //}


        // j'envoie au formulaire twig
        // la catégorie existant en BDD
        // pour préremplir les champs
        //return $this->render('category_update.html.twig', [
        //     'category' => $category]);

        //}

    }