<?php
require_once('connect.php');

$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

$stmt = $pdo->prepare('SELECT * FROM book_authors ba LEFT JOIN authors a ON ba.author_id=a.id WHERE book_id = :id');
$stmt->execute(['id' => $id]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>
        <?php echo $book['title']; ?>
    </h1>
    <span>
        <?php echo 'Aasta ' . $book['release_date']; ?>
    </span>
    <br>
    <span>
        <?php echo 'Type: ' . $book['type']; ?>
    </span>
    <ul>
        <?php while ($row = $stmt->fetch()) { ?>
            <li>
                <?php echo $row['first_name'] . ' ' . $row['last_name']; ?>
            </li>
            <?php
        }
        ?>
    </ul>
    <a href="edit.php?id=<?= $id ?>"> Muuda </a>
</body>

</html>