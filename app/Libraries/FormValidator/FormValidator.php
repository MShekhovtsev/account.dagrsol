<?php

namespace App\Libraries\FormValidator;

use App\Libraries\FormValidator\Converter\Base\Converter;
use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;

class FormValidator extends FormBuilder {

    public $converter;
    public $rules;

    public function __construct(HtmlBuilder $html, UrlGenerator $url, Factory $view, $csrfToken, Converter $converter)
    {
        parent::__construct($html, $url, $view, $csrfToken);
        $this->converter = $converter;
    }

    public function setRules(array $rules){
        $this->converter->set($rules, null);
    }

    public function model($model, array $options = [])
    {
        if($model->exists){
            $options['method'] = 'put';
            $options['url'] = $model->route('update');
        } else {
            $options['url'] = $model->route('create');
        }

        $options['novalidate'] = '';

        $this->converter->set($model->validationRules, null);

        return parent::model($model, $options);
    }

    public function input($type, $name, $value = null, $options = [])
    {
        $options = $this->converter->convert(Helper::getFormAttribute($name)) + $options;
        return parent::input($type, $name, $value, $options);
    }

    public function textarea($name, $value = null, $options = [])
    {
        $options = $this->converter->convert(Helper::getFormAttribute($name)) + $options;
        return parent::textarea($name, $value, $options);
    }

    public function select($name, $list = [], $selected = null, $options = [])
    {
        $options = $this->converter->convert(Helper::getFormAttribute($name)) + $options;
        return parent::select($name, $list, $selected, $options);
    }

    protected function checkable($type, $name, $value, $checked, $options)
    {
        $options = $this->converter->convert(Helper::getFormAttribute($name)) + $options;
        return parent::checkable($type, $name, $value, $checked, $options);
    }

    public function submit($value = null, $options = [])
    {
        $options['class'] = 'btn btn-primary';
        return parent::submit($value ,$options);
    }

    public function text($name, $value = null, $options = [])
    {
        $options['class'] = 'form-control';
        return parent::text($name, $value, $options);
    }

    public function password($name, $options = [])
    {
        $options['class'] = 'form-control';
        return parent::password($name, $options);
    }

    public function label($name, $value = null, $options = [], $escape_html = true)
    {
        if($this->model){
            $value = $this->model->label($name);
        }
        return parent::label($name, $value, $options, $escape_html);
    }

}