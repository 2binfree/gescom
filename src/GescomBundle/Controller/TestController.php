<?php

namespace GescomBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TestController extends Controller
{
    /**
     * @return Response
     * @internal param $name
     * @Route("/test", name="test")
     */
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository("GescomBundle:Supplier");
        $data = $repo->getCategoriesBySupplier(3136);
        dump($data);
        return new Response("test controler");
    }
}
