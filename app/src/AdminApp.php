<?php

declare(strict_types=1);

namespace App\src;

class AdminApp
{
    private AdminAccountManager $adminAccountManager;
    private Authentication $authentication;

    private const LOGIN = 1;
    private const REGISTER = 2;
    private const EXIT_APP = 3;

    private const ALL_TRANSACTIONS = 1;
    private const SPECIFIC_CUSTOMER_TRANSACTIONS = 2;
    private const ALL_CUSTOMERS = 3;
    private const EXIT = 4;
    private ?Admin $admin = null;

    private array $options = [
        self::LOGIN => 'Login',
        self::REGISTER => 'Register',
        self::EXIT_APP => 'Exit'
    ];

    private array $customerOptions = [
        self::ALL_TRANSACTIONS => 'View all transactions',
        self::SPECIFIC_CUSTOMER_TRANSACTIONS => 'View specific customers transaction',
        self::ALL_CUSTOMERS => 'View all customer',
        self::EXIT => 'Exit',
    ];

    public function __construct()
    {
        $this->authentication = new Authentication(new FileStorage());
    }

    public function run(): void
    {
        if ($this->admin == null) {
            foreach ($this->options as $option => $label) {
                printf("%d. %s\n", $option, $label);
            }
            $choice = intval(readline("Enter your option: "));
            switch ($choice) {
                case self::LOGIN:
                    $email = trim(readline("Enter email address: "));
                    $password = trim(readline("Enter password: "));
                    $this->admin = $this->authentication->login($email, $password, AccessLevel::ADMIN);
                    if ($this->admin == null) {
                        echo ("Invalid email or password\n");
                    } else {
                        echo ("Logged in successfully\n");
                    }

                    $this->run();
                    break;

                case self::REGISTER:
                    $name = trim(readline("Enter your name: "));
                    $email = trim(readline("Enter email address: "));
                    $password = trim(readline("Enter password: "));
                    $admin = new Admin($name, $email, $password);
                    $this->admin = $this->authentication->register($admin);
                    if ($this->admin == null) {
                        echo ("Admin already exists\n");
                        $userAuthenticated = false;
                    } else {
                        echo ("Admin Registered\n");
                        $userAuthenticated = true;
                    }
                    $this->run();
                    break;
                case self::EXIT_APP:
                    return;
            }
        } else {
            $this->adminAccountManager = new AdminAccountManager(new FileStorage(), $this->admin);

            while (true) {
                foreach ($this->customerOptions as $option => $label) {
                    printf("%d. %s\n", $option, $label);
                }

                $choice = intval(readline("Enter your option: "));

                switch ($choice) {
                    case self::ALL_TRANSACTIONS:
                        $this->adminAccountManager->viewTransactions();
                        break;
                    case self::SPECIFIC_CUSTOMER_TRANSACTIONS:
                        $email = trim(readline("Enter email address: "));
                        $this->adminAccountManager->viewTransactions($email);
                        break;
                    case self::ALL_CUSTOMERS:
                        $this->adminAccountManager->viewCustomers();
                        break;
                    case self::EXIT:
                        return;
                    default:
                        echo "Invalid option.\n";
                }
            }
        }
    }
}
