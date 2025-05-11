<?php 
    session_start();
    require_once "config/db.php";

    $errors = [];
    $success = false;

    // Process form when submitted
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = sanitizeInput($_POST['firstName']);
        $lastName = sanitizeInput($_POST['lastName']);
        $username = sanitizeInput($_POST['username']);
        $email = sanitizeInput($_POST['email']);
        $phone = sanitizeInput($_POST['phone']);
        $gender = sanitizeInput($_POST['gender']);
        $dob = !empty($_POST['dob']) ? sanitizeInput($_POST['dob']) : null;
        $role = sanitizeInput($_POST['role']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $permissions = isset($_POST['permissions']) ? $_POST['permissions'] : [];
        $permissionsJson = json_encode($permissions);

        // Validate password match
        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }

        // Validate password strength
        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {
            $errors[] = "Password must be at least 8 characters with uppercase, lowercase and numbers.";
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $users = $result->fetch_assoc();
            $errors[] = "Username or email already exists.";
        }
        $stmt->close();

        // Handle Image Upload
        $profileImage = null;
        if(isset($_FILES["profile_image"])){
            $file = $_FILES["profile_image"];

            if($file["error"] == UPLOAD_ERR_OK){
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxSize = 2 * 1024 * 1024; // 2MB

                if(in_array($file["type"], $allowedTypes)){
                    if($file["size"] <= $maxSize){
                        // Generate unique filename

                        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
                        $fileName = 'profile_'.date('YmdHis')."_".uniqid().".".$ext;
                        $uploadPath = __DIR__ . '/uploads/'.$fileName;
                        
                        // Create uploads directory if it doesn't exist
                        if(!is_dir(__DIR__ . '/uploads')){
                            mkdir(__DIR__ . '/uploads/',0755, true);
                        }

                        // Move uploaded file
                        if(move_uploaded_file($file["tmp_name"], $uploadPath)){
                            $profileImage = 'uploads/' . $fileName;
                        }else{
                            $errors[] = "Failed to upload image.";
                        }

                    }else{
                        $errors[] = "File size exceeds 2MB.";
                    }
                }else{
                    $errors[] = "Only JPG, PNG, and GIF images are allowed.";
                }
            }
        }

        if(empty($errors)){
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


            // Preparation of insert statement
            $stmt = $conn->prepare(
                "INSERT INTO users (first_name, last_name, username, email, password, phone, gender, dob, role,  permissions, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param("sssssssssss", $firstName, $lastName, $username, $email, $hashedPassword, $phone, $gender, $dob, $role, $permissionsJson, $profileImage);

            if($stmt->execute()){
                $success= true;
                $_SESSION["success_message"] = "Registration successful! You can now login.";
                header("Location: login.php");
                exit();
            }else{
                $errors[] = "Database Error: " . $stmt->error;
                
                // Delete uploaded image if database insert failed
                if($profileImage && file_exists(__DIR__.'/'.$profileImage)){
                    unlink(__DIR__.'/'.$profileImage);
                }
            }

            $stmt->close();
        }

    }

    $title = "Register";
    $isAuthPage = true;

    ob_start();
?>

<div class="row min-vh-100 align-items-center justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-md">
            <div class="card-body p-4">
                <h4 class="mb-4 text-center">Register New Admin</h4>

                <!-- Display error messages -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-1"><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" autocomplete="off" enctype="multipart/form-data" >
                    <!-- Personal Information Section -->
                    <div class="mb-4">
                        <h5 class="mb-3 border-bottom pb-2">Personal Information</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="firstName">First Name:<span class="text-danger">*</span></label>
                                <input id="firstName" name="firstName" placeholder="John" required type="text" class="form-control" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="lastName">Last Name:<span class="text-danger">*</span></label>
                                <input id="lastName" name="lastName" placeholder="Doe" required type="text" class="form-control" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email:<span class="text-danger">*</span></label>
                            <input id="email" name="email" placeholder="e.g johndoe@example.com" required type="email" class="form-control" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone Number:</label>
                            <input id="phone" name="phone" placeholder="+1234567890" type="tel" class="form-control" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gender:<span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male" required />
                                    <label class="form-check-label" for="genderMale">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female" />
                                    <label class="form-check-label" for="genderFemale">Female</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="genderOther" value="other" />
                                    <label class="form-check-label" for="genderOther">Other</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="dob">Date of Birth:</label>
                            <input id="dob" name="dob" type="date" class="form-control" max="<?= date('Y-m-d'); ?>" value="<?= isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : '' ?>" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="profile_image">Profile Image:</label>
                            <input id="profile_image" name="profile_image" type="file" class="form-control" accept="image/*" />
                            <div class="form-text">Max size 2MB (JPG, PNG, GIF)</div>
                        </div>
                    </div>

                    <!-- Account Security Section -->
                    <div class="mb-4">
                        <h5 class="mb-3 border-bottom pb-2">Account Security</h5>
                        
                        <div class="mb-3">
                            <label class="form-label" for="username">Username:<span class="text-danger">*</span></label>
                            <input id="username" name="username" placeholder="johndoe" required type="text" class="form-control" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Password:<span class="text-danger">*</span></label>
                            <input id="password" name="password" required type="password" class="form-control" 
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                                title="Must contain at least one number, one uppercase and lowercase letter, and at least 8 or more characters" />
                            <div class="form-text">Password must be at least 8 characters with uppercase, lowercase and numbers</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="confirmPassword">Confirm Password:<span class="text-danger">*</span></label>
                            <input id="confirmPassword" name="confirmPassword" required type="password" class="form-control" />
                        </div>
                    </div>

                    <!-- Admin Permissions Section -->
                    <div class="mb-4">
                        <h5 class="mb-3 border-bottom pb-2">Admin Permissions</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Select Admin Role:<span class="text-danger">*</span></label>
                            <select class="form-select" name="role" required>
                                <option value="" selected disabled>Select a role</option>
                                <option value="super_admin">Super Admin</option>
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="editor">Editor</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Access Permissions:</label>
                            <div class="border p-3 rounded">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="inventoryManagement" value="inventory_management" />
                                    <label class="form-check-label" for="inventoryManagement">Inventory Management</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="customerManagement" value="customer_management" />
                                    <label class="form-check-label" for="customerManagement">Customer Management</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="blogManagement" value="blog_management" />
                                    <label class="form-check-label" for="blogManagement">Blog Management</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="invoiceManagement" value="invoice_management" />
                                    <label class="form-check-label" for="invoiceManagement">Invoice Management</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="userManagement" value="user_management" />
                                    <label class="form-check-label" for="userManagement">User Management</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="reporting" value="reporting" />
                                    <label class="form-check-label" for="reporting">Reporting</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="settings" value="settings" />
                                    <label class="form-check-label" for="settings">System Settings</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required />
                            <label class="form-check-label" for="terms">I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a><span class="text-danger">*</span></label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
                </form>

                <div class="mt-3 text-center">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal (hidden by default) -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Your terms and conditions content here -->
                <p>By Agreeing to the Terms and Conditions, you acknowledge that you have read, understand, and agree to be bound by these terms and conditions.</p>
                <ul>
                    <li>Ziada Shokha ni hona!</li>
                    <li>Hr mah izzat se 5000 mere account me send kro ge</li>
                    <li>Tameez se use kro ge Platform ko</li>
                    <li>Beta aage share kia to dekh lena phr hota kia ha</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include "Layout.php";
?>