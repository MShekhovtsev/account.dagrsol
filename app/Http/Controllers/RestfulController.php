<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;

/**
 * Class RestfulController
 * @package App\Http\Controllers
 */
class RestfulController extends MasterController
{

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    public function get($model){
        return $model->get();
    }

    public function find($model, $id){
        return $model->find($id);
    }

    public function filter($model){
        $query = $model->newQuery();
        return $this->get($query);
    }

    public function create($model, Request $request){
        $object = $model->create($request->all());
        return $object;
    }

    public function update($model, Request $request, $id){
        $object = $model->find($id);
        $object->update($request->all());
        return $object;
    }

    public function delete($model, $ids){
        $ids = explode(',', $ids);
        $model->delete($ids);
        return $ids;
    }
}
