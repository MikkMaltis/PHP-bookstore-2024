<?php
require_once('connect.php');

$store_title = "Bookstore";

if (isset($_POST['submit'])) {
    $key = $_POST['key'];
    $query = $pdo->prepare('SELECT * FROM books WHERE title LIKE :key AND is_deleted = 0');
    $query->execute(['key' => '%' . $key . '%']);
    $results = $query->fetchAll();
    $rows = $query->rowCount();
} else {
    $stmt = $pdo->query('SELECT id, title FROM books WHERE is_deleted = 0');
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
}

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
        <?php
        print $store_title;
        ?>
    </h1>
    <div class="w-full flex-row">
        <form method="POST" action="">
            <input class="search_bar bg-gray-200" type="text" placeholder="Search books.." name="key">
            <input class="submit_button border-2 border-black" type="submit" value="Submit" name="submit">
        </form>
        <div>
            <a href="author.php">Add a new author!</a>
        </div>
    </div>
<ul>
    <?php
    if (isset($results)) {
        if ($rows != 0) {
            foreach ($results as $result) {
                echo "<li class='underline '><a href='book.php?id=" . $result['id'] . "'>" . $result['title'] . "</a></li>";
            }
        } else {
            echo "No results";
        }
    } else {
        while ($row = $stmt->fetch()) {
            echo "<li class='underline '><a href='book.php?id=" . $row['id'] . "'>" . $row['title'] . "</a></li>";
        }
    }
    ?>
</ul>
</body>

</html>