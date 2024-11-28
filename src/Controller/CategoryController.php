<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/category/{id}', name: 'category_show')]
    //route pour afficher la catégorie je lui ai demandé suivant son id, via /category/id
    public function showCategory(int $id, CategoryRepository $categoryRepository): Response
    {
        // méthode find($id) du repo, récupère la catégorie suivant son id
        $categoryFound = $categoryRepository->find($id);

        if (!$categoryFound) { // si category = null
            return $this->redirectToRoute('error'); //redirige vers ma page error (grâce à symfony)
        }
        return $this->render('category_show.html.twig',
            ['category' => $categoryFound]);
        // rend le fichier twig en lui passant la catégorie récupérée

    }

}