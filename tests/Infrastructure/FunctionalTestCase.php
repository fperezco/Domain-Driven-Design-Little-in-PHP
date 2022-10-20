<?php
declare(strict_types=1);

namespace App\Tests\Infrastructure;

use App\Tests\Resources\Factory\FakerFactory;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FunctionalTestCase extends KernelTestCase
{
    public FakerFactory $fakerFactory;
    public EntityManager $entityManager;
    private Connection $connection;

    public function setUp(): void
    {
        $this->fakerFactory = new FakerFactory();
        $this->getEntityManagerAndInitTransaction();
        parent::setUp();
    }

    private function getEntityManagerAndInitTransaction():void{
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->connection = $this->entityManager->getConnection();
        $this->connection->beginTransaction();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->connection->rollBack();
    }

    protected function clearUnitOfWork(): void
    {
        $this->entityManager->clear();
    }

    protected function truncateEntities(array $entities)
    {
        $databasePlatform = $this->connection->getDatabasePlatform();
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $this->connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');
        }
        foreach ($entities as $entity) {
            $query = "DELETE FROM ".$this->entityManager->getClassMetadata($entity)->getTableName();
            $this->connection->executeStatement($query);
        }
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $this->connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
        }
    }

}