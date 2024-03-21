<?php

namespace App\Repository;

use App\Entity\Product;
use App\Tools\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function PHPUnit\Framework\isEmpty;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Recherche de produits par un objet search (sur nom du produit et/ou catégories du produit)
     * @return Product[]
     */
    public function findWidthSearch(Search $search)
    {
        $query = $this->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.category', 'c');

        if (!empty($search->categories)) {
            $query = $query
                ->andWhere("c.id IN (:categories)")
                ->setParameter("categories", $search->categories)
                ;
            //dd($query->getQuery());
        }

        if ($search->string != null && !empty($search->string)) {
            $query = $query
                ->orWhere("p.name LIKE :string")
                ->setParameter("string", "%".$search->string."%")
                ;
        }

        //dd($query->getQuery());
        return $query->getQuery()->getResult();
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
