<?php declare(strict_types=1);
namespace App\src;

class CustomerApp
{
    private CustomerAccountManager $customerAccountManager;
    private Authentication $authentication;

    private const LOGIN = 1;
    private const REGISTER = 2;
    private const EXIT_APP = 3;

    private const ALL_TRANSACTIONS = 1;
    private const DEPOSIT = 2;
    private const WITHDRAW = 3;
    private const TRANSFER = 4;
    private const BALANCE = 5;
    private const EXIT = 6;
    private ?Customer $customer = null;

    private array $options = [
        self::LOGIN => 'Login',
        self::REGISTER => 'Register',
        self::EXIT_APP => 'Exit'
    ];

    private array $customerOptions = [
        self::ALL_TRANSACTIONS => 'View all transactions',
        self::DEPOSIT => 'Deposit money',
        self::WITHDRAW => 'Withdraw money',
        self::TRANSFER => 'Transfer money',
        self::BALANCE => 'Account balance',
        self::EXIT => 'Exit',
    ];

    public function __construct()
    {
        $this->authentication = new Authentication(new FileStorage());
    }

    public function run(): void
    {
        if ($this->customer == null) {
            foreach ($this->options as $option => $label) {
                printf("%d. %s\n", $option, $label);
            }
            $choice = intval(readline("Enter your option: "));
            switch ($choice) {
                case self::LOGIN:
                    $email = trim(readline("Enter email address: "));
                    $password = trim(readline("Enter password: "));
                    $this->customer = $this->authentication->login($email, $password,AccessLevel::CUSTOMER);
                    if ($this->customer == null) {
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
                    $customer = new Customer($name, $email, $password);
                    $this->customer = $this->authentication->register($customer);
                    if ($this->customer == null) {
                        echo ("User already exists\n");
                        $userAuthenticated = false;
                    } else {
                        echo ("User Registered\n");
                        $userAuthenticated = true;
                    }
                    $this->run();
                    break;
                case self::EXIT_APP:
                    return;
            }
        } else {
            $this->customerAccountManager = new CustomerAccountManager(new FileStorage(),$this->customer);

            while (true) {
                foreach ($this->customerOptions as $option => $label) {
                    printf("%d. %s\n", $option, $label);
                }

                $choice = intval(readline("Enter your option: "));

                switch ($choice) {
                    case self::ALL_TRANSACTIONS:
                        $this->customerAccountManager->viewTransactions();
                        break;
                    case self::DEPOSIT:
                        $amount = (float)trim(readline("Enter deposit amount: "));
                        $this->customerAccountManager->deposit($amount);
                        break;
                    case self::WITHDRAW:
                        $amount = (float)trim(readline("Enter withdraw amount: "));
                        if($this->customer->getCustomerBalance()<$amount){
                            echo "Insufficient Account balance\n";
                        }else{
                            $this->customerAccountManager->withdraw($amount);
                        }
                        
                        break;
                    case self::TRANSFER:
                        $recepientEmail = trim(readline("Enter receipient email: "));
                        $amount = (float)trim(readline("Enter transfer amount: "));
                        $this->customerAccountManager->transferMoney($recepientEmail,$amount );
                        break;
                    case self::BALANCE:
                        $this->customerAccountManager->accountBalance();
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
