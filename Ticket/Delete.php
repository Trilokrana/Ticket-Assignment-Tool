<?php include __DIR__ . '/../config/config.php';
$id=$_GET['id'];
$delete="DELETE FROM createticket WHERE id='$id'";
$data=mysqli_query($conn,$delete);

if($data){
    echo "<script>alert('Data deleted successfully');
      window.location.href='http://localhost:8000/app/Ticket/Index.php';
        </script>";
}else{
    echo "<script>alert('Error deleting data: " . mysqli_error($conn) . "');</script>";
}
?>