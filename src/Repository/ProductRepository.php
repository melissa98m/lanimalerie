<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    
    /**
     * requete qui permet de recupererles produits en fonction de la recherche de l'utilisateur
     *@return Product()
     */

    public function findWithSearch(Search $search){
        $query =  $this->createQueryBuilder('p')
                        ->select('c', 'p')
                        ->join('p.category' , 'c');
    if(!empty($search->categories)){
        
        $query = $query
        ->andWhere('c.id IN (:categories)')
        ->setParameter('categories', $search->categories);
    }
    if(!empty($search->categories)){
        $query = $query
        ->andWhere('p.name LIKE :string')
        ->setParameter('string', "%{$search->string}%");
    }
     
   return $query->getQuery()->getResult();
    }




    public function findOnetById($id)
    {
        $q = $this->createQueryBuilder('p')
            ->select('p.id')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function findByName(string $query)
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('p.name', ':query'),
                        $qb->expr()->like('p.description', ':query'),
                    ),
                   
                )
            )
            ->setParameter('query', '%' . $query . '%')
        ;
        return $qb
            ->getQuery()
            ->getResult();
    }

    public function findOneByCategory($category){
        $query =  $this->createQueryBuilder('p')
        ->select('c', 'p')
        ->join('p.category' , 'c')
        ->andWhere('c.id IN (:categories)')
        ->setParameter('categories', $category->categories);
   return $query
            ->getQuery()
            ->getResult();
}
}