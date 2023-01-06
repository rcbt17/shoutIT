<?php
class UserServices
{
    protected $database;
    protected $user;
    protected $username;
    protected $password;
    protected $passwordCheck;

    public function __construct(mysqli $database)
    {
        $this->database = $database;
    }

    public function isUserOnline(): bool
    {
        return isset($_SESSION['online']);
    }

    public function userLogin(string $username, string $password): bool
    {
        $this->username = $username;
        $this->password = $password;
        return $this->checkCredentials();
    }

    public function userRegister(string $fullName, string $username, string $email, string $password)
    {
        $registerErrors = [];

        if (!$this->isUsernameFree($username)) {
            $registerErrors[] = 'This username is already registered';
        }

        if (!$this->isEmailRegistered($email)) {
            $registerErrors[] = 'This email address is already registered';
        }

        if (empty($registerErrors)) {
            if($this->attemptRegister($fullName, $username, $email, $password)){
                return true;
            }
        }

        return $registerErrors;
    }

    protected function attemptRegister(string $fullName, string $username, string $email, string $password): bool
    {
        $currentUnixTime = time();
        $userClass = 'user';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->database->prepare('INSERT INTO users (username, full_name, email, password_hash, user_class, registration_date) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssss', $username, $fullName, $email, $hashedPassword, $userClass, $currentUnixTime);
        $result = $stmt->execute();
        if ($result) {
            return true;
        }
        return false;
    }

    protected function isUsernameFree(string $username): bool
    {
        $stmt = $this->database->prepare('SELECT username FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = mysqli_fetch_assoc($stmt->get_result());

        return !$result;
    }

    protected function isEmailRegistered(string $email): bool
    {
        $stmt = $this->database->prepare('SELECT username FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = mysqli_fetch_assoc($stmt->get_result());

        return !$result;
    }

    protected function checkCredentials(): bool
    {
        $stmt = $this->database->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->bind_param('s', $this->username);
        $stmt->execute();
        $result = mysqli_fetch_assoc($stmt->get_result());

        if ($result) {
            $hashedPassword = $result['password_hash'];
            if (password_verify($this->password, $hashedPassword)) {
                $_SESSION['online'] = 1;
                $this->user = $result;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}