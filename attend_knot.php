<?php

require_once('require.php');
setupEncodeing();
session_start();
connectDb();

$me = get_me();

$login  = checkLogin($me, true);

$id = $_POST['id'];
if(empty($id))
    jump(PAGE_ERROR, array('id' => $id, 'err' => E1CKNOT.E2ATEND."02"));
$knot = getKnot($id);
if($knot === false)
    jump(PAGE_ERROR, array('id' => $id, 'err' => E1CKNOT.E2ATEND."04"));

if($me->isAttendKnot($knot->id))
    jump('class', array('id' => $id, 'err' => E1CKNOT.E2ATEND."01"));


if($result = attendKnot($me, $knot, 'a'))
    jump('class', array('id' => $knot->id, 'mes' => E1CLASS.E2ATEND));
else
    jump('class', array('id' => $knot->id, 'err' => E1CLASS.E2ATEND.'08'));

