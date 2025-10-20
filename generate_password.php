<?php
// Script to generate password hash for admin user

// Password you want to use
$newPassword = "admin123"; // Change this to your desired password

// Generate hash (same method used in AuthController)
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

echo "==================================\n";
echo "PASSWORD HASH GENERATOR\n";
echo "==================================\n\n";

echo "Plain Password: " . $newPassword . "\n";
echo "Hashed Password: " . $hashedPassword . "\n\n";

echo "==================================\n";
echo "INSTRUCTIONS:\n";
echo "==================================\n\n";

echo "1. Go to MongoDB Atlas: https://cloud.mongodb.com\n";
echo "2. Select: swordhub database → users collection\n";
echo "3. Find your admin user (or any user)\n";
echo "4. Click 'Edit' on that user\n";
echo "5. Update the 'password' field with this hash:\n\n";
echo "   " . $hashedPassword . "\n\n";
echo "6. Make sure 'role' is set to: \"admin\"\n";
echo "7. Save changes\n\n";

echo "8. Then login with:\n";
echo "   Email: [your admin email]\n";
echo "   Password: " . $newPassword . "\n\n";

echo "==================================\n";
echo "OR - Quick MongoDB Shell Command:\n";
echo "==================================\n\n";

echo "db.users.updateOne(\n";
echo "  { email: \"admin@swordhub.com\" },\n";
echo "  { \$set: { \n";
echo "      password: \"" . $hashedPassword . "\",\n";
echo "      role: \"admin\"\n";
echo "    }\n";
echo "  }\n";
echo ");\n\n";

echo "Replace 'admin@swordhub.com' with your actual admin email.\n\n";
?>
