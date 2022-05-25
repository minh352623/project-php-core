<?php
if (!defined('_INCODE')) die('access define...');
function query($sql, $data = [], $statementStatus = false)
{
    global $conn;
    $query = false;
    try {
        $statement = $conn->prepare($sql);
        // var_dump($statement);
        if (empty($data)) {

            $query = $statement->execute();
        } else {
            $query = $statement->execute($data);
        }
    } catch (Exception $e) {

        require_once 'modules/error/database.php'; //import error
        die(); //dừng truy vấn cơ sở  dữ liệu
    }

    if ($statementStatus && $query) {
        return $statement;
    }
    return $query;
}
function insert($table, $dataInsert)
{

    $keyArr = array_keys($dataInsert);
    $fieldStr = implode(', ', $keyArr);
    $valueStr = ':' . implode(', :', $keyArr);

    $sql = 'INSERT INTO `' . $table . '`(' . $fieldStr . ') VALUES(' . $valueStr . ')';

    return query($sql, $dataInsert);
}
function update($table, $dataUpdate, $condition)
{
    // echo '<pre>';
    // print_r($dataUpdate); 
    // echo '</pre>';
    $updateStr = '';
    foreach ($dataUpdate as $key => $value) {
        $updateStr .= $key . '=:' . $key . ', ';
    }
    $updateStr = rtrim($updateStr, ', ');
    // echo $updateStr;
    if (!empty($condition)) {

        $sql = 'UPDATE `' . $table . '` SET ' . $updateStr . ' where ' . $condition;
    } else {
        $sql = 'UPDATE `' . $table . '` SET ' . $updateStr;
    }
    // echo $sql;
    return query($sql, $dataUpdate);
}

function delete($table, $condition)
{
    if (!empty($condition)) {
        $sql = "DELETE FROM `$table`  WHERE $condition";
    } else {
        $sql = "DELETE FROM `$table`";
    }
    return query($sql);
}

//lấy dữ liệ từ câu lện sql: lấy tất cả
function getRaw($sql)
{
    $statement = query($sql, [], true);
    // var_dump($statement);
    if (is_object($statement)) {
        $dataFetch = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $dataFetch;
    }
    return false;
}
//lấy 1 bản ghi
function firstRaw($sql)
{
    $statement = query($sql, [], true);
    // var_dump($statement);
    if (is_object($statement)) {
        $dataFetch = $statement->fetch(PDO::FETCH_ASSOC);
        return $dataFetch;
    }
    return false;
}

//lấy dữ liệu theo table,field, condition
function get($table, $field = '*', $condition = '')
{
    $sql = "SELECT $field FROM $table";
    // echo $sql;
    if (!empty($condition)) {
        $sql .= ' WHERE ' . $condition;
    }
    return getRaw($sql);
}
function first($table, $field = '*', $condition = '')
{
    $sql = "SELECT $field FROM $table";
    // echo $sql;
    if (!empty($condition)) {
        $sql .= ' WHERE ' . $condition;
    }
    return firstRaw($sql);
}

function getRow($sql)
{
    $statement = query($sql, [], true);
    if (!empty($statement)) {
        return $statement->rowCount();
    }
}
//lấy id vừa insert
function insertId()
{
    global $conn;
    return $conn->lastInsertId();
}
