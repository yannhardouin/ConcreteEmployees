<?php

namespace ConcreteEmployees\Infrastructure\Objects;
use ConcreteEntities\Infrastructure\Objects\AbstractEntity;
use Employees\Domain\Employees\Employee;
use Uuids\Domain\Uuids\Uuid;
use DateTimes\Domain\DateTimes\DateTime;
use Booleans\Domain\Booleans\Adapters\BooleanAdapter;
use Strings\Domain\Strings\String;
use Entities\Domain\Entities\Exceptions\CannotCreateEntityException;
use Integers\Domain\Integers\Integer;

/**
 * @ConcreteContainer("employee") 
 */

final class ConcreteEmployee extends AbstractEntity implements Employee {
    private $firstName;
    private $lastName;
    private $number;
    
    public function __construct(Uuid $uuid, String $firstName, String $lastName, Integer $number, DateTime $createdOn, BooleanAdapter $booleanAdapter, DateTime $lastUpdatedOn = null)
    {
        
        if ($firstName->get() == '') {
            throw new CannotCreateEntityException('The first name must be a non-empty String object.');
        }
        if ($lastName->get() == ''){
            throw new CannotCreateEntityException('The last name must be a non-empty String object.');
        }
        if($number->get() == ''){
            throw new CannotCreateEntityException('The number must be a non-empty Integer object.');
        }
        
        parent::__construct($uuid, $createdOn, $booleanAdapter,$lastUpdatedOn);
        
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->number = $number;
    }
    
    public function getFirstName() {
        return $this->firstName;
    }
    
    public function getLastName() {
        return $this->lastName;
    }
    
    public function getNumber() {
        return $this->number;
    }
}
