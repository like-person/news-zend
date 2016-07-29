<?php
namespace ListNews\Controller;

use News\Model\NewsTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ListNews\Model\SelectSqlTable;


class ListNewsController extends AbstractActionController
{
    // Add this property:
    private $table;
    public $dates;

    // Add this constructor:
    public function __construct(NewsTable $table)
    {
        $this->table = $table;
        $this->dates = clone $table;
    }
    public function indexAction()
    {                
        $paginator = $this->table->getPaginator();
        $paginator->setItemCountPerPage(5);
        $page = $this->params()->fromRoute('page');
        $paginator->setCurrentPageNumber($page);
        
        $dates = new SelectSqlTable();
        $themes = new SelectSqlTable();
        
        return new ViewModel([
            'dates' =>  $dates->Sql('SELECT DATE_FORMAT(date,\'%Y\') as year, DATE_FORMAT(date,\'%M\') as month, DATE_FORMAT(date,\'%m\') as nmonth, count(DATE_FORMAT(date,\'%m\')) as cnt_month FROM news group by DATE_FORMAT(date,\'%Y-%m\') order by date DESC'),
            'themes' =>  $themes->Sql('SELECT themes.theme_title, themes.theme_id, count(news.theme_id) as cnt_theme FROM news inner join themes on news.theme_id=themes.theme_id  group by news.theme_id order by news.date DESC'),
            'listnews' =>  $paginator->getCurrentItems(),
            'paginator' => $paginator,
        ]);
    }
    public function moreAction()
    {
        $id = (int) $this->params()->fromRoute('num', 0);
        if (0 === $id) {
            return $this->redirect()->toRoute('listnews');
        }
        $news = $this->table->getNews($id);
        /*
        try {
            $news = $this->table->getNews($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('listnews');
        }*/
        return new ViewModel([
            'new' => $news,
        ]);
    }
    public function themeAction()
    {         
        $theme_id = (int) $this->params()->fromRoute('num', 0);        
        $paginator = $this->table->getNewsTheme($theme_id);
        $paginator->setItemCountPerPage(5);
        $page = $this->params()->fromRoute('page');
        $paginator->setCurrentPageNumber($page);
        
        $dates = new SelectSqlTable();
        $themes = new SelectSqlTable();        
        return new ViewModel([
            'dates' =>  $dates->Sql('SELECT DATE_FORMAT(date,\'%Y\') as year, DATE_FORMAT(date,\'%M\') as month, DATE_FORMAT(date,\'%m\') as nmonth, count(DATE_FORMAT(date,\'%m\')) as cnt_month FROM news group by DATE_FORMAT(date,\'%Y-%m\') order by date DESC'),
            'themes' =>  $themes->Sql('SELECT themes.theme_title, themes.theme_id, count(news.theme_id) as cnt_theme FROM news inner join themes on news.theme_id=themes.theme_id  group by news.theme_id order by news.date DESC'),
            'listnews' =>  $paginator->getCurrentItems(),
            'paginator' => $paginator,
            'year' => $theme_id,
        ]);
    }
    public function yearAction()
    {         
        $year = (int) $this->params()->fromRoute('num', 0);        
        $paginator = $this->table->getNewsYear($year);
        $paginator->setItemCountPerPage(5);
        $page = $this->params()->fromRoute('page');
        $paginator->setCurrentPageNumber($page);
        
        $dates = new SelectSqlTable();
        $themes = new SelectSqlTable();
        
        return new ViewModel([
            'dates' =>  $dates->Sql('SELECT DATE_FORMAT(date,\'%Y\') as year, DATE_FORMAT(date,\'%M\') as month, DATE_FORMAT(date,\'%m\') as nmonth, count(DATE_FORMAT(date,\'%m\')) as cnt_month FROM news group by DATE_FORMAT(date,\'%Y-%m\') order by date DESC'),
            'themes' =>  $themes->Sql('SELECT themes.theme_title, themes.theme_id, count(news.theme_id) as cnt_theme FROM news inner join themes on news.theme_id=themes.theme_id  group by news.theme_id order by news.date DESC'),
            'listnews' =>  $paginator->getCurrentItems(),
            'paginator' => $paginator,
            'year' => $year,
        ]);
    }
    public function monthAction()
    {         
        $year = (int) $this->params()->fromRoute('num', 0);        
        $paginator = $this->table->getNewsMonth($year);
        $paginator->setItemCountPerPage(5);
        $page = $this->params()->fromRoute('page');
        $paginator->setCurrentPageNumber($page);
        
        $dates = new SelectSqlTable();
        $themes = new SelectSqlTable();
        
        return new ViewModel([
            'dates' =>  $dates->Sql('SELECT DATE_FORMAT(date,\'%Y\') as year, DATE_FORMAT(date,\'%M\') as month, DATE_FORMAT(date,\'%m\') as nmonth, count(DATE_FORMAT(date,\'%m\')) as cnt_month FROM news group by DATE_FORMAT(date,\'%Y-%m\') order by date DESC'),
            'themes' =>  $themes->Sql('SELECT themes.theme_title, themes.theme_id, count(news.theme_id) as cnt_theme FROM news inner join themes on news.theme_id=themes.theme_id  group by news.theme_id order by news.date DESC'),
            'listnews' =>  $paginator->getCurrentItems(),
            'paginator' => $paginator,
            'year' => $year
        ]);
    }    
}
