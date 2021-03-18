<?php

use eftec\MessageContainer;

include '../vendor/autoload.php';


$container=new MessageContainer();
$container->addItem('id1','some msg 1','error');
$container->addItem('id1','some msg 2','error');
$container->addItem('id1','some msg 1','warning');
$container->addItem('id1','some msg 2','warning');

$container->addItem('id2','some msg 1','info');
$container->addItem('id2','some msg 2','info');
$container->addItem('id2','some msg 1','success');
$container->addItem('id2','some msg 2','success');

$container->addItem('id33','some msg 1','error');
$container->addItem('id33','some msg 2','error');
$container->addItem('id33','some msg 1','success');
$container->addItem('id33','some msg 2','success');

// reading by locker
$msg=$container->getLocker('id1')->firstErrorOrWarning(); // returns if the locker id1 has an error or warning
$msg2=$container->getLocker('id2')->allInfo(); // returns all info store in locker id2 ["some msg1","some msg2"]
$msg3=$container->getLocker('id3')->allInfo(); // (note this locker is not defined so it returns an empty array.
$msg4=$container->getLocker('id33')->hasError(); // returns true if there is an error.
$msg5=$container->getLocker('id33')->countError(); // returns the number of errors (or zero if none).
// reading by container
$msg7=$container->hasError(); // returns true if there is an error in any locker.
$msg8=$container->allErrorArray(true); // returns all errors and warnings presents in any locker.



