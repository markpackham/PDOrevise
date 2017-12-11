<?php

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'pdoposts';

// Set DSN (Data Source Name), string to describe connection to datasource
// eg database type such as MYSQL or Oracle
$dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;

//create PDO instance (Php Database Object)
$pdo = new PDO($dsn, $user, $password);

//we can turn the array into an object
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

#PDO Query
//just using stmt since to lazy to write "statement"
$stmt = $pdo->query('SELECT * FROM posts');

/*
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['title'] . '<br>';
}
*/

/* if we we set it to an object then we don't need to do thi
while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    echo $row->title . '<br>';
}
*/

// This only works if we've set the array as an object via $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
while ($row = $stmt->fetch()) {
    echo $row->title . '<br>';
}

#Prepated Statements (prepare & exectute)

//Never do unless you want an SQL Injection!!!!!!!!!!!!!
//$sql= "SELECT * FROM posts WHERE author = '$author'";


//User Input
$author = 'Mark';

// Fetch Multiple Posts
//Positional Params (using the ?)
$sql = 'SELECT * FROM posts WHERE author = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$author]);
$posts = $stmt->fetchAll();

//var_dump($posts);

foreach ($posts as $post) {
    echo $post->title . ' by ' . $post->author . '<br>';
}

$author = 'Paul';
$is_published = true;

//Named Params (using the :)
$sql = 'SELECT * FROM posts WHERE author = :author && is_published = :is_published';
$stmt = $pdo->prepare($sql);
//We are using an associative array
$stmt->execute(['author' => $author, 'is_published' => $is_published]);
$posts = $stmt->fetchAll();

foreach ($posts as $post) {
    echo $post->title . ' by ' . $post->author . '<br>';
}

$id = 1;

//Fetch Single Post
$sql = 'SELECT * FROM posts WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
//We are just getting 1 record so no need for fetchAll()
$posts = $stmt->fetch();

echo $posts->body . ' has id of ' . $posts->id . '<br>';


//Get Row Count
$stmt = $pdo->prepare('SELECT * FROM POSTS WHERE author = ?');
$stmt->execute([$author]);
$postCount = $stmt->rowCount();
echo $postCount . '<br>';

//Insert Data
/*
$title = 'Post Nine';
$body = 'This is Post Nine';
$author = 'Mark';

$sql = 'INSERT INTO posts(title, body, author) VALUES(:title, :body, :author)';
$stmt = $pdo->prepare($sql);
$stmt->execute(['title'=>$title, 'body'=>$body, 'author'=>$author]);
*/

// Update Data
/*
$id = 1;
$body = 'Post 1 updated';

$sql = 'UPDATE posts SET body = :body WHERE id= :id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['body' => $body, 'id' => $id]);
*/


//Delete Data
/*
$id = 3;
$sql = 'DELETE FROM posts WHERE id= :id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
*/

//Search Data
//look for anything with "not" in it
$search = "%not%";
$sql = 'SELECT * FROM posts WHERE title LIKE ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$search]);
$posts = $stmt->fetchAll();
echo '<br> titles with "not" in them <br>';
foreach ($posts as $post){
    echo $post->title.'<br>';
}

//Limit Selection
$author = 'Mark';
$limit = 2;
//Need to do the following below to use LIMIT which is banned by default
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$sql = 'SELECT * FROM posts WHERE author = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$author, $limit]);
$posts = $stmt->fetchAll();

echo '<br> Show only 2 items by Mark <br>';
foreach ($posts as $post) {
    echo $post->title . ' by ' . $post->author . '<br>';
}
?>
