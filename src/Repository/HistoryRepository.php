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
class HistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }
    public function exchangeAndSave(int $first, int $second): array
    {
        $history = new History();
        $history->setFirstIn($first);
        $history->setSecondIn($second);
        $this->_em->persist($history);
        $this->_em->flush();

        $first = $first + $second;
        $second = $first - $second;
        $first = $first - $second;

        $history->setFirstOut($first);
        $history->setSecondOut($second);
        $this->_em->flush();

        return ['first' => $first, 'second' => $second];
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

}
