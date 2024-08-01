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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>User Table</h1>
        <table>
            <thead>
                <tr>
                    <th>UserID</th>
                    <th>Phone</th>
                    <th>Name</th>
                    <th>Gender</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($userItems)) {
                    foreach ($userItems as $item) {
                        $userId = isset($item['UserID']['S']) ? htmlspecialchars($item['UserID']['S']) : 'N/A';
                        $phone = isset($item['Phone']['S']) ? htmlspecialchars($item['Phone']['S']) : 'N/A';
                        $name = isset($item['Name']['S']) ? htmlspecialchars($item['Name']['S']) : 'N/A';
                        $gender = isset($item['Gender']['S']) ? htmlspecialchars($item['Gender']['S']) : 'N/A';
                        
                        echo "<tr>";
                        echo "<td>$userId</td>";
                        echo "<td>$phone</td>";
                        echo "<td>$name</td>";
                        echo "<td>$gender</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <nav>
            <ul>
                <li><a href="dashboard.php">Back to Dashboard</a></li>
                <li><a href="guestlist.php">Guest List</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>
