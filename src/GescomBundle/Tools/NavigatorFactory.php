<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 19/05/17
 * Time: 11:19
 */

namespace GescomBundle\Tools;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class NavigatorFactory
{

    /** @var \Doctrine\Bundle\DoctrineBundle\Registry|object  */
    protected $doctrine;

    /** @var string */
    protected $controllerName;

    /** @var  string */
    protected $page;

    /** @var array */
    protected $filter;

    /**
     * NavigatorFactory constructor.
     * @param RequestStack $requestStack
     * @param EntityManager $doctrine
     * @param FilterTransform $filterTransformer
     */
    public function __construct($requestStack, $doctrine, $filterTransformer)
    {
        $request = $requestStack->getCurrentRequest();
        $controller = $request->get('_controller');
        $this->page = $request->get("page");
        $this->filter = $request->get("gescom_bundle_filter_type");
        if (is_null($this->filter)){
            $this->filter = $filterTransformer->transform($request->get("filter"));
        }
        $controller = explode('::', $controller);
        $controller = explode('\\', $controller[0]);

        $this->controllerName = preg_replace('/Controller/', '', $controller[count($controller) - 1]);

        $this->doctrine = $doctrine;
    }

    /**
     * @return Navigator
     */
    public function get()
    {
        $repositoryName = "GescomBundle:" . $this->controllerName;
        $repository = $this->doctrine->getRepository($repositoryName);
        return new Navigator($repository, $this->page, $this->filter);
    }
}