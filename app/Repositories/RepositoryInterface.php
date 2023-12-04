<?php

namespace App\Repositories;

interface RepositoryInterface
{

    public function all($columns = [ '*' ]);


    public function find($id, $columns = [ '*' ]);


    public function findBy($field, $value, $columns = [ '*' ]);


    public function findOrFail($id);


    public function create($attributes);


    public function update($id, $attributes);


    public function delete($id);


    public function paginate($perPage = 15, $columns = [ '*' ]);
}