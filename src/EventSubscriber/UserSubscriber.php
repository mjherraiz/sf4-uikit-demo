<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

class UserSubscriber implements EventSubscriberInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function encodeUserPassword($event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof User)) {
            return;
        }
        $encoded_password=$this->encoder->encodePassword($entity, $entity->getPassword());
        $entity->setPassword($encoded_password);

        $event['entity'] = $entity;
    }

    public static function getSubscribedEvents()
    {
        return [
           'easy_admin.pre_persist' => 'encodeUserPassword',
           'easy_admin.pre_update' => 'encodeUserPassword',
        ];
    }
}
