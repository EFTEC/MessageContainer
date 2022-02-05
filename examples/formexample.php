<?php

use eftec\MessageContainer;

include '../vendor/autoload.php';


$container=MessageContainer::instance();

$id=$_GET['id']??'';
$text=$_GET['text']??'';

if(!$id) {
    $container->addItem('id',"id is required");
}
if(!is_numeric($id)) {
    $container->addItem('id',"id is not a numeric value");
}
if(!$text) {
    $container->addItem('text',"text is required");
}
if(strlen($text)>20) {
    $container->addItem('text',"text must contains until 20 characters");
}
?>
<style>
    label {display: inline-block; width: 300px}
    .red {color:red}
</style>
<form method='get'>
    <label for="id">ID:</label><input type="text" id="id" name="id" value="<?=$id?>"/><br>
    <?php if($container->get('id')->hasError()) { ?>
    <label>Locker id Error:</label><span class="red"><?=$container->get('id')->firstError() ?></span><br>
    <?php } ?>
    <label for="text">TEXT:</label><input type="text" id="text" name="text" value="<?=$text?>"/><br>
    <?php if($container->get('text')->hasError()) { ?>
    <label>Locker text Error:</label><span class="red"><?=$container->get('text')->firstError() ?></span><br>
    <?php } ?>
    <label>&nbsp;</label><button type="submit">Send form</button><br>
    <hr>
    <h2>All locker errors:</h2>
    <ul>
    <?php
    foreach($container->allErrorArray() as $error) {
        echo "<li class='red'>$error</li>";
    }
    ?>
    </ul>
</form>
