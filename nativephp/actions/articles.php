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
    if (auth_user() && article_id()) {
        $auth_id = auth_user();
        $article_id = article_id();
        $with = "id = {$article_id}";
        $sql = list_all_data('articles', $with,true);
        if ($auth_id == 1 || $auth_id == $sql['data'][0]['user_id']) {
            $requestData = request();
            $data = [];
            $data['title'] = $requestData['content'];
            $data['content'] = $requestData['content'];
            if (file_exists($_FILES["img"]["tmp_name"])) {
                $img_name = rand(0, 999) . basename($_FILES["img"]["name"]);
                $data['img'] = $img_name;
                $targetUrl = '../storage/app/public/' . $img_name;
                move_uploaded_file($_FILES["img"]["tmp_name"], $targetUrl);
            }
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
    $user = auth_user();
    if ($user) {
        $with = "id = {$user}";
        $us = null;
        $sql = list_all_data('users', $with, true);
        if ($sql['data'] != []) {
            $requestData = request();
            $data = [];
            $data['user_id'] = $requestData['user_id'];
            $data['title'] = $requestData['title'];
            $data['content'] = $requestData['content'];
            if (file_exists($_FILES["img"]["tmp_name"])) {
                $img_name = rand(0, 999) . basename($_FILES["img"]["name"]);
                $data['img'] = $img_name;
//                var_dump(getcwd());
//                exit();
//                $targetUrl = __DIR__ . "/../../storage/app/public/".$img_name ;
                $targetUrl = "../storage/app/public/" . $img_name;
                move_uploaded_file($_FILES["img"]["tmp_name"], $targetUrl);
            }
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
    $user = auth_user();
    $article_id = article_id();
    if ($user && $article_id) {
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
    $user = auth_user();
    if ($user) {
        $with = "user_id = {$user}";
        return list_all_data('articles', $with);
    } else {
        return [
            'message' => 'not authorized'
        ];
    }

}

function article_id()
{
    $headers = getallheaders();
    if (array_key_exists('Article-Id', $headers) && $headers['Article-Id'] != null) {
        $article_id = $headers['Article-Id'];
        return $article_id;
    }
}

