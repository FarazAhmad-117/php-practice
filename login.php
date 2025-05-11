<?php

    session_start();
    require_once("config/db.php");

    $errors = [];
    $success = false;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $identifier = sanitizeInput($_POST["identifier"]);
        $password = $_POST["password"];

        if(empty($identifier) || empty($password)){
            $errors[] = "Please fill in all fields.";
        }else{
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->bind_param("ss", $identifier, $identifier);
            $stmt->execute();
            $result  = $stmt->get_result();
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Verify password
                if(password_verify($password, $user["password"])){
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["username"] = $user["username"];
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["role"] = $user["role"];
                    $_SESSION["permissions"] = json_decode($user["permissions"]);
                    $_SESSION["logged_in"] = true;

                    // Regenerate session ID to prevent session fixation
                    session_regenerate_id(true);

                    if($_SESSION["redirect_url"]){
                        $redirectUrl = $_SESSION["redirect_url"];
                        unset($_SESSION["redirect_url"]);
                        header("Location: $redirectUrl");
                        exit();
                    }

                    // Redirect to dashboard
                    header("Location: index.php");
                    exit();
                }else{
                    $errors[] = "Invalid credentials.";
                }
            }else{
                // User not found
                $errors[] = "Invalid credentials.";
            }
            $stmt->close();
        }
    }


    // Set variables for the layout
    $title = 'Login';
    $isAuthPage = true;

    // Start output buffering
    ob_start();
?>
<div class="row justify-content-center align-items-center min-vh-100" >
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Login</h2>

                <!-- Display error messages -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-1"><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form  method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email or Username:</label>
                        <input type="text" class="form-control" id="email" name="identifier" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <div class="mt-2" >
                    <p class="text-center" >Don't have an account? <a href="register.php">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
// Get the buffered content
$content = ob_get_clean();

// Include the layout
include 'Layout.php';