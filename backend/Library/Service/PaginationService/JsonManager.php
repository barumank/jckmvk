<?php


namespace Backend\Library\Service\PaginationService;
use Phalcon\Paginator\Adapter\QueryBuilder;

class JsonManager
{
    private $paginate;
    private $page;
    private $pageSize;

    /**
     * @param \Phalcon\Mvc\Model\Query\BuilderInterface $builder
     * @param int $page
     * @param int $pageSize
     */
    public function execute($builder, $page, $pageSize)
    {
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->paginate = (new QueryBuilder(
            [
                "builder" => $builder,
                "limit"   => $pageSize,
                "page"    => $page,
            ]
        ))->getPaginate();

    }

    /**
     * @return mixed
     */
    public function getPaginate()
    {
        return $this->paginate;
    }


    public function getPagination()
    {
        $paginate = $this->paginate;
        if($paginate->total_pages<=1){
            return[];
        }
        $out = [];
        if($paginate->current>1){
            $out[] = ['page'=>$paginate->before,'label'=>$paginate->before,'type'=>'prev'];
        }
        $reserve = 2;
        $reserveRange=5;

        $startP =  $paginate->current - $reserve;
        if($startP<=0){
            $startP=1;
        }
        $countP =  $startP + $reserveRange -1;
        if($countP>=$paginate->last){
            $countP = $paginate->last;
        }
        for($i=$startP;$i<=$countP; $i++ ){
            $out[] = ['page'=>$i,'label'=>$i,'type'=>'page'];
        }
        if($paginate->last - $paginate->current >= 1){
            $out[] = ['page'=>$paginate->next,'label'=>$paginate->next,'type'=>'next'];
        }
        return $out;
    }
}