<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Doctrine\Migrations\Events;

class MigrationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            Events::onMigrationsMigrated => [
                ['onMigrationsMigrated', 10],
            ],
            Events::onMigrationsMigrating => [
                ['onMigrationsMigrated', 10],
            ],
        ];
    }

    public function onMigrationsMigrated($event)
    {
        dump($event);
    }
}