<?php

namespace App\Libraries\FormBuilder;

use Collective\Html\FormBuilder as Builder;
use Collective\Html\HtmlBuilder;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;

class FormBuilder extends Builder
{

    public function __construct(HtmlBuilder $html, UrlGenerator $url, Factory $view, $csrfToken)
    {
        parent::__construct($html, $url, $view, $csrfToken);
    }

    public function open(array $options = [])
    {
        $options += config('form.default-attributes.form') ?: [];
        return parent::open($options);
    }

    public function model($model, array $options = [])
    {
        if($model->exists){
            $options['method'] = 'put';
            $options['url'] = $model->route('update');
        } else {
            $options['url'] = $model->route('create');
        }

        return parent::model($model, $options);
    }

    public function input($type, $name, $value = null, $options = [])
    {
        $options += config('form.default-attributes.' . $type) ?: [];

        return parent::input($type, $name, $value, $options);
    }

    public function textarea($name, $value = null, $options = [])
    {
        $options += config('form.default-attributes.textarea') ?: [];
        return parent::textarea($name, $value, $options);
    }

    public function select($name, $list = [], $selected = null, $options = [])
    {
        $options += config('form.default-attributes.select') ?: [];
        return parent::select($name, $list, $selected, $options);
    }

    public function label($name, $value = null, $options = [], $escape_html = true)
    {
        if($this->model){
            $value = $this->model->label($name);
        }

        $options += config('form.default-attributes.label') ?: [];

        return parent::label($name, $value, $options, $escape_html);
    }

}