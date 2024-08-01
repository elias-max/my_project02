<?php
require 'vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;

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

// Fetch UserTable Data
try {
    $userResult = $dynamodb->scan([
        'TableName' => 'UserTable',
    ]);
    $userItems = $userResult['Items'];
} catch (AwsException $e) {
    $userItems = [];
    echo "Error fetching UserTable data: " . $e->getMessage();
}

// Fetch GuestBook Data
try {
    $guestBookResult = $dynamodb->scan([
        'TableName' => 'GuestBook',
    ]);
    $guestBookItems = $guestBookResult['Items'];
} catch (AwsException $e) {
    $guestBookItems = [];
    echo "Error fetching GuestBook data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav>
            <ul>
                <li><a href="dashboard.php">Back to Dashboard</a></li>
                <li><a href="userlist.php">User List</a></li>
            </ul>
        </nav>
        <h1>Guest Book</h1>
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Country</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($guestBookItems)) {
                    foreach ($guestBookItems as $item) {
                        $email = isset($item['Email']['S']) ? htmlspecialchars($item['Email']['S']) : 'N/A';
                        $name = isset($item['Name']['S']) ? htmlspecialchars($item['Name']['S']) : 'N/A';
                        $country = isset($item['Country']['S']) ? htmlspecialchars($item['Country']['S']) : 'N/A';
                        
                        echo "<tr>";
                        echo "<td>$email</td>";
                        echo "<td>$name</td>";
                        echo "<td>$country</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
