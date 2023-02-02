<?php

namespace App\Domain;

interface  CrudOp{

    public  function getByID(array $params);

    public  function getAll(array $params, array $queryParam = []);

    public  function create_element(array $params);

    public  function edit_element(array $params);

    public  function delete_element(array $params);
}