<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Modern Business - Start Bootstrap Template</title>

<?php

global $wpdb;
//$wpdb->prefix 
$count = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'users');

foreach($count as $b1){

        //$view1 .= "$b1->ID";

    }

    $user_id = get_current_user_id();
if ($user_id == 0) {
    echo 'You are currently not logged in.';
} else {
    echo 'You are logged in as user '.$user_id;
}
       

echo $view1;
/*
     $sql = "SELECT p.id, p.post_title, p.guid, p.post_type, m.meta_key, m.meta_value
            FROM wp_posts p
            INNER JOIN wp_postmeta m
            WHERE p.id=m.post_id
            AND m.meta_key='_rentable' AND m.meta_value='yes'
            ";*/

            ?>

    <!-- Custom Fonts 
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">-->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

   


   

</body>

</html>
