<?php 

require_once '../config/config.php';

if(!isset($_SESSION['user'])){
    header("Location: ../auth/index.php");
    exit();
}

$user = $_SESSION['user'];
$userRole = $user['role'];


if($user['role']=='manager' || $user['role']=='admin'){
    $sql = "SELECT * from tasks";
}
else if($user['role']=='employee'){
    $sql = "SELECT * FROM tasks WHERE assigned_to=:user_id";
}

$stmt = $conn->prepare($sql);
if($user['role']!='manager' && $user['role']!='admin'){
    $stmt->bindParam(':user_id',$user['id']);
}
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<script>
    var role = "<?php echo $userRole; ?>";
</script>