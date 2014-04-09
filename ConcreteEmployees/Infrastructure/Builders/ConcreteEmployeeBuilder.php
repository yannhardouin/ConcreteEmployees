<?php

namespace ConcreteEmployees\Infrastructure\Builders;

use ConcreteEntities\Infrastructure\Builders\AbstractEntityBuilder;

use Employees\Domain\Employees\Builders\EmployeeBuilder;
use Booleans\Domain\Booleans\Adapters\BooleanAdapter;
use ObjectLoaders\Domain\ObjectLoaders\Adapters\ObjectLoaderAdapter;
use Entities\Domain\Entities\Builders\Exceptions\CannotBuildEntityException;
use Strings\Domain\Strings\String;
use Integers\Domain\Integers\Integer;

final class ConcreteEmployeeBuilder extends AbstractEntityBuilder implements EmployeeBuilder {
    private $firstName;
    private $lastName;
    private $number;
    
    public function __construct(BooleanAdapter $booleanAdapter, ObjectLoaderAdapter $objectLoaderAdapter)
    {
        parent::__construct($booleanAdapter,$objectLoaderAdapter, 'ConcreteEmployees\Infrastructure\Objects\ConcreteEmployee');
    }
    
    public function create()
    {
        parent::create();
        $this->firstName = null;
        $this->lastName = null;
        $this->number = null;
        return $this;
    }
    
    public function withFirstName(String $firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }
    
    public function withLastName(String $lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }
    
    public function withNumber(Integer $number)
    {
        $this->number = $number;
        return $this;
    }
    
    protected function getParamsData() {
        
        $paramsData = array($this->uuid, $this->firstName, $this->lastName, $this->number, $this->createdOn, $this->booleanAdapter);
        
        if (!empty($this->lastUpdatedOn)) {
            $paramsData[] = $this->lastUpdatedOn;
        }
        
        return $paramsData;
    }
    
    public function now() {
        
        if (empty($this->lastName)) {
            throw new CannotBuildEntityException('The last name is mandatory in order to build a Employee object.');
        }
        
        if (empty($this->firstName)) {
            throw new CannotBuildEntityException('The first name is mandatory in order to build a Employee object.');
        }
        
        if (empty($this->number)) {
            throw new CannotBuildEntityException('The number is mandatory in order to build a Employee object.');
        }
        return parent::now();
        
    }
    
}
