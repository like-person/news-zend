<?php
namespace News\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use News\Model\MyTableGateaway;

class NewsTable
{
    private $tableGateway;
    private $id;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select(function (Select $select) {
            $select->order('date DESC');
       });
    }
    public function getAllNews()
    {
        return $this->tableGateway->select(function (Select $select) {
            $select->join('themes','news.theme_id=themes.theme_id');
            $select->order('date DESC');
       });
    }
    public function getNewsTheme($theme)
    {
        $select = new MyTableGateaway( $this->tableGateway, 'news.theme_id ='.$theme, 'date DESC');
        return new Paginator( $select );
    }    
    public function getNewsYear($year)
    {
        $select = new MyTableGateaway( $this->tableGateway, 'date like \''.$year.'%\'', 'date DESC');
        return new Paginator( $select );
    }
    public function getNewsMonth($year)
    {
        $select = new MyTableGateaway( $this->tableGateway, 'date like \''.substr($year,0,4).'-'.substr($year,4).'%\'', 'date DESC');
        return new Paginator( $select );
    }
    public function getPaginator()
    {
        $select = new MyTableGateaway( $this->tableGateway, null, 'date DESC' );
        return new Paginator( $select );
    }
    public function getNews($id)
    {
        $this->id = (int) $id;
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->join('themes','news.theme_id=themes.theme_id');
            $select->where("news.news_id=$this->id");
       });
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveNews(News $news)
    {       
        $data = [
            'date' => $news->date,
            'theme_id'  => $news->theme_id,
            'text' => $news->text,
            'title' => $news->title,
        ];

        $id = (int) $news->news_id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getNews($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['news_id' => $id]);
    }

    public function deleteNews($id)
    {
        $this->tableGateway->delete(['news_id' => (int) $id]);
    }
}