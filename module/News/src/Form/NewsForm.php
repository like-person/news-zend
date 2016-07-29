<?php
namespace News\Form;

use Zend\Form\Form;


class NewsForm extends Form
{
    public function __construct($themes = array())
    {
        // We will ignore the name provided to the constructor
        $themes_list = array();
        foreach ($themes as $row)
        {
           $themes_list[$row->theme_id] = $row->theme_title;
        }
        parent::__construct('news');
      
        $this->add([
            'name' => 'news_id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'theme_id',
            'type' => 'select',
            'options' => [
                'label' => 'Theme',
                 'options' => $themes_list,
            ],            
        ]);        
        $this->add([
            'name' => 'date',
            'type' => 'text',
            'options' => [
                'label' => 'Date',
            ],
        ]);
        $this->add([
            'name' => 'title',
            'type' => 'text',
            'options' => [
                'label' => 'Title',
            ],
        ]);
        $this->add([
            'name' => 'text',
            'type' => 'textarea',
            'options' => [
                'label' => 'Text',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
