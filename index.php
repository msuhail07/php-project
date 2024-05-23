<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP - Simple To Do List App</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="text-center text-primary">PHP - Simple To Do List App</h1>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form id="taskForm" class="form-inline mb-3">
                <div class="form-group mx-sm-3 mb-2">
                    <input type="text" id="taskName" class="form-control" placeholder="Enter task name">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Add Task</button>
            </form>
            <div class="mb-3">
                <button id="showAllTasks" class="btn btn-secondary">Show All Tasks</button>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Task</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="taskList"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.3/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        loadTasks();

        $('#taskForm').submit(function(e) {
            e.preventDefault();
            const taskName = $('#taskName').val().trim();
            if (taskName) {
                $.post('action.php', {action: 'add', taskName: taskName}, function(response) {
                    if (response.success) {
                        loadTasks();
                        $('#taskName').val('');
                    } else {
                        alert(response.message);
                    }
                }, 'json');
            }
        });

        $('#showAllTasks').click(function() {
            loadTasks(true);
        });

        function loadTasks(showAll = false) {
            $.post('action.php', {action: 'load', showAll: showAll}, function(response) {
                $('#taskList').html(response);
            });
        }

        $(document).on('change', '.task-checkbox', function() {
            const taskId = $(this).data('id');
            $.post('action.php', {action: 'complete', taskId: taskId}, function(response) {
                if (response.success) {
                    loadTasks();
                } else {
                    alert(response.message);
                }
            }, 'json');
        });

        $(document).on('click', '.delete-task', function() {
            if (confirm('Are you sure to delete this task?')) {
                const taskId = $(this).data('id');
                $.post('action.php', {action: 'delete', taskId: taskId}, function(response) {
                    if (response.success) {
                        loadTasks();
                    } else {
                        alert(response.message);
                    }
                }, 'json');
            }
        });
    });
</script>
</body>
</html>
