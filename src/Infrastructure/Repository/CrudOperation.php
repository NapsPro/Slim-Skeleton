<?php

namespace App\Infrastructure\Repository;

interface  CrudOperation{

    public  function getByID($id);

    public  function getAll(array $params);

    public  function createElement(array $params);

    public  function editElement($id, $params);

    public  function deleteElement($id, $params);
}
