<?php
require_once('connect.php');

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $release_date = $_POST['release_date'];
    $type = $_POST['type'];
    $author = $_POST['author'];

    $update_fields = [];
    $params = ['id' => $id];
    if (!empty($title)) {
        $update_fields[] = 'title = :title';
        $params['title'] = $title;
    }
    if (!empty($release_date)) {
        $update_fields[] = 'release_date = :release_date';
        $params['release_date'] = $release_date;
    }
    if (!empty($type)) {
        $update_fields[] = 'type = :type';
        $params['type'] = $type;
    }

    $update_query = 'UPDATE books SET ' . implode(', ', $update_fields) . ' WHERE id = :id';
    $stmt = $pdo->prepare($update_query);
    $stmt->execute($params);

    $update_query = 'UPDATE book_authors SET author_id = :author_id WHERE book_id = :id';

    $stmt = $pdo->prepare($update_query);
    $stmt->execute(['author_id' => $author, 'id' => $id]);

    header('Location: hello.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $delete_query = 'UPDATE books SET is_deleted = 1 WHERE id = :id';
    $stmt = $pdo->prepare($delete_query);
    $stmt->execute(['id' => $id]);

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id AND is_deleted = 0');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

$stmt = $pdo->prepare('SELECT * FROM book_authors ba LEFT JOIN authors a ON ba.author_id=a.id WHERE book_id = :id');
$stmt->execute(['id' => $id]);

function is_deleted($book)
{
    return $book['is_deleted'] == 1;
}
?>

<?php
$stmt = $pdo->prepare('SELECT * FROM authors');
$stmt->execute();
$authors = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT DISTINCT type FROM books');
$stmt->execute();
$result = $stmt->fetchAll();

$types = array();
foreach ($result as $row) {
    $types[] = $row['type'];
}
?>

<h1>Muuda</h1>
<?php if (is_deleted($book)): ?>
    <p>This book has been deleted.</p>
<?php else: ?>
    <form method="POST">
        <label for="title">Pealkiri</label>

        <input type="text" name="title" value="<?php echo $book['title']; ?>">
        <br>

        <label for="release_date">Väljalaske aasta</label>
        <input type="number" min="0" max="2023" name="release_date" value="<?php echo $book['release_date']; ?>">

        <br>

        <label for="type">Tüüp</label>
        <select name="type">
            <?php foreach ($types as $type): ?>
                <option value="<?php echo $type; ?>" <?php if ($type == $book['type'])
                       echo 'selected'; ?>>
                    <?php echo $type; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br>

        <label for="author">Author</label>
        <select name="author">
            <?php foreach ($authors as $author): ?>
                <option value="<?php echo $author['id']; ?>" <?php if ($author['id'] == $book['author_id'])
                       echo 'selected'; ?>>
                    <?php echo $author['first_name'] . ' ' . $author['last_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br>

        <button type="submit">Salvesta</button>
    </form>

    <form method="POST" id="delete-form">
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit">Kustuta</button>
    </form>

    <script>
        document.getElementById('delete-form').addEventListener('submit', function (event) {
            event.preventDefault();

            fetch('?id=<?php echo $id; ?>', {
                method: 'DELETE'
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (data.success) {
                        window.location.href = 'hello.php';
                    }
                });
        });
    </script>
<?php endif; ?>