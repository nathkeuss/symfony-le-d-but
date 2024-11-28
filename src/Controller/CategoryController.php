<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/category/{id}', name: 'category_show', requirements: ['id' => '\d+'])]
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

    #[Route('/category/create', name: 'category_create')]
    public function createCategory(EntityManagerInterface $entityManager): Response
    {

        // je créé une instance de l'entité Category
        // car c'est elle qui représente les catégories dans mon application
        $category = new Category();

        // j'utilise les méthodes set pour remplir
        // les propriétés de mon article
        $category->setTitle('film');
        $category->setColor('orange');

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
        $entityManager->persist($category);
        $entityManager->flush();

        // persist permet de pre-sauvegarder mes entités
        // flush execute la requête SQL dans ma BDD
        // pour créer un enregistrement d'article dans la table
        return $this->redirectToRoute('categories');


    }

}