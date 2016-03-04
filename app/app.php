<?php

    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../src/Author.php';
    require_once __DIR__.'/../src/Book.php';
    require_once __DIR__.'/../src/Copy.php';
    require_once __DIR__.'/../src/Patron.php';

    $server = 'mysql:host=localhost;dbname=library';
    $user = 'root';
    $password = 'root';
    $DB = new PDO($server, $user, $password);

    $app = new Silex\Application();

    $app->register(new Silex\Provider\TwigServiceProvider, array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function () use ($app) {
        return $app['twig']->render("index.html.twig", array(
            'books' => Book::getAll(),
            'copies' => Copy::getAll(),
            'patron' => Patron::getAll()
        ));
    });



    // Librarian
    // books
    $app->get("/librarian", function () use ($app) {
        return $app['twig']->render("librarian.html.twig", array(
            'books' => Book::getAll(),
            'copies' => Copy::getAll()
        ));
    });

    $app->post("/add_book", function () use ($app) {
        $stock = 0;
        $title = ucwords($_POST['title']);
        $genre = ucwords($_POST['genre']);
        $new_book = new Book($title, $genre);
        $new_book->save();
        $book_id = $new_book->getId();

        $due_date = '1970-01-01';
        $new_copy = new Copy($book_id, $id = null, $due_date, $available = 1);
        $new_copy->save();

        $new_book->addCopy($new_copy);
        $current_copies = $new_book->getCopies();

        if($book_id == $new_copy->getBookId()){
            $stock = $stock + 1;
        }
        var_dump($stock);
        return $app['twig']->render("librarian.html.twig", array(
            'stock' => $stock,
            'books' => Book::getAll(),
            'copies' => $current_copies
        ));
    });

    $app->get("/book/{id}", function ($id) use ($app) {
        $current_book = Book::find($id);
        $current_authors = $current_book->getAuthors();
        $current_copy = $current_book->getCopies();
        return $app['twig']->render("book.html.twig", array(
            'book' => $current_book,
            'current_authors' => $current_authors,
            'current_copies' => $current_copy
        ));
    });

    $app->get("/book/{id}", function ($id) use ($app) {
        $current_book = Book::find($id);
        $current_copies = $current_book->getCopies();
        $current_authors = $current_book->getAuthors();
        return $app['twig']->render("book.html.twig", array(
            'book' => $current_book,
            'authors' => $current_authors
        ));
    });

    $app->delete("/delete/book/{id}", function ($id) use ($app) {
        $current_book = Book::find($id);
        $current_book->deleteBook();

        return $app['twig']->render("librarian.html.twig", array(
            'book' => $current_book,
            'books' => Book::getAll()
        ));
    });

    // Authors
    $app->get("/book/{id}/add_author", function ($id) use ($app) {
        $current_book = Book::find($id);
        return $app['twig']->render("add_author.html.twig", array(
            'book' => $current_book
        ));
    });

    $app->post("/book/{id}/add_author", function ($id) use ($app) {
        $current_book = Book::find($id);
        $author = ucwords($_POST['author']);
        $new_author = new Author($author, $id);
        $new_author->save();
        $current_book->addAuthor($new_author);
        $current_authors = $current_book->getAuthors();
        return $app['twig']->render("book.html.twig", array(
            'book' => $current_book,
            'authors' => $current_authors
        ));
    });

    $app->delete("/book/{id}/delete/{author_id}", function ($id, $author_id) use ($app) {
        $current_book = Book::find($id);
        $current_author = Author::find($author_id);
        $current_author->deleteAuthor();
        return $app['twig']->render("book.html.twig", array(
            'book' => $current_book,
            'books' => Book::getAll(),
            'authors' => $current_book->getAuthors()
        ));
    });


    // Guest
    $app->get("/guest", function() use ($app) {
        return $app['twig']->render("guest.html.twig", array(
            'books' => Book::getAll(),
            'copies' => Copy::getAll()
        ));
    });

    $app->get("/guest/book/{id}/copy/{copy_id}", function($id, $copy_id) use ($app) {
        $current_book = Book::find($id);
        $current_copy = Copy::find($copy_id);
        return $app['twig']->render("guest_book.html.twig", array(
            'book' => $current_book,
            'copies' => Copy::getAll()
        ));
    });

    $app->post("/search", function () use ($app) {
        $error = 'No results found';
        $search_term = ucwords($_POST['search']);
        $books = Book::getAll();
        $authors = Author::getAll();
        $copies = Copy::getAll();
        var_dump($copies);

        $returned_books = array();
        $returned_authors = array();
        foreach($books as $book){
            if($book->getTitle() ==  $search_term || $book->getAuthors() == $search_term){
                array_push($returned_books, $book);
            }
        }
        if(!$returned_books){
            return $error;
        } else {
            foreach($returned_books as $book){
                $current_authors = $book->getAuthors();
                array_push($returned_authors, $current_authors);
            }
        }
        return $app['twig']->render("results.html.twig", array(
            'book' => $search_term,
            'error' => $error,
            'books' => $returned_books,
            'authors' => $returned_authors,
            'copies' => $copies
        ));
    });


    return $app;
?>
