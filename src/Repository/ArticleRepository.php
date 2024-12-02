<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function search(string $search): array {
        // Crée un QueryBuilder pour construire dynamiquement une requête SQL
        return $this->createQueryBuilder('a')
            // Ajoute une condition WHERE pour rechercher des articles dont le titre contient le texte recherché
            ->where('a.title LIKE :search')
            // Ajoute une autre condition WHERE pour rechercher dans le contenu des articles (OR condition)
            ->orWhere('a.content LIKE :search')
            // Définit le paramètre ':search' avec des wildcards (%) pour une recherche partielle
            ->setParameter('search', '%'.$search.'%')
            ->orderBy('c.id', 'DESC')
            // Finalise la requête SQL
            ->getQuery()
            // Exécute la requête et retourne les résultats sous forme d'un tableau d'entités Article
            ->getResult();
    }

}
