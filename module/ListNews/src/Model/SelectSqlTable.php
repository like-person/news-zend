<?php
namespace ListNews\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class SelectSqlTable
{
    private $adapter;
    public function __construct()
    {
        $this->adapter = new Adapter([
            'driver'   => 'Mysqli',
            'database' => 'test_news',
            'username' => 'root',
            'password' => '',
        ]);
    }
    public function Sql( $sql )
    {
        $stmt = $this->adapter->createStatement($sql);
        $stmt->prepare();
        $result = $stmt->execute();
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);
            return $resultSet;
        }
        return array();
    }    
}