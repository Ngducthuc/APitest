<?php
session_start();
if(isset($_SESSION['admin'])){
    include "class/cartegory_class.php";
$cartegory = new cartegory();
if(!isset($_GET["cartegory_id"])  || $_GET["cartegory_id"]==NULL){
    echo "<script>window.location = 'cartegory_list.php'</script>";
}
else{
    $cartegory_id = $_GET['cartegory_id'];
}
$delete_cartegory = $cartegory->delete_cartegory($cartegory_id);
}else{echo "Error: 404!";}
?>