<?php
require_once('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    addAuthor($firstName, $lastName);
}

function addAuthor($firstName, $lastName)
{
    global $pdo;

    $stmt = $pdo->prepare('INSERT INTO authors (first_name, last_name) VALUES (:firstName, :lastName)');
    $stmt->execute(['firstName' => $firstName, 'lastName' => $lastName]);
}
?>

<html>

<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        First name: <input type="text" name="firstName"><br>
        Last name: <input type="text" name="lastName"><br>
        <input type="submit">
    </form>
</body>

</html>