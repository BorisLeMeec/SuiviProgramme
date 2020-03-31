<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/31/20
 * Time: 13:00
 */

namespace App\Controller;


use App\Entity\Device;
use App\Entity\Proposal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class LikeProposal
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Proposal $data, Request $request): Proposal
    {
        $data->setLikes($data->getLikes()+1);
        $this->em->persist($data);

        $request = json_decode($request->getContent());
        if (isset($request->token)) {
            $device = $this->getOrCreateDevice($request->token);

            $device->addFavorite($data);
        }

        $this->em->flush();
        return $data;
    }

    public function getOrCreateDevice($token) : Device{
        $device =$this->em->getRepository(Device::class)
            ->findOneByToken($token);

        if ($device)
            return $device;

        $device = new Device();
        $this->em->persist($device);

        $device->setToken($token);

        $this->em->flush();
        return $device;
    }
}