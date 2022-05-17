<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

abstract class ModelExt extends Model
{
    protected $_errors = [];
    
    public function refreshCustomModel(Model $model)
    {
        $nameModel = get_class($model);
        $newModel = new $nameModel;
        
        return $newModel->find($model->id);
    }
    
    public function setAttribute($key, $value)
    {
        $mutate = $this->getMutators();

        if (isset($mutate[$key])) {
            if (is_callable($mutate[$key])) {
                $value = $mutate[$key]($value);
            }
        }
        
        return parent::setAttribute($key, $value);
    }

    public static function getStaticTable(): string
    {
        return (new static)->getTable();
    }

    public function cloneModel()
    {
        return clone $this;
    }
    
    public function getAllAttributes()
    {
        $columns = $this->getFillable();
        // Another option is to get all columns for the table like so:
        // $columns = \Schema::getColumnListing($this->table);
        // but it's safer to just get the fillable fields

        $attributes = $this->getAttributes();

        foreach ($columns as $column) {
            if (!array_key_exists($column, $attributes)) {
                $attributes[$column] = null;
            }
        }
        return $attributes;
    }
    
    /**
     * fillMissingAttributes from $fillable
     * @param array $data
     * @param type $default
     * @return array
     */
    public function fillMissingAttributes(array $data, $default = null): array
    {
        $model = new static();
        $attributes = $model->attributes?? [];
        
        if (empty($this->fillable)) {
            return $data;
        }

        foreach ($this->fillable as $field) {
            if ( !isset($data[$field])) {
                if (isset($attributes[$field])) {
                    $data[$field] = $attributes[$field];
                } else {
                    $data[$field] = $default;
                }
            }
        }

        return $data;
    }

    /**
     * create model or throw new exception
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createOrException(array $datas)
    {
        $this->fill($datas);

        if ($this->isInvalid()) {
            return $this->throwValidationException();
        }

        return $this->create($datas);
    }

    /**
     * Get the first record matching the attributes or create it or fail if data not validate.
     * @param array $attributes
     * @param array $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreateOrFail(array $attributes, array $values = [])
    {
        if ( ! is_null($instance = $this->where($attributes)->first())) {
            return $instance;
        }

        return tap(
            $this->newModelInstance($attributes + $values),
            function ($instance) {
                if ($instance->isInvalid()) {
                    $instance->throwValidationException();
                }

                $instance->save();
            }
        );
    }
    
    public static function findOrNew($id)
    {
        $obj = static::find($id);

        return $obj ? $obj : new static;
    }
    
    protected function getMutators()
    {
        return [];
    }
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    
    public function getFieldsSql($prefTable = false, $delimiterPrefix = '__')
    {
        $fileds = \Schema::getColumnListing($this->table);

        $result = '';
        foreach ($fileds as $filed) {
            $name = '';

            if ($prefTable) {
                $name = ' as '.$this->table.$delimiterPrefix.$filed.' ';
            }

            $result .= $this->table.'.`'.$filed.'`'.$name.', ';
        }

        return trim($result, ', ');
    }
    
    public static function mutateAsAttr($attr, $value)
    {
        $model = new static();
        $model->{$attr} = $value;

        return $model->{$attr};
    }
    
    public function getFilteredData(array $datasModel)
    {
        $result = [];
        $model = $this->newInstance();
        foreach ($datasModel as $key => $val) {
            $model->{$key} = $val;
        }

        return $model->toArray();
    }

}
