<?php

namespace ApiBundle\Services;

use ApiBundle\Entity\LogHistory;
use ApiBundle\Repository\LogHistoryRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Container;

class SimulatorService implements SimulatorServiceInterface
{
    /**
     * @var LogHistoryRepositoryInterface
     */
    private $log_history_repository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Container
     */
    private $container;

    /**
     * SimulatorService constructor.
     * @param LogHistoryRepositoryInterface $log_history_repository
     * @param LoggerInterface $logger
     * @param Container $container
     */
    public function __construct(
        LogHistoryRepositoryInterface $log_history_repository,
        LoggerInterface $logger,
        Container $container)
    {
        $this->log_history_repository = $log_history_repository;
        $this->logger = $logger;
        $this->container = $container;
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function addSimulateRequest($params) {

        try {
            $numRequest = $this->setCountCalls($this->getCountCalls() + 1);

            /* 10% de peticiones que retornará error */
            if ($numRequest >= 0 && $numRequest <= 6) {
                $this->setDayRequests('fail');
                throw new \Exception('Cant execute 10% of request');
            }

            $this->setDayRequests('success');

            /** @var LogHistory $logHistory */
            $logHistory = $this->addLogHistory($params);
            $this->addLogger($params);

            /* El 10% de 60 es 6 , por lo tanto, 66 peticiones hay que recibir */
            $closed = ($numRequest === 66) ? true : false;
            if ($closed) {
                $this->setCountCalls(0);
            }

            $logRes = $this->getLogResults('nuptic-43');

            $res = [
                'id' => $logHistory->getId(),
                'closed' => $closed,
                'res' => $logRes
            ];

        } catch (\Exception $e) {
            $res = ['res' => 'ko', 'msg' => $e->getMessage()];
        }

        return $res;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function addLogHistory($params) {

        $logHistory = new LogHistory();

        $logHistory->setNameSimulator($params['name_simulator']);
        $logHistory->setCardinalPoint($params['cardinal_point']);
        $logHistory->setNumCall($params['num']);
        $logHistory->setPath($params['path']);
        $logHistory->setDate(new \DateTime());

        return $this->log_history_repository->save($logHistory);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function addLogger($params) {
        try {

            if ($params['cardinal_point'] === 'Este') {
                $this->logger->info(
                    ' Name Simulator:' . $params['name_simulator'] .
                    ' Cardinal Point: ' . $params['cardinal_point'] .
                    ' Number of Call:' . $params['num'] .
                    ' Path: ' . $params['path']
                );
            }
        } catch (\Exception $e) {
            $this->logger->error('Logger error: ' . $e->getMessage());
        }
    }

    /**
     * @param String $name_simulator
     * @return array
     */
    public function getLogResults($name_simulator) {

        try {

            $res = $this->log_history_repository->findBy(['name_simulator' => $name_simulator]);

        } catch (\Exception $e) {
            $this->logger->error('Error: ' . $e->getMessage());
            $res = ['res' => 'ko', 'msg' => $e->getMessage()];
        }

        return $res;
    }

    /**
     * @param String $type
     */
    public function setDayRequests($type) {

        try {
            $redis = $this->container->get('snc_redis.default');

            $key = "count:day:" . $type;
            $redis->incr($key);

        } catch (\Exception $e) {
            $this->logger->error('Redis error: ' . $e->getMessage());
        }
    }

    /**
     * @param $num
     * @return int
     */
    public function setCountCalls($num) {

        try {
            $redis = $this->container->get('snc_redis.default');

            $key = "count:calls";
            $redis->set($key, $num);

        } catch (\Exception $e) {
            $this->logger->error('Redis error: ' . $e->getMessage());
        }

        return intval($num);
    }

    public function getCountCalls() {

        try {
            $redis = $this->container->get('snc_redis.default');

            $key = "count:calls";
            $res = $redis->get($key);

        } catch (\Exception $e) {
            $this->logger->error('Redis error: ' . $e->getMessage());
            $res = 0;
        }

        return intval($res);
    }
}
