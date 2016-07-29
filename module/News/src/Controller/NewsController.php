<?php
namespace News\Controller;

use News\Model\NewsTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use News\Form\NewsForm;
use News\Model\News;
use ListNews\Model\SelectSqlTable;

class NewsController extends AbstractActionController
{
    // Add this property:
    private $table;

    // Add this constructor:
    public function __construct(NewsTable $table)
    {
        $this->table = $table;
    }
    public function indexAction()
    {
        return new ViewModel([
            'news' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $themes = new SelectSqlTable();
        $form = new NewsForm($themes->Sql('SELECT * FROM themes'));
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $news = new News();
        $form->setInputFilter($news->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $news->exchangeArray($form->getData());
        $this->table->saveNews($news);
        return $this->redirect()->toRoute('news');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('news_id', 0);
        $request = $this->getRequest();
        if( empty($id) ) $id = (int) $request->getPost('news_id');

        if (0 === $id) {
            return $this->redirect()->toRoute('news', ['action' => 'add']);
        }

        try {
            $news = $this->table->getNews($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('news', ['action' => 'index']);
        }

        $themes = new SelectSqlTable();
        $form = new NewsForm($themes->Sql('SELECT * FROM themes'));
        $form->bind($news);
        $form->get('submit')->setAttribute('value', 'Save');

        $viewData = ['news_id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($news->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->saveNews($news);

        // Redirect to news list
        return $this->redirect()->toRoute('news', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('news_id', 0);
        if (!$id) {
            $request = $this->getRequest();
            if ($request->isPost()) {
                $del = $request->getPost('del', 'No');

                if ($del == 'Yes') {
                    $id = (int) $request->getPost('news_id');
                    $this->table->deleteNews($id);
                }
            }
            
            return $this->redirect()->toRoute('news');
        }

        return [
            'id'    => $id,
            'news' => $this->table->getNews($id),
        ];
    }
}
