<?php

function list_users()
{
    return list_all_data('users',1,true);
}

function create_user()
{
    // @todo: validate
    $requestData = request();
    $data = [];
    $data['first_name']= $requestData['first_name'];
    $data['last_name']= $requestData['last_name'];
    $data['email']= $requestData['email'];
    $data['mobile']= $requestData['mobile'];
    $data['permissions']=[];
    $data['password'] = '$2y$10$2Hc8NLiimtNdcmijOS2.zOJkYS7Dkzbmi6op9oLkQXsJFpMY0lVUS' ;
    return create('users',$data);
}

function my_info()
{
    $headers  =getallheaders();
       if ( array_key_exists('Auth-Id', $headers)){
           if ($headers['Auth-Id'] !=null) {
               $auth_id = $headers['Auth-Id'];
               $condition = "id = {$auth_id}";
               return list_all_data('users', $condition);
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
            $condition = "id = {$auth_id}";
            $columns = 'is_super_admin';
            $result = list_data('users',$columns, $condition,true);
            if (isset($user['is_super_admin']) && $result['is_super_admin'] == 1){
                $permissions = list_all_data('permissions',1,true);
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
            $condition = "id = {$user_id}";
            $columns = 'is_super_admin';
            $result = list_any('users',$columns,$condition,true);
            if (isset($result['is_super_admin']) && $result['is_super_admin'] == 1){
                $requestData = request();
                $data=[];
                $data['permissions'] =$requestData['permissions'];
//                var_dump($data);
//                exit();
//                $data =json_encode( $_POST['permissions']);
               return update('users',$data,$condition);
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

