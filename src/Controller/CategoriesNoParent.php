<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/31/20
 * Time: 20:49
 */

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Proposal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriesNoParent
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
        $categories = $this->em->getRepository(Category::class)
            ->findByParent(null);

        return $categories;
    }
}