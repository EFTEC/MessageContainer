<?php /** @noinspection ForgottenDebugOutputInspection */

use eftec\MessageContainer;

include '../vendor/autoload.php';


$container=new MessageContainer();
$container->addItem('id1','some msg 1');
$container->addItem('id1','some msg 2');
$container->addItem('id1','some msg 1','warning');
$container->addItem('id1','some msg 2','warning');

$container->addItem('id2','some msg 1','info');
$container->addItem('id2','some msg 2','info');
$container->addItem('id2','some msg 1','success');
$container->addItem('id2','some msg 2','success');

$container->addItem('id33','some msg 1');
$container->addItem('id33','some msg 2');
$container->addItem('id33','some msg 1','success');
$container->addItem('id33','some msg 2','success');

// reading by locker
$msg=$container->getLocker('id1')->firstErrorOrWarning(); // returns if the locker id1 has an error or warning
$msg2=$container->getLocker('id2')->allInfo(); // returns all info store in locker id2 ["some msg1","some msg2"]
$msg3=$container->getLocker('id3')->allInfo(); // (note this locker is not defined, so it returns an empty array.
$msg4=$container->getLocker('id33')->hasError(); // returns true if there is an error.
$msg5=$container->getLocker('id33')->countError(); // returns the number of errors (or zero if none).
// reading by container
$msg7=$container->hasError(); // returns true if there is an error in any locker.
$msg8=$container->allErrorArray(true); // returns all errors and warnings presents in any locker.
$msg9=$container->allAssocArray(); // returns all errors and warnings presents in any locker (associative array)
echo "<h1>First error or warning in locker id1</h1><br>";
var_dump($msg);
echo "<h1>All info in locker id2</h1><br>";
var_dump($msg2);
echo "<h1>All info in locker id3</h1><br>";
var_dump($msg3);
echo "<h1>if there is an error in locker id33</h1><br>";
var_dump($msg4);
echo "<h1>number of errors in locker id33</h1><br>";
var_dump($msg5);
echo "<h1>if there is an error in any locker</h1><br>";
var_dump($msg7);
echo "<h1>all errors/warnings in all lockers</h1><br>";
var_dump($msg8);
echo "<h1>all errors/warnings in all lockers</h1><br>";
var_dump($msg9);
