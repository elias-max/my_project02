<?php
require 'vendor/autoload.php';
use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;

session_start();

// AWS SDK Configuration
$sdk = new Aws\Sdk([
    'region'   => 'us-east-1',
    'version'  => 'latest',
    'credentials' => [
        'key'    => '',
        'secret' => '',
    ],
]);

$dynamodb = $sdk->createDynamoDb();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Update getItem to include both partition key and sort key
        $result = $dynamodb->getItem([
            'TableName' => 'UserTable',
            'Key' => [
                'UserID' => ['S' => $username], // Partition key
                'Phone' => ['S' => '123-456-7890'] // Sort key, use actual value or modify to match the phone entered
            ]
        ]);

        // Debug output
        echo '<pre>';
        print_r($result);
        echo '</pre>';

        if (!empty($result['Item'])) {
            $hashedPassword = $result['Item']['Password']['S'];
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['username'] = $username;
                header('Location: dashboard.php');
                exit();
            } else {
                $error = "Invalid credentials.";
            }
        } else {
            $error = "User not found.";
        }
    } catch (AwsException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <div style="max-width: 1000px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <h1 style="text-align: center; color: #333; margin-bottom: 20px;">Azubi Login</h1>
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post" style="max-width: 400px; margin: auto;">
            <label for="username" style="display: block; margin-bottom: 10px;">Username:</label>
            <input type="text" id="username" name="username" required style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 4px;">

            <label for="password" style="display: block; margin-bottom: 10px;">Password:</label>
            <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 4px;">

            <input type="submit" value="Login" style="width: 100%; padding: 10px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer;">

            <p style="text-align: center; margin-top: 20px;">
                Don't have an account? <a href="register.php" style="color: #007bff; text-decoration: none;">Register here</a>.
            </p>
        </form>
    </div>
</body>
</html>
