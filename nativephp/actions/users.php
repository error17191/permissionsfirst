<?php

function list_users()
{
    $sql = "SELECT * from users";

    return [
        'list-users' => conn()->query($sql)->fetch_all(MYSQLI_ASSOC)
    ];

}

function create_user()
{
    // @todo: validate
    $requestData = request();
    $sql = "INSERT INTO users (first_name, last_name, email, mobile, permissions, password)
        VALUES (
        '{$requestData['first_name']}',
         '{$requestData['last_name']}' ,
          '{$requestData['email']}' ,
          '{$requestData['mobile']}' ,
          '[]',
          '$2y$10$2Hc8NLiimtNdcmijOS2.zOJkYS7Dkzbmi6op9oLkQXsJFpMY0lVUS'
          )";
    if (conn()->query($sql) === TRUE) {
        return [

            'user_id' => conn()->insert_id
        ];
    } else {
        echo "Error: " . $sql . "<br>" . conn()->error;
        exit();
    }

    conn()->close();
}

function my_info()
{
    $headers  =getallheaders();
       if ( array_key_exists('Auth-Id', $headers)){
           if ($headers['Auth-Id'] !=null ){
               $auth_id = $headers['Auth-Id'] ;
               $sql = "SELECT * from users where id='$auth_id'";
//               var_dump(conn()->query($sql)->fetch_all(MYSQLI_ASSOC));
//               exit();
               return [
                   'auth-user'=>conn()->query($sql)->fetch_all(MYSQLI_ASSOC),
               ];
           }
           else{
               return [
                   'data'=>'not user or admin'
               ];
           }
       }
       else{

        echo 'not authorized';
           conn()->close();
           exit();
       }

}
function list_permissions()
{
    $headers = getallheaders();
    if(array_key_exists('Auth-Id',$headers)){
        if ($headers['Auth-Id'] !=null){
            $auth_id = $headers['Auth-Id'] ;
            $sql = "SELECT * from users where id='$auth_id'";
            $user =conn()->query($sql);
            $user = mysqli_fetch_assoc($user);
            if (isset($user['is_super_admin']) && $user['is_super_admin'] == 1){
                $sql = "SELECT * from permissions";
                $permissions =conn()->query($sql)->fetch_all(MYSQLI_ASSOC);
                return [
                'permissions'=>$permissions,
            ];
            }
            else{
                return ['message'=>'Not Admin'];
            }
        }
        else{
            return [
                'message'=>'Not User'
            ];
        }
    }
    else{
        return [
            'data' =>'not authorized'
        ];
    }
}
function edit_permissions()
{
    $headers = getallheaders();
    if(array_key_exists('Auth-Id',$headers)){
        if ($headers['Auth-Id'] !=null && array_key_exists('User-Id',$headers)
            && $headers['User-Id'] !=null){
            $auth_id = $headers['Auth-Id'];
            $user_id = $headers['User-Id'];
            $sql = "SELECT * from users where id='$auth_id'";
            $user =conn()->query($sql);
            $user = mysqli_fetch_assoc($user);
            if (isset($user['is_super_admin']) && $user['is_super_admin'] == 1){
//                $requestData = request();
//                $data =$requestData['permissions'];
//                var_dump($data);
//                exit();
                $data =json_encode( $_POST['permissions']);
                $sql = "UPDATE  users set permissions = $data WHERE id = $user_id";
                if (conn()->query($sql) === TRUE) {
                    return [
                        'message' => 'done suucefully'
                    ];
                } else {
                    echo "Error: " . $sql . "<br>" . conn()->error;
                    exit();
                }
            }
            else{
                return ['message'=>'Not Admin & only admin can edit permissions'];
            }
        }
        else{

            return [
                'message'=>'Not User'
            ];
        }
    }
    else{
        return [
            'data' =>'not authorized'
        ];
    }

}
