<?php

$link = new PDO('mysql:host=localhost;dbname=comment_db', 'root', '');

function get_comments()
{
    global $link;
    $query = "SELECT * FROM `comments` WHERE `parent` = 0";
    //    $result = mysqli_fetch_all($query);
    $statement = $link->prepare($query);

    $statement->execute();
    $output = '';

    $result = $statement->fetchAll();
    foreach ($result as $row) {
        echo  $row['username'] . "<br>";
        echo $row['text'] . "<br><br>";
        // echo $row['id'] ;
        $output .= get_reply_comment($link, $row["id"]);
        echo $output;
        $output = '';
    }

    //   print_r($result);
}
get_comments();

function get_reply_comment($connect, $parent_id, $marginleft = 0)
{
    //echo $parent_id;
    $query = "SELECT * FROM comments WHERE parent = '" . $parent_id . "'";
    $output = '';
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $count = $statement->rowCount();

    if ($parent_id == 0) {
        $marginleft = 0;
    } else {
        $marginleft = $marginleft + 48;
    }
    if ($count > 0) {
        foreach ($result as $row) {
            $output .= '<div class="display_reply" style="margin-left:' . $marginleft . 'px">
                            ' . $row['username'] . '<br>
                            ' . $row['text'] . '<br><br>
                        </div>';

            $output .= get_reply_comment($connect, $row["id"], $marginleft);
        }
    }
    return $output;
}
