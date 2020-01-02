<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countNbArticles()
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $limit
     * @return mixed
     */
    public function findLatest($limit)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->orderBy('a.article_created_at','DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $limit
     * @return mixed
     */
    public function articleByNbComments($limit)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->orderBy('a.nbcomments', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @method Article[]
     * @throws \Exception
     */
    public function findAllRecent()
    {

        return $this->createQueryBuilder('a')
            ->where('a.article_created_at <= :date')
            ->setParameter('date', new \DateTime(date('Y-m-d H:i:s')))
            ->orderBy('a.article_created_at','DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @param $value
//     * @return mixed
//     */
//    public function findByCategory($value)
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.tags = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getResult();
//    }

    /**
     * @param $value
     * @return mixed
     */
    public function findOneByName($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.title = :val')
            ->setParameter('val', $value)
            ->getQuery()
            //->getOneOrNullResult()
            ->getResult()
            ;
    }

    /**
     * @param $value
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function findOneBySlug($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.slug = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
//            ->getResult()
            ;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function findOneByID($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            //->getOneOrNullResult()
            ->getResult()
            ;
    }

    /**
     * @param string $query
     * @param int $limit
     * @return mixed
     */
    public function findAllMatching(string $query, int $limit = 100)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.title LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function findNbArticleByCategory(int $limit = 3)
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.category)')
            ->groupBy('a.category')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
