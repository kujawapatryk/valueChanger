<?php

namespace App\Repository;

use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<History>
 *
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function exchangeAndSave(int $first, int $second): array
    {
        $history = new History();
        $history->setFirstIn($first);
        $history->setSecondIn($second);
        $this->entityManager->persist($history);
        $this->entityManager->flush();

        $first = $first + $second;
        $second = $first - $second;
        $first = $first - $second;

        $history->setFirstOut($first);
        $history->setSecondOut($second);
        $this->entityManager->flush();

        return ['first' => $first, 'second' => $second];
    }

}
