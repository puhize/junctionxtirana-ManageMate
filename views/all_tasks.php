<?php
require '../config/config.php';
session_start();
include('../dashboards/employee.php');
include('../config/config.php');
include('includes/header.php');
include('../task_manager/getEnums.php');
try {
    $stmt = $conn->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt2 = $conn->query("SELECT * FROM tasks");
    $tasks = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $userCount = count($users);
    $tasksCount = count($tasks);

    $stmt2 = $conn->query("SELECT COUNT(*) AS in_progress_tasks_count FROM tasks WHERE status = 'In Progress'");
    $taskResult = $stmt2->fetch(PDO::FETCH_ASSOC);
    $inProgressTasksCount = $taskResult['in_progress_tasks_count'];


    $stmt2 = $conn->query("SELECT COUNT(*) AS done_tasks_count FROM tasks WHERE status = 'Done'");
    $taskResult2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $doneTasksCount = $taskResult2['done_tasks_count'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$sql = "SHOW COLUMNS FROM tasks WHERE Field = 'status'";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Fetch the column information
$columnInfo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($columnInfo) {
    // Extract enum values from the column definition
    preg_match_all("/'([^']+)'/", $columnInfo['Type'], $matches);
    $enumValues = $matches[1];

    // Output the enum values
}

$sql = "SELECT * FROM tasks";
$stmt = $conn->prepare($sql);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);



$userSql = "SELECT * FROM users WHERE role='employee'";
$userStmt = $conn->prepare($userSql);
$userStmt->execute();
$employees = $userStmt->fetchAll(PDO::FETCH_ASSOC);

$statuses = getEnumValues($conn, 'tasks', 'status');
$priorities = getEnumValues($conn, 'tasks', 'priority');


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="https://cdn.discordapp.com/attachments/1239877130016264203/1246494735955398756/erta-logo.png?ex=665c982f&is=665b46af&hm=37da24a2c8e62d1df181f3041a913d796ff77b270f56268bd5996b06f7b9ec37&">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        Manage Mate
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/paper-dashboard.css" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="../assets/demo/demo.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<?php include('includes/header.php'); ?>


<body>

    <div class="wrapper ">
        <?php include('includes/sidebar.php'); ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include('includes/navbar.php'); ?>
            <style>
                .task {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 10px;
                    border-bottom: 1px solid #ddd;
                }

                .task-title {
                    margin: 0;
                    font-size: 14px;
                }

                .task-icon {
                    cursor: pointer;
                }
            </style>
            <div class="content">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-single-02 text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Users</p>
                                            <p class="card-title"><?= $userCount ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-money-coins text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Tasks</p>
                                            <p class="card-title"><?= $tasksCount ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-satisfied text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Finished</p>
                                            <p class="card-title"><?= $doneTasksCount ?></p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-bullet-list-67 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">In-Progress Tasks</p>
                                            <p class="card-title"><?= $inProgressTasksCount ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid px-1 py-3">
                    <div class="card mb-4">
                        <div class="card-header">Tasks Management Table</div>
                        <div class="card-body">
                            <div class="card-body">
                                <button type="button" class="btn btn-secondary mb-4" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                    <i class="fa-solid fa-plus"></i> Add New Task
                                </button>
                            </div>
                            <!-- Task Table -->
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Deadline</th>
                                        <th>Assigned To</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tasks as $task) : ?>
                                        <tr>
                                            <td><?= $task['id'] ?></td>
                                            <td><?= $task['title'] ?></td>
                                            <td><?= $task['description'] ?></td>
                                            <td><?= $task['status'] ?></td>
                                            <td><?= $task['priority'] ?></td>
                                            <td><?= $task['deadline'] ?></td>
                                            <td><?= $task['assigned_to'] ?></td>
                                            <td class="action-links">
                                                <form method="post" action="../task_manager/edit_tasks.php">
                                                    <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                                    <a href='../task_manager/delete_task.php?id=<?= $task['id'] ?>' class="btn btn-sm btn-primary edit-btn">Delete</a>
                                                    <button type="button" class="btn btn-sm btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editTaskModal<?= $task['id'] ?>" data-id="<?= $task['id'] ?>">Edit</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- Add Task Modal -->
                <div class="modal" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="../task_manager/create_task.php">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title:</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description:</label>
                                        <input type="text" class="form-control" id="description" name="description" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status:</label>
                                        <select class="form-select" id="status" name="status">
                                            <?php foreach ($statuses as $status) : ?>
                                                <option><?php echo $status; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="priority" class="form-label">Priority:</label>
                                        <select class="form-select" id="priority" name="priority">
                                            <?php foreach ($priorities as $priority) : ?>
                                                <option><?php echo $priority; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="deadline" class="form-label">Deadline:</label>
                                        <?php $today = date('Y-m-d'); ?>
                                        <input type="date" class="form-control" id="deadline" name="deadline" min=<?php echo $today; ?>>
                                    </div>
                                    <div class="mb-3">
                                        <label for="assigned_to" class="form-label">Assign To:</label>
                                        <select class="form-select" id="assigned_to" name="assigned_to">
                                            <?php foreach ($employees as $employee) : ?>
                                                <option value="<?php echo $employee['id']; ?>"><?php echo $employee["name"] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Task Modal -->
                <?php foreach ($tasks as $task) : ?>
                    <div class="modal" id="editTaskModal<?= $task['id'] ?>" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="../task_manager/update_task.php">
                                        <input type="hidden" name="taskId" value="<?= $task['id'] ?>">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title:</label>
                                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $task['title']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description:</label>
                                            <input type="text" class="form-control" id="description" name="description" value="<?php echo $task['description']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status:</label>
                                            <select class="form-select" id="status" name="status">
                                                <?php foreach ($statuses as $status) : ?>
                                                    <option <?php echo ($task['status'] == $status) ? 'selected' : ''; ?>><?php echo $status; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="priority" class="form-label">Priority:</label>
                                            <select class="form-select" id="priority" name="priority">
                                                <?php foreach ($priorities as $priority) : ?>
                                                    <option <?php echo ($task['priority'] == $priority) ? 'selected' : ''; ?>><?php echo $priority; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="deadline" class="form-label">Deadline:</label>
                                            <?php $today = date('Y-m-d'); ?>
                                            <input type="date" class="form-control" id="deadline" name="deadline" value="<?php echo $task['deadline']; ?>  min=<?php echo $today; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="assigned_to" class="form-label">Assigned To:</label>
                                            <select class="form-select" id="assigned_to" name="assigned_to">
                                                <?php foreach ($employees as $employee) : ?>
                                                    <option value="<?php echo $employee['id']; ?>" <?php echo ($task['assigned_to'] == $employee['id']) ? 'selected' : ''; ?>><?php echo $employee["name"]; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="update">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- 
                <footer class="footer" style="position: absolute; bottom: 0; width: -webkit-fill-available;">
                    <div class="container-fluid">
                        <div class="row">
                            <nav class="footer-nav">
                                <ul>
                                    <li><a href="https://ertadigitalmarketing.com/" target="_blank">ERTA</a></li>
                                    <li><a href="https://www.facebook.com/ertadigitalmarketing" target="_blank">Facebook</a></li>
                                    <li><a href="https://www.instagram.com/fioralbafazlli/" target="_blank">Instagram</a></li>

                                </ul>
                            </nav>

                        </div>
                    </div>
                </footer> -->
            </div>






            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <!-- Font Awesome for icons -->
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">


            <!--   Core JS Files   -->
            <script src="../assets/js/core/jquery.min.js"></script>
            <script src="../assets/js/core/popper.min.js"></script>
            <script src="../assets/js/core/bootstrap.min.js"></script>
            <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
            <!--  Google Maps Plugin    -->
            <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
            <!-- Chart JS -->
            <script src="../assets/js/plugins/chartjs.min.js"></script>
            <!--  Notifications Plugin    -->
            <script src="../assets/js/plugins/bootstrap-notify.js"></script>
            <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
            <script src="../assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script>

            <script>
                // function selectOption() {
                //     const selectElement = document.getElementById('status_active');
                //     const selectedValue = selectElement.value;

                //     for (let i = 0; i < selectElement.options.length; i++) {
                //         if (selectElement.options[i].value == selectElement.value) {
                //             selectElement.options[i].selected = true;
                //         }
                //     }

                // }
            </script>
</body>

</html>