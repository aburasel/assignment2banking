<?php
namespace App\src;
class Authentication implements Model
{
    private array $registeredUsers;
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
        $this->registeredUsers = $this->storage->load(User::getModelName());
    }
    protected User $user;
    protected Storage $storage;

    public function login(string $email, string $password, AccessLevel $accessLevel): ?User
    {
        //$registeredUsers = $this->storage->load($this->getModelName());
        // echo "<pre>";
        // print_r($this->registeredUsers);
        // echo "</pre>";
        foreach ($this->registeredUsers as $reg) {
            if ($email == $reg->getEmail() && $password == $reg->getPassword() && $accessLevel == $reg->getAccessLevel() ) {
               return $reg;
            }
        }   

        return null;
    }

    public function register(User $user): ?User
    {
        $userExist = $this->isUserExist($user);
        if ($userExist) {
            return null;
        } else {
            $this->registeredUsers[] = $user; // array push
            $this->storage->save($this->getModelName(), $this->registeredUsers);
            return $user;
        }
    }

    private function isUserExist(User $user): bool
    {
        $registeredUsers = $this->storage->load($this->getModelName());
        foreach ($registeredUsers as $reg) {
            if ($user->getEmail() == $reg->getEmail()) {
                return true;
            }
        }
        return false;
    }

    public static function getModelName(): string
    {
        return 'user';
    }
}
