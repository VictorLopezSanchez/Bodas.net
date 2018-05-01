<?php

namespace ApiBundle\Services;

interface SimulatorServiceInterface
{
    public function getLogResults($name_simulator);
    public function addSimulateRequest($params);
    public function addLogHistory($params);
    public function addLogger($params);
    public function setCountCalls($num);
    public function getCountCalls();
    }
