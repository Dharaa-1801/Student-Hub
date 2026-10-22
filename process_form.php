<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $course = htmlspecialchars(trim($_POST['course']));
    $message = htmlspecialchars(trim($_POST['message']));

    $errors = [];

    if (empty($fullname)) {
        $errors[] = "Full name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address format.";
    }

    if (empty($course)) {
        $errors[] = "Please select a department.";
    }

    if (empty($message)) {
        $errors[] = "Message cannot be empty.";
    }

    if (empty($errors)) {
        $csvFile = 'submissions.csv';
        $fileExists = file_exists($csvFile);

        $fileHandle = fopen($csvFile, 'a');

        if ($fileHandle) {
            if (!$fileExists) {
                fputcsv($fileHandle, ['Full Name', 'Email', 'Department', 'Message', 'Timestamp']);
            }

            fputcsv($fileHandle, [$fullname, $email, $course, $message, date('Y-m-d H:i:s')]);
            fclose($fileHandle);

            echo "<script>alert('Success: Form data successfully validated and stored in CSV!'); window.location.href='contact.php';</script>";
        } else {
            echo "<script>alert('Error: Unable to save data to file.'); window.location.href='contact.php';</script>";
        }
    } else {
        $errorMessage = implode("\\n", $errors);
        echo "<script>alert('Validation Failed:\\n" . $errorMessage . "'); window.location.href='contact.php';</script>";
    }
} else {
    header("Location: contact.php");
    exit();
}
?>