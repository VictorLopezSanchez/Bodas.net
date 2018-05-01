<?php

namespace ApiBundle\Controller;

use ApiBundle\Services\SimulatorServiceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;


class SimulatorController extends FOSRestController
{
    /**
     * Receive simulator calls
     *
     * @Post("/simulator")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function simulatorAction(Request $request)
    {
        // Save parameters in array with his name
        $params = [];
        foreach (json_decode($request->getContent()) as $key => $value) {
            $params[$key] = $value;
        }

        /** @var SimulatorServiceInterface $simulator_service */
        $simulator_service = $this->container->get('api.simulator_service');

        $view = View::create(
            $simulator_service->addSimulateRequest($params),
            200)
            ->setFormat('json')
            ->setHeader('Access-Control-Allow-Origin', '*');

        return $this->handleView($view);

    }
}
