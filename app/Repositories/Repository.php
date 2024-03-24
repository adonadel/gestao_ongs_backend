<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    abstract protected function getModelClass();

    public function newQuery()
    {
        return app()->make($this->model)->query();
    }

    public function getById($id)
    {
        return $this->newQuery()->find($id);
    }

    public function doQuery($query = null, int $take = 20, bool $paginate = true)
    {
        if (! $query) {
            $query = $this->newQuery();
        }

        if ($paginate) {
            return $query->paginate($take);
        }

        if ($take) {
            return $query->take($take)->get();
        }

        return $query->get();
    }

    public function getAll(int $take = 20, bool $paginate = false)
    {
        return $this->doQuery(null, $take, $paginate);
    }

    public function factory(array $data = []): Model
    {
        $model = $this->newQuery()->getModel()->newInstance();

        $this->fillModel($model, $data);

        return $model;
    }

    public function create(array $data)
    {
        $model = $this->factory($data);

        return $this->save($model);
    }

    public function update(Model $model, array $data)
    {
        $this->fillModel($model, $data);

        return $this->save($model);
    }

    public function delete(Model $model)
    {
        return $model->delete();
    }

    public function fillModel(Model $model, array $data = []): void
    {
        $model->fill($data);
    }

    public function save(Model $model): Model
    {
        $model->save();

        return $model;
    }
}
