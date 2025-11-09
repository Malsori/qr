<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['surname'] = $user['surname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['birthday'] = $user['birthday'];
            $_SESSION['passport_number'] = $user['passport_number'];

            header("Location: Prompt.php"); // redirect to dashboard/home
            exit;
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Login</title>
<style>
:root {
  --primary-color: #F5B700;
  --secondary-color: #00798C;
  --accent-color: #F25F5C;
  --background-color: #30343F;
}
* { box-sizing: border-box; font-family: "Inter", "Poppins", sans-serif; }
body {
  background: radial-gradient(circle at top left, var(--background-color) 60%, #1e212a);
  display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; color: #fff;
}
.card {
  backdrop-filter: blur(20px);
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 1.5rem;
  padding: 3rem 2rem;
  width: 100%;
  max-width: 380px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
  transition: all 0.3s ease;
}
.card:hover { transform: translateY(-4px); box-shadow: 0 10px 35px rgba(0, 0, 0, 0.5); }
h2 { text-align: center; color: var(--primary-color); letter-spacing: 1px; margin-bottom: 2rem; font-weight: 700; }
form { display: flex; flex-direction: column; gap: 1rem; }
input {
  background: rgba(255, 255, 255, 0.1); border: none; padding: 0.9rem; border-radius: 0.8rem;
  color: #fff; font-size: 1rem; transition: all 0.3s ease; outline: none;
}
input:focus { box-shadow: 0 0 0 2px var(--secondary-color); background: rgba(255,255,255,0.15); }
button {
  margin-top: 0.5rem; padding: 0.9rem;
  background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
  border: none; border-radius: 0.8rem; color: #fff; font-weight: 600; letter-spacing: 0.5px;
  cursor: pointer; transition: all 0.3s ease;
}
button:hover { background: linear-gradient(135deg, var(--accent-color), var(--secondary-color)); transform: translateY(-1px); }
.links { text-align: center; margin-top: 1.3rem; font-size: 0.9rem; }
.links a { color: var(--primary-color); text-decoration: none; font-weight: 600; }
.links a:hover { color: var(--accent-color); }
.home-btn { display: inline-block; text-align: center; margin-top: 1.7rem; padding: 0.7rem 1.2rem; border-radius: 0.8rem; background: var(--primary-color); color: #000; text-decoration: none; font-weight: 700; transition: all 0.3s ease; }
.home-btn:hover { background: var(--accent-color); color: #fff; }
.error { background: #ff4d4d; padding: 0.8rem; border-radius: 0.8rem; margin-bottom: 1rem; text-align: center; }
</style>
</head>
<body>
<div class="card">
  <h2>Welcome Back 👋</h2>

  <?php
  if (!empty($errors)) {
      foreach ($errors as $err) {
          echo "<div class='error'>{$err}</div>";
      }
  }
  ?>

  <form method="POST" action="">
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Login</button>
  </form>

  <div class="links">
    <p>New here? <a href="Register.php">Create an account</a></p>
  </div>
  <a href="index.php" class="home-btn">Home</a>
</div>
</body>
</html>
