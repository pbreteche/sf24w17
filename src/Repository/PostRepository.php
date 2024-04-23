<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[]
     */
    public function findByTitleLike(string $searchQuery): array
    {
        // Syntaxe DQL
        // * limitée dénominateur commun à MySQL, Postgres, SQLite, etc.
        // * travaille autour des classes d'entité, et non sur les tables
        return $this->getEntityManager()->createQuery('
            SELECT p FROM '.Post::class.' p
            WHERE p.title LIKE :query')
            ->setMaxResults(10)
            ->setParameter('query', '%'.$searchQuery.'%')
            ->getResult()
        ;
    }

    /**
     * @return Post[]
     */
    public function findByTitleLikeWithQueryBuilder(string $searchQuery): array
    {
        return $this->createQueryBuilder('post')
            ->andWhere('post.title LIKE :query')
            ->getQuery()
            ->setMaxResults(10)
            ->setParameter('query', '%'.$searchQuery.'%')
            ->getResult()
        ;
    }
}
