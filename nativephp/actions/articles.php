<?php
/**
 * Created by PhpStorm.
 * User: mhmd
 * Date: 11/13/2019
 * Time: 12:54 PM
 */
function list_articles()
{
    return list_all_data('articles');
}

function edit_article()
{
    $headers = getallheaders();
    if (array_key_exists('Auth-Id', $headers) && array_key_exists('Article-Id', $headers)) {
        $auth_id = $headers['Auth-Id'];
        $article_id = $headers['Article-Id'];
        $with = "id = {$article_id}";
        $sql = list_all_data('articles', $with);
        if ($auth_id == 1 || $auth_id == $sql['data']['user_id']) {
            $requestData = request();
            $data = [];
            $data['title'] = "{$requestData['content']}";
            $data['content'] = "{$requestData['content']}";
            $condtion = "id = {$article_id}";
            return update('articles', $data, $condtion);
        }
    } else {
        return [
            'message' => 'this user has not permission for edit'
        ];
    }
}

function post_article()
{
    $headers = getallheaders();
    if (array_key_exists('Auth-Id', $headers) && $headers['Auth-Id'] != null) {
        $user = $headers['Auth-Id'];
        $with = "id = {$user}";
        $sql = list_all_data('users', $with);
        if ($sql['data'] != []) {
            $requestData = request();
            $data = [];
            $data['user_id'] = $requestData['user_id'];
            $data['title'] = $requestData['title'];
            $data['content'] = $requestData['content'];
            return create('articles', $data);
        }
    } else {
        return [
            'message' => 'not authorized'
        ];
    }

}


function git_article()
{
    $hearders = getallheaders();
    if (array_key_exists('Auth-Id', $hearders) && array_key_exists('Article-Id', $hearders)
        && $hearders['Article-Id'] != null) {
        $article_id = $hearders['Article-Id'];
        $with = "id = {$article_id}";
        return list_all_data('articles', $with);
    } else {
        return [
            'message' => 'please enter the article id at header ya mo8afal',
        ];
    }
}

function git_my_articles()
{
    $hearders = getallheaders();
    if (array_key_exists('Auth-Id', $hearders) && $hearders['Auth-Id'] != null) {
        $user = $hearders['Auth-Id'];
        $with = "user_id = {$user}";
        return list_all_data('articles', $with);
    } else {
        return [
            'message' => 'not authorized'
        ];
    }

}

function create($table_name, $data)
{
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
    return list_data($table_name,$columns,$condition,true)->fetch_assoc();
}

function item_all($table_name, $condition)
{
    return list_all_data($table_name,$condition,true)->fetch_assoc();
}


function update($table_name, $data, $condition)
{
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

