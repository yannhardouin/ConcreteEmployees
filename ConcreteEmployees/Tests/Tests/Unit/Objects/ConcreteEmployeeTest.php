<?php

namespace ConcreteEmployees\Tests\Tests\Unit\Object;
use DateTimes\Tests\Helpers\DateTimeHelper;
use Strings\Tests\Helpers\StringHelper;
use Primitives\Tests\Helpers\PrimitiveHelper;
use Integers\Domain\Integers\Integer;
use ConcretEmployees\Infrastructure\Builders\ConcreteEmployeeBuilder;
use ConcreteEmployees\Infrastructure\Objects\ConcreteEmployee;

use Entities\Domain\Entities\Exceptions\CannotCreateEntityException;

final class ConcreteEmployeeTest extends \PHPUnit_Framework_TestCase {
    
    private $uuidMock;
    private $integerMock;
    private $stringMock;
    private $dateTimeMock;
    private $booleanAdapterMock;
    private $createdOnTimestampElement;
    private $lastUpdatedOnTimestampElement;
    private $firstNameElement;
    private $emptyFirstNameElement;
    private $lastNameElement;
    private $emptyLastNameElement;
    private $numberElement;
    private $emptyNumberElement;
    private $dateTimeHelper;
    private $stringHelper;
    private $integerHelper;
    
    public function setUp(){
        $this->uuidMock = $this->getMock('Uuids\Domain\Uuids\Uuid');
        $this->integerMock = $this->getMock('Integers\Domain\Integers\Integer');
        $this->stringMock = $this->getMock('Strings\Domain\Strings\String');
        $this->dateTimeMock = $this->getMock('DateTimes\Domain\DateTimes\DateTime');
        $this->booleanAdapterMock = $this->getMock('Booleans\Domain\Booleans\Adapters\BooleanAdapter');
        
        $this->createdOnTimestampElement = time() - (24 * 60 * 60);
        $this->lastUpdatedOnTimestampElement = time();
        $this->firstNameElement = 'This is a first name';
        $this->lastNameElement = 'This is a last name';
        $this->numberElement = 'This is a number';
        $this->emptyFirstNameElement = '';
        $this->emptyLastNameElement = '';
        $this->emptyNumberElement = '';
        
        $this->dateTimeHelper = new DateTimeHelper($this, $this->dateTimeMock);
        $this->stringHelper = new StringHelper($this, $this->stringMock);
        $this->integerHelper = new PrimitiveHelper($this, $this->integerMock);
    }
    
    public function tearDown() {
        
    }
    
    public function testCreate_Success(){
        $this->stringHelper->expectsGet_multiple_Success(array($this->firstNameElement, $this->lastNameElement));
        $this->integerHelper->expectsGet_multiple_Success(array($this->numberElement));
        
        $employee = new ConcreteEmployee($this->uuidMock, $this->stringMock, $this->stringMock, $this->integerMock, $this->dateTimeMock, $this->booleanAdapterMock);
        
        $this->assertEquals($this->uuidMock, $employee->getUuid());
        
        $this->assertEquals($this->stringMock, $employee->getFirstName());
        $this->assertEquals($this->stringMock, $employee->getLastName());
        $this->assertEquals($this->integerMock, $employee->getNumber());
        
        $this->assertEquals($this->dateTimeMock, $employee->createdOn());
        $this->assertNull($employee->lastUpdatedOn());
        
        $this->assertTrue($employee instanceof \Employees\Domain\Employees\Employee);
        $this->assertTrue($employee instanceof \ConcreteEntities\Infrastructure\Objects\AbstractEntity);
    }
    
    public function testCreate_withEmptyFirstName_throwsCannotCreateEntityException() {
        
        $this->stringHelper->expectsGet_Success($this->emptyFirstNameElement);
        
        $asserted = false;
        try {
        
            new ConcreteEmployee($this->uuidMock, $this->stringMock, $this->stringMock,$this->integerMock, $this->dateTimeMock, $this->booleanAdapterMock, $this->dateTimeMock);
            
        } catch (CannotCreateEntityException $exception) {
            $asserted = true;
        }
        
        $this->assertTrue($asserted);
        
    }
    
    public function testCreate_withEmptyLastName_throwsCannotCreateEntityException() {
        
        $this->stringHelper->expectsGet_multiple_Success(array($this->firstNameElement, $this->emptyLastNameElement));
        $this->integerHelper->expectsGet_multiple_Success(array($this->numberElement));
        
        $asserted = false;
        try {
        
            new ConcreteEmployee($this->uuidMock, $this->stringMock, $this->stringMock,$this->integerMock, $this->dateTimeMock, $this->booleanAdapterMock);
            
        } catch (CannotCreateEntityException $exception) {
            $asserted = true;
        }
        
        $this->assertTrue($asserted);
        
    }
    
    public function testCreate_withEmptyNumber_throwsCannotCreateEntityException() {
        $this->stringHelper->expectsGet_multiple_Success(array($this->firstNameElement, $this->lastNameElement));        
        $this->integerHelper->expectsGet_Success($this->emptyNumberElement);
        
        $asserted = false;
        try {
        
            new ConcreteEmployee($this->uuidMock, $this->stringMock, $this->stringMock,$this->integerMock, $this->dateTimeMock, $this->booleanAdapterMock);
            
        } catch (CannotCreateEntityException $exception) {
            $asserted = true;
        }
        
        $this->assertTrue($asserted);
        
    }
    
    public function testCreate_withLastUpdatedOn_Success() {
        
        $this->dateTimeHelper->expectsGetTimestamp_multiple_Success(array($this->integerMock, $this->integerMock));
        $this->integerHelper->expectsGet_multiple_Success(array($this->numberElement, $this->createdOnTimestampElement, $this->lastUpdatedOnTimestampElement));
        $this->stringHelper->expectsGet_multiple_Success(array($this->firstNameElement, $this->lastNameElement));
        
        $employee = new ConcreteEmployee($this->uuidMock, $this->stringMock, $this->stringMock, $this->integerMock, $this->dateTimeMock, $this->booleanAdapterMock, $this->dateTimeMock);
        
        $this->assertEquals($this->uuidMock, $employee->getUuid());
        $this->assertEquals($this->stringMock, $employee->getFirstName());
        $this->assertEquals($this->stringMock, $employee->getLastName());
        $this->assertEquals($this->integerMock, $employee->getNumber());
        $this->assertEquals($this->dateTimeMock, $employee->createdOn());
        $this->assertEquals($this->dateTimeMock, $employee->lastUpdatedOn());
        
    }
     
     
}

