<?php

require_once('require.php');
setupEncodeing();
session_start();
connectDb();

$me = get_me();

$login  = checkLogin($me, true);

$id = $_POST['id'];
if(empty($id))
    jump(PAGE_ERROR, array('id' => $id, 'err' => E1CLASS.E2ATEND.'03'));
$class = getClass($id);
if(!$class)
    jump(PAGE_ERROR, array('id' => $id, 'err' => E1CLASS.E2ATEND.'04'));

if(checkAttend($me, $class, $matched)) {
    if($result = attendClass($me, $class)) {
        jump('class', array('id' => $id, 'mes' => E1CLASS.E2ATEND));
    } else {
        jump('class', array('id' => $id, 'err' => E1CLASS.E2ATEND.'08'));
    }
} else {
    jump('class', array('id' => $id, 'err' => E1CLASS.E2ATEND.'01'));
}
