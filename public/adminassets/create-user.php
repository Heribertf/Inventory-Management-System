<?php
header('Content-Type: application/json');
// include_once './includes/page-access.php';

$response = [
    'success' => false,
    'message' => 'Cannot complete request'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

$type = $_GET["type"] ?? '';
$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$email = trim($_POST['email-address'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm_password = trim($_POST['conf-password'] ?? '');

$userRole = $type === 'role' ? trim($_POST['user-role'] ?? '') : '';
$userCategory = $type === 'other' ? (int) ($_POST['user-category'] ?? 0) : 0;

if (empty($password)) {
    $response['message'] = 'Please enter a password.';
} elseif (strlen($password) < 6) {
    $response['message'] = 'Password must have at least 6 characters.';
} elseif (empty($confirm_password)) {
    $response['message'] = 'Please confirm password.';
} elseif ($password !== $confirm_password) {
    $response['message'] = 'Password did not match.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Invalid email format.';
} elseif (empty($firstname) || empty($lastname) || empty($email)) {
    $response['message'] = 'One or more required fields is empty!';
} else {
    try {
        require_once '../includes/connection-db.php';

        $sql = "INSERT INTO users (firstname, lastname, email, password, " . ($type === 'role' ? 'role' : 'type') . ") VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $paramPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($type === 'role') {
            $stmt->bind_param('sssss', $firstname, $lastname, $email, $paramPassword, $userRole);
        } else {
            $stmt->bind_param('ssssi', $firstname, $lastname, $email, $paramPassword, $userCategory);
        }

        if ($stmt->execute()) {
            $response = [
                'success' => true,
                'message' => 'User created successfully.'
            ];
        } else {
            throw new Exception("Could not create user");
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);