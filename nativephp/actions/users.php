<?php

function list_users()
{
    $sql = "SELECT * from users";

    return [
        'data' => conn()->query($sql)->fetch_all(MYSQLI_ASSOC)
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
