<?php
//Класс формирования таблицы новостей вместе с темами, используется для компонента постраничного отображения
namespace News\Model;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Having;
use Zend\Db\Sql\Join;
use Zend\Db\TableGateway\TableGateway;

class MyTableGateaway extends DbSelect
{
     /**
     * Constructs instance.
     *
     * @param TableGateway                      $tableGateway
     * @param null|Where|\Closure|string|array  $where
     * @param null|string|array                 $order
     * @param null|string|array                 $group
     * @param null|Having|\Closure|string|array $having
     * @param null|Join|\Closure|string|array $join
     */
    public function __construct(TableGateway $tableGateway, $where = null, $order = null, $group = null, $having = null)
    {
        $sql    = $tableGateway->getSql();
        $select = $sql->select();
        $select->columns(['title','text','date','news_id'],false);
        if ($where) {
            $select->where($where);
        }
        if ($order) {
            $select->order($order);
        }
        if ($group) {
            $select->group($group);
        }
        if ($having) {
            $select->having($having);
        }
        $join_table = 'themes';
        $on = 'news.theme_id=themes.theme_id';
        if ($join_table) {
            $select->join($join_table,$on);
        }

        $resultSetPrototype = $tableGateway->getResultSetPrototype();
        parent::__construct($select, $sql, $resultSetPrototype);
    }
}