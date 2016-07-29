<?php
//Контроллер для вывода новостей
namespace ListNews\Controller;

use News\Model\NewsTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ListNews\Model\SelectSqlTable;


class ListNewsController extends AbstractActionController
{
    // Add this property:
    private $table;

    // Add this constructor:
    public function __construct(NewsTable $table)
    {
        $this->table = $table;
    }
    //Постраничный вывод всех новостей
    public function indexAction()
    {                
        $paginator = $this->table->getPaginator();
        
        //Количество новостей на странице
        $paginator->setItemCountPerPage(5);
        $page = $this->params()->fromRoute('page');
        $paginator->setCurrentPageNumber($page);
        
        //Данные для левого меню
        $dates = new SelectSqlTable();
        $themes = new SelectSqlTable();
        
        return new ViewModel([
            'dates' =>  $dates->Sql('SELECT DATE_FORMAT(date,\'%Y\') as year, DATE_FORMAT(date,\'%M\') as month, DATE_FORMAT(date,\'%m\') as nmonth, count(DATE_FORMAT(date,\'%m\')) as cnt_month FROM news group by DATE_FORMAT(date,\'%Y-%m\') order by date DESC'),
            'themes' =>  $themes->Sql('SELECT themes.theme_title, themes.theme_id, count(news.theme_id) as cnt_theme FROM news inner join themes on news.theme_id=themes.theme_id  group by news.theme_id order by news.date DESC'),
            'listnews' =>  $paginator->getCurrentItems(),
            'paginator' => $paginator,
        ]);
    }
    //Подробная новость
    public function moreAction()
    {
        $id = (int) $this->params()->fromRoute('num', 0);
        if (0 === $id) {
            return $this->redirect()->toRoute('listnews');
        }
        $news = $this->table->getNews($id);
        
        try {
            $news = $this->table->getNews($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('listnews');
        }
        return new ViewModel([
            'new' => $news,
        ]);
    }
    //Постраничный вывод новостей по теме
    public function themeAction()
    {         
        $theme_id = (int) $this->params()->fromRoute('num', 0);  
        if ( empty($theme_id ) ) {
            return $this->redirect()->toRoute('listnews');
        }
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
    //Постраничный вывод новостей за определнный год
    public function yearAction()
    { 
        $year = (int) $this->params()->fromRoute('num', 0);        
        if ( empty($year ) ) {
            return $this->redirect()->toRoute('listnews');
        }        
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
    //Постраничный вывод новостей за определнный месяц
    public function monthAction()
    {         
        $year = (int) $this->params()->fromRoute('num', 0); 
        if ( empty($year ) ) {
            return $this->redirect()->toRoute('listnews');
        }
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
