<?php

namespace App;

use Aura\SqlQuery\QueryFactory;

use PDO;

class QueryBuilder
{
    private $pdo;
    private $queryFactory;

    public function __construct(PDO $pdo)
    {
        // a PDO connection
        $this->pdo = $pdo;
        $this->queryFactory = new QueryFactory('mysql');
    }

    public function getAll($table)
    {
        $select = $this->queryFactory->newSelect();

        $select->cols(['*'])
            ->from($table);

// prepare the statement
        $sth = $this->pdo->prepare($select->getStatement());

// bind the values and execute
        $sth->execute($select->getBindValues());

// get the results back as an associative array
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

   public function  getAllTablesInfo($table1, $table2) {
       $select = $this->queryFactory->newSelect();

       $select->cols(['*'])
           ->from($table1);

       $select->join(
           'INNER',             // the join-type
           $table2,        // join to this table ...
           "{$table1}.id = {$table2}.user_id" // ... ON these conditions
       );

// prepare the statement
       $sth = $this->pdo->prepare($select->getStatement());

// bind the values and execute
       $sth->execute($select->getBindValues());

// get the results back as an associative array
       $result = $sth->fetchAll(PDO::FETCH_ASSOC);

       return $result;
   }

    public function getOne($table, $id)
    {
        $select = $this->queryFactory->newSelect();

        $select->cols(['*'])
            ->from($table)->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($select->getStatement());

        $sth->execute($select->getBindValues());

        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getOneByUserId($table, $user_id)
    {
        $select = $this->queryFactory->newSelect();

        $select->cols(['*'])
            ->from($table)->where('user_id = :user_id')
            ->bindValue('user_id', $user_id);

        $sth = $this->pdo->prepare($select->getStatement());

        $sth->execute($select->getBindValues());

        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function insert($data, $table)
    {
        $insert = $this->queryFactory->newInsert();

        $insert
            ->into($table)                   // INTO this table
            ->cols($data);

        $sth = $this->pdo->prepare($insert->getStatement());

//        var_dump($_POST);die();

        $sth->execute($insert->getBindValues());

//        d($sth->execute($insert->getBindValues()));die();
    }

    public function update($data, $id, $table)
    {
        $update = $this->queryFactory->newUpdate();

        $update
            ->table($table)                  // update this table
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($update->getStatement());

        $sth->execute($update->getBindValues());
    }

    public function updateByUserId($data, $user_id, $table)
    {
        $update = $this->queryFactory->newUpdate();

        $update
            ->table($table)                  // update this table
            ->cols($data)
            ->where('user_id = :user_id')
            ->bindValue('user_id', $user_id);

        $sth = $this->pdo->prepare($update->getStatement());

        $sth->execute($update->getBindValues());
    }

    public function delete($table, $id)
    {
        $delete = $this->queryFactory->newDelete();

        $delete
            ->from($table)                   // FROM this table
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($delete->getStatement());

        $sth->execute($delete->getBindValues());
    }

    public function deleteByUserId($table, $user_id)
    {
        $delete = $this->queryFactory->newDelete();

        $delete
            ->from($table)                   // FROM this table
            ->where('user_id = :user_id')
            ->bindValue('user_id', $user_id);

        $sth = $this->pdo->prepare($delete->getStatement());

        $sth->execute($delete->getBindValues());
    }

    //Получить уникальный id загружаемого файла:
    public function get_uniqid($file) {
        $pathinfo = pathinfo($file);
        $ext = $pathinfo['extension'];
        $file = uniqid() . '.' . $ext;
        return $file;
    }

    public function upload_avatar($avatar) {
        //    Получаем уникальное название для картинки:
        $uniqid = $this->get_uniqid($avatar);

        $to = $_SERVER['DOCUMENT_ROOT'] . '/img/uploaded/' . $uniqid;

        move_uploaded_file($_FILES['avatar']['tmp_name'], $to);

        return $uniqid;
    }


}