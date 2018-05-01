<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

class LogHistoryRepository extends EntityRepository implements LogHistoryRepositoryInterface
{
    /**
     * @param $logHistory
     * @return mixed
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($logHistory) {
        $em = $this->getEntityManager();
        $em->persist($logHistory);
        $em->flush();

        return $logHistory;
    }
}