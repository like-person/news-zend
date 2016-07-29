<?php
//Класс модели News, поля и ограничения по полям
namespace News\Model;
use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class News
{
    public $news_id;
    public $date;
    public $theme_id;
    public $text;
    public $title;
    public $theme_title;
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->news_id     = !empty($data['news_id']) ? $data['news_id'] : null;
        $this->date = !empty($data['date']) ? $data['date'] : null;
        $this->theme_id  = !empty($data['theme_id']) ? $data['theme_id'] : null;
        $this->text = !empty($data['text']) ? $data['text'] : null;
        $this->title = !empty($data['title']) ? $data['title'] : null;
        $this->theme_title = !empty($data['theme_title']) ? $data['theme_title'] : null;
    }
    public function getArrayCopy()
    {
        return [
            'news_id'     => $this->news_id,
            'date' => $this->date,
            'title'  => $this->title,
            'text'  => $this->text,
            'theme_id'  => $this->theme_id,
        ];
    }    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'news_id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'text',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'date',
            'required' => true,
        ]);
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }    
}
