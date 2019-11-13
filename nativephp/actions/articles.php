<?php
/**
 * Created by PhpStorm.
 * User: mhmd
 * Date: 11/13/2019
 * Time: 12:54 PM
 */
function list_articles()
{
    $sql = "SELECT * from articles";
    return [
        'articles'=> conn()->query($sql)->fetch_all(MYSQLI_ASSOC),
    ];

}
function edit_article()
{
    $headers = getallheaders();
    if (array_key_exists('Auth-Id',$headers)&& array_key_exists('Article-Id',$headers)){
        $auth_id = $headers['Auth-Id'];
        $article_id = $headers['Article-Id'];
        $sql ="SELECT * from articles where id =$article_id";
        $sql = conn()->query($sql) ;
        $sql = mysqli_fetch_assoc($sql);
//        var_dump($sql['id'] , $sql['user_id']);
//        exit();
        if ($auth_id == 1 || $auth_id ==$sql['user_id']){
            $requestData = request();
            $sql = "UPDATE articles set title='{$requestData['title']}' ,
            content='{$requestData['content']}'  WHERE id={$article_id} ";
            if (conn()->query($sql) === TRUE){
                return [
                    'message'=>'article updated succefully',
                ];
            }
            else {
                echo "Error: " . $sql . "<br>" . conn()->error;
                exit();
            }
        }
    }
    else {
        return [
            'message'=>'this user has not permission for edit'
        ];
    }
}

function post_article()
{
    $headers = getallheaders();
    if (array_key_exists('Auth-Id',$headers) && $headers['Auth-Id'] != null){
        $user = $headers['Auth-Id'];
        $sql = "SELECT * from users where id= {$user}";
        $sql = conn()->query($sql)->fetch_all(MYSQLI_ASSOC);
        if(!empty($sql)){
            $requestData = request();
            $sql = "INSERT INTO articles (user_id ,title ,content) values (
            '{$requestData['user_id']}',
            '{$requestData['title']}',
            '{$requestData['content']}'
            )";
        if (conn()->query($sql) === TRUE){
            return [
                'article_id' => conn()->insert_id
            ];
        }
        else {
            echo "Error: " . $sql . "<br>" . conn()->error;
            exit();
        }
        }
    }
    else {
        return [
            'message'=>'not authorized'
        ];
    }

}


function git_article()
{
    $hearders = getallheaders();
    if(array_key_exists('Auth-Id',$hearders) && array_key_exists('Article-Id',$hearders)
    && $hearders['Article-Id'] !=null){
        $article_id = $hearders['Article-Id'];
        $sql = "SELECT * from articles WHERE id = {$article_id}";
        return [
            'article'=> conn()->query($sql)->fetch_all(MYSQLI_ASSOC),
        ];
    }
    else{
        return [
            'message'=>'please enter the article id at header ya mo8afal',
        ];
    }
}
function git_my_articles()
{
    $hearders = getallheaders();
    if(array_key_exists('Auth-Id',$hearders)&& $hearders['Auth-Id'] !=null){
        $user = $hearders['Auth-Id'];
        $sql = "SELECT * from articles WHERE user_id = '{$user}'";
        return [
            'my_articles'=> conn()->query($sql)->fetch_all(MYSQLI_ASSOC) ,
        ];
    }
    else{
        return [
            'message'=>'not authorized'
        ];
    }
}
