<?php

namespace App\Models\Traits;

use App\Models\Content\Translatable as Content;

trait Translatable
{
    public function translatable(){
        return $this->morphMany(Content::class, 'model');
    }

    public function getTranslatableAttribute(){
        return $this->getRelationValue('translatable')->keyBy('language');
    }

    public function trans($key = null){
        if($key){
            $translation = $this->translatable->where('language', \App::getLocale())->first();
            return $translation->$key ?? $translation->additional[$key] ?? null;
        }
        return $this->translatable()->where('language', \App::getLocale());
    }

}