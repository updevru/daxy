<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191010202139 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return \FOS\UserBundle\Model\UserManagerInterface
     */
    private function getUserManager()
    {
        return $this->container->get('fos_user.user_manager');
    }


    public function getDescription() : string
    {
        return 'Add default user';
    }

    public function up(Schema $schema) : void
    {
        $userService = $this->getUserManager();

        $user = $userService->createUser();
        $user->setUsername('admin');
        $user->setEmail('admin');
        $user->setPlainPassword('admin');
        $user->setSuperAdmin(true);
        $user->setEnabled(true);

        $userService->updateUser($user);
    }

    public function down(Schema $schema) : void
    {
        $userService = $this->getUserManager();
        if (!$user = $userService->findUserByUsername('admin')) {
            return;
        }

        $userService->deleteUser($user);
    }
}
