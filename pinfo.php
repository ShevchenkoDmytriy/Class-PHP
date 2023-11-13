<?php
abstract class User
{
    protected $name;
    protected $age;
    protected $issuedBooks = [];
    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getAge()
    {
        return $this->age;
    }
    public function issueBook($book)
    {
        $this->issuedBooks[] = $book;
    }
    public function returnBook($book)
    {
        $key = array_search($book, $this->issuedBooks);
        if ($key !== false) {
            unset($this->issuedBooks[$key]);
        }
    }
    public function getIssuedBooks()
    {
        return $this->issuedBooks;
    }
}
class Student extends User
{
    public function getMaximumBooks()
    {
        return 2; // Students can borrow up to 2 books
    }
}
class Teacher extends User
{
    public function getMaximumBooks()
    {
        return 5; // Teachers can borrow up to 5 books
    }
}
class Library
{
    private $availableBooks = [
        'Introduction to PHP' => 5,
        'Object-Oriented Programming' => 3,
        'Database Management' => 7,
    ];

    public function issueBook(User $user, $book)
    {
        if (!array_key_exists($book, $this->availableBooks) || $this->availableBooks[$book] <= 0) {
            echo "Book '$book' is not available for issue.\n";
            return;
        }

        if (count($user->getIssuedBooks()) >= $user->getMaximumBooks()) {
            echo $user->getName() . " has reached the maximum limit for issued books.\n";
            return;
        }

        $user->issueBook($book);
        $this->availableBooks[$book]--;
        echo $user->getName() . " has issued the book '$book'.\n";
    }

    public function returnBook(User $user, $book)
    {
        $user->returnBook($book);
        $this->availableBooks[$book]++;
        echo $user->getName() . " has returned the book '$book'.\n";
    }

    public function getUserType(User $user)
    {
        if ($user instanceof Teacher) {
            return new TeacherType();
        } elseif ($user instanceof Student) {
            return new StudentType();
        } else {
            return new UserType();
        }
    }
}
class UserType
{
    public function canIssueBooks()
    {
        return false;
    }
}

class StudentType extends UserType
{
    public function canIssueBooks()
    {
        return true;
    }
}

class TeacherType extends UserType
{
    public function canIssueBooks()
    {
        return true;
    }
}
$library = new Library();
$student = new Student('Alice', 16);
$teacher = new Teacher('Bob', 30);
$library->issueBook($student, 'Introduction to PHP');
$library->issueBook($teacher, 'Object-Oriented Programming');
echo "\n";
print_r($student->getIssuedBooks());
print_r($teacher->getIssuedBooks());
$library->returnBook($student, 'Introduction to PHP');
$library->returnBook($teacher, 'Object-Oriented Programming');
echo "\n";
print_r($student->getIssuedBooks());
print_r($teacher->getIssuedBooks());
?>
