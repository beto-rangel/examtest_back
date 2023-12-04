<?php

namespace App\Repositories\Eloquent;

use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
abstract class EloquentRepository implements RepositoryInterface
{

    /**
     * @return Model
     */
    abstract public function getModel();


    /**
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($columns = ['*'])
    {
        return $this->getModel()->all($columns);
    }


    /**
     * @param       $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find($id, $columns = ['*'])
    {
        return $this->getModel()->query()->find($id, $columns);
    }


    /**
     * @param       $field
     * @param       $value
     * @param array $columns
     *
     * @return Model|null|static
     */
    public function findBy($field, $value, $columns = ['*'])
    {
        return $this->getModel()->query()->where($field, $value)->first($columns);
    }


    /**
     * @param $attributes
     *
     * @return static
     */
    public function create($attributes)
    {

        return $this->getModel()->forceCreate($attributes);
    }


    /**
     * @param $id
     * @param $attributes
     *
     * @return bool
     * @throws ModelNotFoundException;
     */

   public function update($attributes, $id)
    {
        $model = $this->findOrFail($id);
        $model->forceFill($attributes);

        return $model->save();
    }


    /**
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model
     * @throws ModelNotFoundException
     */
    public function findOrFail($id)
    {
        return $this->getModel()->query()->findOrFail($id);
    }


    /**
     * @param $id
     *
     * @return bool|null
     * @throws ModelNotFoundException
     */
    public function delete($id)
    {
        $model = $this->findOrFail($id);

        return $model->delete();
    }


    /**
     * @param int $perPage
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 15, $columns = ['*'])
    {
        return $this->getModel()->query()->paginate($perPage, $columns);
    }


    /**
     * @param array $data
     *
     * @return bool
     */
    public function bulkInsert(array $data)
    {
        return $this->getModel()->onWriteConnection()->insert($data);
    }


}