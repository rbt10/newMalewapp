<?php

namespace App\Repository;

use App\Entity\Recette;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Recette>
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Recette::class);
    }

    public function findBySearch(SearchData $search)
    {
        $query = $this
            ->createQueryBuilder('r')
            ->select('c','r')
            ->join('r.categorie', 'c');

        if(!empty($search->categories)){
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter( 'categories', $search->categories);
        }

        if(!empty($search->q)){
            $query = $query
                ->andWhere('r.libelle LIKE :q')
                ->setParameter( 'q',"%{$search->q}%");
        }

        $query =  $query->getQuery()->getResult();
        $data =$this->paginator->paginate($query, $search->page, 12);

        return $data;

    }



    //    /**
    //     * @return Recette[] Returns an array of Recette objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recette
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
