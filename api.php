<?php
// ============================================================
// Agile Scheduling Portal API
// Handles: list, add_story, delete_story, commit_to_sprint,
//          update_assignee, advance_status
// ============================================================
header('Content-Type: application/json');
require_once 'db_config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        listAll($conn);
        break;
    case 'add_story':
        addStory($conn);
        break;
    case 'delete_story':
        deleteStory($conn);
        break;
    case 'commit_to_sprint':
        commitToSprint($conn);
        break;
    case 'update_assignee':
        updateAssignee($conn);
        break;
    case 'advance_status':
        advanceStatus($conn);
        break;
    default:
        echo json_encode(["success" => false, "message" => "Unknown action: $action"]);
}

$conn->close();

// ------------------------------------------------------------

function listAll($conn) {
    $backlog = [];
    $result = $conn->query("SELECT * FROM product_backlog ORDER BY id ASC");
    while ($row = $result->fetch_assoc()) {
        $backlog[] = $row;
    }

    $sprint = [];
    $result2 = $conn->query("SELECT * FROM sprint_backlog ORDER BY id ASC");
    while ($row = $result2->fetch_assoc()) {
        $sprint[] = $row;
    }

    echo json_encode(["success" => true, "backlog" => $backlog, "sprint" => $sprint]);
}

function addStory($conn) {
    $persona  = trim($_POST['persona'] ?? '');
    $goal     = trim($_POST['goal'] ?? '');
    $benefit  = trim($_POST['benefit'] ?? '');
    $priority = $_POST['priority'] ?? 'Medium';
    $points   = intval($_POST['points'] ?? 5);

    if (!in_array($priority, ['High','Medium','Low'])) $priority = 'Medium';

    if ($persona === '' || $goal === '') {
        echo json_encode(["success" => false, "message" => "Persona and goal are required."]);
        return;
    }

    $countResult = $conn->query("SELECT COUNT(*) AS cnt FROM product_backlog");
    $count = intval($countResult->fetch_assoc()['cnt']) + 1;
    $storyCode = "US-" . str_pad($count, 2, "0", STR_PAD_LEFT);

    $fullStory = "As a $persona, I want $goal";
    if ($benefit !== '') $fullStory .= ", so that $benefit";
    $fullStory .= ".";

    $stmt = $conn->prepare(
        "INSERT INTO product_backlog (story_code, persona, goal, benefit, full_story, priority, points)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssssi", $storyCode, $persona, $goal, $benefit, $fullStory, $priority, $points);
    $stmt->execute();
    $stmt->close();

    echo json_encode(["success" => true, "story_code" => $storyCode]);
}

function deleteStory($conn) {
    $id = intval($_POST['id'] ?? 0);
    $stmt = $conn->prepare("DELETE FROM product_backlog WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(["success" => true]);
}

function commitToSprint($conn) {
    $id = intval($_POST['id'] ?? 0);

    $stmt = $conn->prepare("SELECT * FROM product_backlog WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo json_encode(["success" => false, "message" => "Story not found."]);
        return;
    }

    $stmt2 = $conn->prepare(
        "INSERT INTO sprint_backlog (story_code, task, priority, points, assignee, status)
         VALUES (?, ?, ?, ?, 'Unassigned', 'To Do')"
    );
    $stmt2->bind_param("sssi", $row['story_code'], $row['full_story'], $row['priority'], $row['points']);
    $stmt2->execute();
    $stmt2->close();

    $del = $conn->prepare("DELETE FROM product_backlog WHERE id = ?");
    $del->bind_param("i", $id);
    $del->execute();
    $del->close();

    echo json_encode(["success" => true]);
}

function updateAssignee($conn) {
    $id = intval($_POST['id'] ?? 0);
    $assignee = trim($_POST['assignee'] ?? 'Unassigned');

    $stmt = $conn->prepare("UPDATE sprint_backlog SET assignee = ? WHERE id = ?");
    $stmt->bind_param("si", $assignee, $id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(["success" => true]);
}

function advanceStatus($conn) {
    $id = intval($_POST['id'] ?? 0);
    $order = ["To Do", "In Progress", "Completed"];

    $stmt = $conn->prepare("SELECT status FROM sprint_backlog WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo json_encode(["success" => false, "message" => "Task not found."]);
        return;
    }

    $idx = array_search($row['status'], $order);
    if ($idx === false || $idx >= count($order) - 1) {
        echo json_encode(["success" => true, "unchanged" => true, "status" => $row['status']]);
        return;
    }

    $newStatus = $order[$idx + 1];
    $stmt2 = $conn->prepare("UPDATE sprint_backlog SET status = ? WHERE id = ?");
    $stmt2->bind_param("si", $newStatus, $id);
    $stmt2->execute();
    $stmt2->close();

    echo json_encode(["success" => true, "status" => $newStatus]);
}
