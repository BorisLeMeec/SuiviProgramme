<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/3/20
 * Time: 21:32
 */

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Person;
use App\Entity\Proposal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SearchProposal
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $category = null;
        $person = null;

        if ($request->query->get('category')) {
            $category = $this->em->getRepository(Category::class)
                ->findOneById($request->query->get('category'));

        }

        if ($request->query->get('person')) {
            $person = $this->em->getRepository(Person::class)
                ->findOneById($request->query->get('person'));
        }

        $proposals = $this->em->getRepository(Proposal::class)
            ->search($person, $category);

        return $proposals;
    }
}