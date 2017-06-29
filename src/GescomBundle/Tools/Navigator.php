<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 16/05/17
 * Time: 09:52
 */

namespace GescomBundle\Tools;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Navigator extends Paginator
{
    /** @var array  */
    private $filter;

    /** @var  int */
    private $currentPage;

    /** @var int */
    private $maxPage;

    /** @var string */
    private $context;

    /**
     * Navigator constructor.
     * @internal param Paginator $paginator
     * @param \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder $context
     * @param EntityRepository $repository
     * @param int $page
     * @param array $filter
     */
    public function __construct($context, $repository, $page, $filter)
    {
        parent::__construct($repository->getRowsByPage($page, $filter));
        $this->context = $context;
        $this->filter = $filter;
        $this->maxPage =  floor($this->count() / constant(get_class($repository) . "::MAX_RESULT"));
        if ($this->maxPage == 0){
            $this->maxPage = 1;
        }
        $this->setCurrentPage($page);
    }

    /**
     * @param int $page
     * @return $this
     */
    public function setCurrentPage($page)
    {
        $this->currentPage = $page;
        return $this;
    }

    /**
     * @param bool
     * @return int
     */
    public function getNextPage()
    {
        if ($this->currentPage < $this->maxPage){
            return $this->currentPage + 1;
        }
        return $this->currentPage;
    }

    /**
     * @param bool
     * @return int
     */
    public function getPrevPage()
    {
        if ($this->currentPage > 1){
            return $this->currentPage - 1;
        }
        return $this->currentPage;
    }

    /**
     * @param bool
     * @return int
     */
    public function getFirstPage()
    {
        if ($this->currentPage <= 4) {
            return 1;
        }
        return $this->currentPage - 4;
    }

    /**
     * @param bool
     * @return int
     */
    public function getLastPage()
    {
        if ($this->currentPage <= 4){
            $newPage = 10;
        } else {
            $newPage = $this->currentPage + 5;
        }
        if ($newPage > $this->maxPage){
            $newPage = $this->maxPage;
        }
        return $newPage;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param bool
     * @return int
     */
    public function getMaxPage()
    {
        return $this->maxPage;
    }

    /**
     * @return mixed
     */
    public function getEntityFilter()
    {
        $class = "\\GescomBundle\\Entity\\" . $this->context . "Filter";
        $filter = new $class();
        if (! is_null($this->filter)) {
            foreach($this->filter as $field => $value) {
                if ($value !== "") {
                    $accessor = "set" . ucwords($field);
                    $filter->$accessor($value);
                }
            }
        }
        return $filter;

    }

}