<?php


function create_user()
{
    // @todo: validate

    $sql = "INSERT INTO users (first_name, last_name, email, mobile, permissions, password)
        VALUES (
        '{$_POST['first_name']}',
         '{$_POST['last_name']}' ,
          '{$_POST['email']}' ,
          '{$_POST['mobile']}' ,
          '[]',
          '$2y$10$2Hc8NLiimtNdcmijOS2.zOJkYS7Dkzbmi6op9oLkQXsJFpMY0lVUS'
          )";
    if ($GLOBALS['conn']->query($sql) === TRUE) {
        return [
            'user_id' => $GLOBALS['conn']->insert_id
        ];
    } else {
        echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
        exit();
    }

    $GLOBALS['conn']->close();
}
