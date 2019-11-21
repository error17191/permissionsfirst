<?php
/**
 * Created by PhpStorm.
 * User: mhmd
 * Date: 11/21/2019
 * Time: 12:41 PM
 */


function list_data($table_name, $columns, $condition = 1, $returnMySQLResult = false)
{
    return list_any($table_name, implode(',', $columns), $condition);
}

function list_all_data($table_name, $condition = 1, $returnMySQLResult = false)
{
    return list_any($table_name, '*', $condition);
}

function list_any($table_name, $columnsString, $condition = 1, $returnMySQLResult = false)
{
    $sql = "SELECT $columnsString from $table_name WHERE {$condition}";
    $mySQLResult = conn()->query($sql);
    return [
        'data' => $returnMySQLResult ? $mySQLResult : $mySQLResult->fetch_all(MYSQLI_ASSOC),
    ];
}

function item($table_name, $columns, $condition)
{
    return list_data($table_name, $columns, $condition, true)->fetch_assoc();
}

function item_all($table_name, $condition)
{
    return list_all_data($table_name, $condition, true)->fetch_assoc();
}

function create($table_name, $data)
{
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $data[$key] = "'{$value}'";
        }
    }
    $columns = implode(",", array_keys($data));
    $values = implode(",", array_values($data));
    $sql = "INSERT INTO $table_name ($columns) values ($values)";
    if (conn()->query($sql) === TRUE) {
        return [
            'id' => conn()->insert_id
        ];
    } else {
        echo "Error: " . $sql . "<br>" . conn()->error;
        exit();
    }
}

function update($table_name, $data, $condition)
{
    foreach ($data as $key=>$value){
        if(is_string($value)){
            $data[$value] = "'{$value}'";
        }
    }
    $sql = "UPDATE {$table_name} SET";
    foreach ($data as $key => $value) {
        $sql .= "  {$key} = {$value} , ";
    }
    $sql = substr($sql, 0, strlen($sql) - 2);

    $sql .= " WHERE   {$condition} ";
    if (conn()->query($sql) === TRUE) {
        return [
            'message' => 'updated successfully',
        ];
    } else {
        echo "Error: " . $sql . "<br>" . conn()->error;
        exit();
    }

}
