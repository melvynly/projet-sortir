<?php

namespace App\Repository;


use App\Data\RechercheDonnees;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findSearch(RechercheDonnees $search, $user):array
    {
        $query = $this
            ->createQueryBuilder('s');
//        ->select('c','p')
//            -> join('p.categories','c');

        if (!empty($search->q)){
            $query = $query
                ->andWhere('s.nom LIKE :q ')
                ->setParameter('q',"%".$search->q."%");
        }

        if (!empty($search->dateMin) && !empty($search->dateMax)){
            $query = $query
                // les : servent à se appeler la date min qui est settée dans le set parameter avec la datemin recupére dans le search
                ->andWhere('s.dateHeureDebut BETWEEN :dateMin AND :dateMax ')
                ->setParameter('dateMin',$search->dateMin)
                ->setParameter('dateMax',$search->dateMax);
        }


        if (!empty($search->orga)){
            $query = $query
                ->andWhere('s.organisateur = :orga ')
                ->setParameter('orga',$user->getId());
        }

//        if (!empty($search->inscrit)){
//            $query = $query
//                ->andWhere('s.id = :id')
//                ->select('u','s')
//               -> join('s.user','u')
//                ->setParameter('id',$user->getId());
//        }
//
//        if (!empty($search->pasInscrit)){
//            $query = $query
//                ->andWhere('s.nom LIKE :q ')
//                ->setParameter('q',"%".$search->q."%");
//        }

        if (!empty($search->passee)){
            $query = $query
                ->andWhere('s.dateHeureDebut < :now ')
                ->setParameter('now',date('Y-m-d'));

        }





        return $query->getQuery()->getResult();
    }
}
