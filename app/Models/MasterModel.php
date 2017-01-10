<?php

namespace App\Models;


use App\Exceptions\ModelValidationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class MasterModel extends Model
{
    public $classname;

    protected $formData = [];
    protected $relationsData = [];

    public $validationRules = [];

    public function __construct(array $attributes = [])
    {
        $this->classname = strtolower(class_basename($this));
        parent::__construct($attributes);
    }

    function route($type){
        return route('api.'.$type, $this->classname);
    }

    public function label($key){
        return \Lang::has('attributes.' . $key) ? mb_convert_case(\Lang::get('attributes.' . $key), MB_CASE_TITLE, "UTF-8") : mb_convert_case($key, MB_CASE_TITLE, "UTF-8");
    }

    public function form($type = null){
        $view = 'forms.' . $this->classname . ($type ? '_' . $type : '');
        if(\View::exists($view)){
            return \View::make($view, ['model' => $this]);
        }
    }

    public function isOwn(){
        return \Auth::check() && $this->user && $this->user->id === \Auth::user()->id;
    }

    public function fill(array $attributes)
    {
        $this->formData = $attributes;

        if($attributes){
            foreach ($attributes as $key => $data) {

                if (method_exists($this, $key) && $this->$key() instanceof Relation) {
                    $this->relationsData[$key] = $data;
                }

            }
        }

        return parent::fill($attributes);
    }

    protected function buildUniqueExclusionRules() {

        if($this->exists){
            foreach ($this->validationRules as $field => &$ruleset) {
                // If $ruleset is a pipe-separated string, switch it to array
                $ruleset = (is_string($ruleset))? explode('|', $ruleset) : $ruleset;

                foreach ($ruleset as &$rule) {
                    if (strpos($rule, 'unique:') === 0) {
                        // Stop splitting at 4 so final param will hold optional where clause
                        $params = explode(',', $rule, 4);

                        $uniqueRules = array();

                        // Append table name if needed
                        $table = explode(':', $params[0]);
                        if (count($table) == 1) {
                            $uniqueRules[1] = $this->getTable();
                        } else {
                            $uniqueRules[1] = $table[1];
                        }

                        // Append field name if needed
                        if (count($params) == 1) {
                            $uniqueRules[2] = $field;
                        } else {
                            $uniqueRules[2] = $params[1];
                        }

                        if (isset($this->primaryKey)) {
                            if (isset($this->{$this->primaryKey})) {
                                $uniqueRules[3] = $this->{$this->primaryKey};

                                // If optional where rules are passed, append them otherwise use primary key
                                $uniqueRules[4] = isset($params[3])? $params[3] : $this->primaryKey;
                            }
                        } else {
                            if (isset($this->id)) {
                                $uniqueRules[3] = $this->id;
                            }
                        }

                        $rule = 'unique:'.implode(',', $uniqueRules);
                    }

                    if($rule == 'confirmed' && $field == 'password'){
                        $rule = null;
                    }
                }
            }
        }
    }

    public function validate($data){
        $this->buildUniqueExclusionRules();

        $validator = \Validator::make($data, $this->validationRules);

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        return true;
    }

    public function save(array $options = array())
    {
        $attributes = array_merge($this->formData, $options);

        $valid = $this->validate($attributes);

        if ($valid) {
            $saved = parent::save($options);
        } else {
            return false;
        }

        //Automatic saving relations
        if($saved) {

            foreach ($this->relationsData as $key => $data) {
                $relation = $this->$key();

                if ($relation instanceof BelongsToMany) {
                    $relation->sync($data ?: []);
                    $saved = true;
                }
                if ($relation instanceof HasOneOrMany) {
                    $relation->delete();
                    if($data) $relation->createMany((array)$data);
                    $saved = true;
                }
            }
        }

        return $saved;
    }
}