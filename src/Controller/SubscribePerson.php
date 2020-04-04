<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/4/20
 * Time: 12:06
 */

namespace App\Controller;


use App\Entity\Device;
use App\Entity\Person;
use App\Entity\Proposal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SubscribePerson
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Person $data, Request $request): Person
    {
        $request = json_decode($request->getContent());
        if (isset($request->token)) {
            $device = $this->getOrCreateDevice($request->token);

            $device->addPeople($data);
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