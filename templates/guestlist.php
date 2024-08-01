<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest List</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Guest Book</h1>
    <table border="1">
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Country</th>
        </tr>
        <?php if (!empty($guestBookItems)) : ?>
            <?php foreach ($guestBookItems as $item): ?>
                <tr>
                    <td><?= $item['Email']['S'] ?></td>
                    <td><?= $item['Name']['S'] ?></td>
                    <td><?= $item['Country']['S'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="3">No records found</td>
            </tr>
        <?php endif; ?>
    </table>

    <h1>User Table</h1>
    <table border="1">
        <tr>
            <th>UserID</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>Name</th>
        </tr>
        <?php if (!empty($userTableItems)) : ?>
            <?php foreach ($userTableItems as $item): ?>
                <tr>
                    <td><?= $item['UserID']['S'] ?></td>
                    <td><?= $item['Phone']['S'] ?></td>
                    <td><?= $item['Gender']['S'] ?></td>
                    <td><?= $item['Name']['S'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="4">No records found</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
