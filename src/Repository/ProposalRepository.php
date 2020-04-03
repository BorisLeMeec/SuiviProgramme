<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/3/20
 * Time: 21:43
 */

namespace App\Repository;


use App\Entity\Category;
use App\Entity\Person;
use App\Entity\Proposal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ProposalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proposal::class);
    }

    public function search(?Person $person, ?Category $category){
        $qb = $this->createQueryBuilder('p');
        if ($person) {
            $qb->where('p.person = :person')
                ->setParameter('person', $person);
        }
        if ($category) {
            $qb->join('p.category', 'c')
                ->andWhere('c= :category')
                ->setParameter('category', $category);
        }

        return $qb->getQuery()->execute();
    }
}