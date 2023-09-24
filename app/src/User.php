<?php
namespace App\src;
class User implements Model
{
    private string $name;
    private string $email;
    private string $password;
    protected AccessLevel $accessLevel;
    public function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setPassword(string $password)
    {
        $this->password = $password;
    }
    public function getPassword(): string
    {
        return $this->password;
    }

    public static function getModelName(): string{
        return 'user';
    }
    public function getAccessLevel() : AccessLevel {
        return $this->accessLevel;
    }
}
//Single Responsibility Principle
//Open-Closed principle (open for extension closed for modification)
//Liskov Substitution Principle
//Interface segregation principle. Keeping Minimal number of function in interface
//Depndency Inversion principle

//SOLID

//Encapsulate what varies