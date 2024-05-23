<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $taskName = trim($_POST['taskName']);
        if (in_array($taskName, array_column($_SESSION['tasks'], 'name'))) {
            $response['message'] = 'Task already exists!';
        } else {
            $_SESSION['tasks'][] = ['name' => $taskName, 'completed' => false];
            $response['success'] = true;
        }
    } elseif ($action === 'load') {
        $showAll = filter_var($_POST['showAll'], FILTER_VALIDATE_BOOLEAN);
        $tasks = $_SESSION['tasks'];
        if (!$showAll) {
            $tasks = array_filter($tasks, function($task) {
                return !$task['completed'];
            });
        }
        $response = '';
        foreach ($tasks as $index => $task) {
            $response .= '<tr>';
            $response .= '<td>' . ($index + 1) . '</td>';
            $response .= '<td>' . htmlspecialchars($task['name']) . '</td>';
            $response .= '<td>' . ($task['completed'] ? 'Done' : '') . '</td>';
            $response .= '<td>';
            $response .= '<input type="checkbox" class="task-checkbox" data-id="' . $index . '"' . ($task['completed'] ? ' checked' : '') . '>';
            $response .= '<button class="btn btn-danger btn-sm ml-2 delete-task" data-id="' . $index . '">x</button>';
            $response .= '</td>';
            $response .= '</tr>';
        }
        echo $response;
        exit;
    } elseif ($action === 'complete') {
        $taskId = $_POST['taskId'];
        if (isset($_SESSION['tasks'][$taskId])) {
            $_SESSION['tasks'][$taskId]['completed'] = true;
            $response['success'] = true;
        } else {
            $response['message'] = 'Task not found!';
        }
    } elseif ($action === 'delete') {
        $taskId = $_POST['taskId'];
        if (isset($_SESSION['tasks'][$taskId])) {
            unset($_SESSION['tasks'][$taskId]);
            $_SESSION['tasks'] = array_values($_SESSION['tasks']);
            $response['success'] = true;
        } else {
            $response['message'] = 'Task not found!';
        }
    }
}

//header('Content-Type: application/json');
echo json_encode($response);
