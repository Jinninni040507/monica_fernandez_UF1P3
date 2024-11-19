<?php namespace ComBank\Bank;
    
    use ComBank\Support\Traits\ApiTrait;
    
    class Person{
        use ApiTrait;
    // Class Atributes
        private $name;
        private $idCard;
        private $email;

    // Constructor
    public function __construct(string $newName, string $newIdCard, string $newEmail){
        $this->name = $newName;
        $this->idCard = $newIdCard;
        $this->email = $this->validateEmail($newEmail);
    }

    // GETTERS & SETTERS

        // NAME
        /**
         * Get the value of name
         */ 
        public function getName()
        {
                return $this->name;
        }

        /**
         * Set the value of name
         *
         * @return  self
         */ 
        public function setName($name)
        {
                $this->name = $name;

                return $this;
        }

        
        // ID CARD
        /**
         * Get the value of idCard
         */ 
        public function getIdCard()
        {
                return $this->idCard;
        }
        
        // EMAIL
        /**
         * Get the value of email
         */ 
        public function getEmail()
        {
                return $this->email;
        }

        /**
         * Set the value of email
         *
         * @return  self
         */ 
        public function setEmail($email)
        {
                $this->email = $email;

                return $this;
        }
    }

?>