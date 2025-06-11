<?php

namespace App\Repository;

use App\Entity\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Route>
 */
#[ServiceEntityRepository(Route::class)]
class RouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    public function findRoutesWithFilter(
    ?\DateTimeInterface $date1 = null,
    ?\DateTimeInterface $date2 = null,
    int $direction_id = 0,
    int $departure_id = 0,
    ?int $max_price = null
): array {
    $qb = $this->createQueryBuilder('r')
        ->select('DISTINCT r')
        ->innerJoin('r.trips', 't')
        ->leftJoin('r.direction', 'd')
        ->leftJoin('r.departure_city', 'dep');

    
    if ($date1 !== null) {
        $qb->andWhere('t.startDate > :date1')
           ->setParameter('date1', $date1);
    }
    

    if ($direction_id !== 0) {
        $qb->andWhere('d.id = :direction_id')
           ->setParameter('direction_id', $direction_id);
    }

    if ($departure_id !== 0) {
        $qb->andWhere('dep.id = :departure_id')
           ->setParameter('departure_id', $departure_id);
    }

    if ($max_price !== null) {
        $qb->andWhere('t.price <= :max_price')
           ->setParameter('max_price', $max_price);
    }

    $routes = $qb->getQuery()->getResult();
        if ($date2 !== null) {
            $filteredRoutes = [];
            foreach ($routes as $route) {
                $tripEndsBeforeDate2 = false;
                foreach ($route->getTrips() as $trip) {
                    $startDate = $trip->getStartDate();
                    $routeSpan = $route->getRouteSpan(); 
                    $endDate = clone $startDate;
                    $endDate->add($routeSpan);
                    if ($endDate <= $date2 && ($date1===null || $startDate >= $date1)) {
                        $tripEndsBeforeDate2 = true;
                        break; 
                    }
                }
                if ($tripEndsBeforeDate2) {
                    $filteredRoutes[] = $route;
                }
            }
            return $filteredRoutes;
        }
        return $routes;
}

    //    /**
    //     * @return Route[] Returns an array of Route objects
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

    //    public function findOneBySomeField($value): ?Route
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
