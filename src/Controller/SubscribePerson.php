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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Json;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;


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

    public function __invoke(Person $data, Request $request)
    {
        $request = json_decode($request->getContent());
        if (!isset($request->token)) {
            return new JsonResponse(json_encode(['status'=> 'nok']), 400);
        }
        $device = $this->getOrCreateDevice(json_encode($request->token));

        $device->addPeople($data);
        $this->em->flush();

        $this->sendNotifWelcome($device);
        return new JsonResponse(json_encode(['status'=> 'ok']), 201);
    }

    public function sendNotifWelcome(Device $device) {
        $token = json_decode($device->getToken(), true);

        $wp = new WebPush();
        $wp->sendNotification(
            Subscription::create([
                'endpoint' => $token['endpoint'],
                'publicKey' => $token['keys']['p256dh'],
                'authToken' => $token['keys']['auth']
            ]),
            '{msg:"test"}'
        );
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