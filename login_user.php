<?php 
session_start(); 

$servername = "localhost";
$username_db = "root";  // Elakkan konflik nama variabel dengan input form
$password_db = "";
$dbname = "luxury_store";

// Sambung ke database
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Semak jika sambungan berjaya
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Sambungan database berjaya<br>";
}

// Terima data dari form dengan cara yang selamat
$username = $_POST['username'];
$password = $_POST['password'];

// Semak pengguna dalam database dengan prepared statement
$stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Semak kata laluan menggunakan password_verify() jika disimpan sebagai hash
    if (password_verify($password, $row['password'])) { // Gunakan $password, bukan $pass
        // Set sesi pengguna
        $_SESSION['username'] = $username; // Gunakan $username yang betul
        $_SESSION['role'] = $row['role'];

        // Halaman redirect berdasarkan role
        if ($row['role'] == 'admin') {
            header('Location: main.html'); // Ubah ke halaman admin yang sesuai
        } else {
            header('Location: main.html');  // Ubah ke halaman pengguna yang sesuai
        }
        exit(); // Pastikan skrip berhenti selepas redirect
    } else {
        echo "Invalid password!";
    }
} else {
    echo "No user found!";
}

// Tutup statement dan sambungan
$stmt->close();
$conn->close();
?>
