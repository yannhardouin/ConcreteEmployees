<?php

namespace ConcreteEmployees\Tests\Tests\Unit\Builders;
use DateTimes\Tests\Helpers\DateTimeHelper;
use Strings\Tests\Helpers\StringHelper;
use Primitives\Tests\Helpers\PrimitiveHelper;
use ConcreteEmployees\Infrastructure\Builders\ConcreteEmployeeBuilder;
use ObjectLoaders\Tests\Helpers\ObjectLoaderAdapterHelper;
use ObjectLoaders\Tests\Helpers\ObjectLoaderHelper;

use Entities\Domain\Entities\Builders\Exceptions\CannotBuildEntityException;


final class ConcreteEmployeeBuilderTest extends \PHPUnit_Framework_TestCase {
    private $objectLoaderAdapterMock;
    private $objectLoaderMock;
    private $uuidMock;
    private $integerMock;
    private $stringMock;
    private $dateTimeMock;
    private $booleanAdapterMock;
    private $employeeMock;
    private $classNameElement;
    private $createdOnTimestampElement;
    private $lastUpdatedOnTimestampElement;
    private $firstNameElement;
    private $emptyFirstNameElement;
    private $lastNameElement;
    private $emptyLastNameElement;
    private $numberElement;
    private $emptyNumberElement;
    private $builder;
    private $objectLoaderAdapterHelper;
    private $objectLoaderHelper;
    private $dateTimeHelper;
    private $stringHelper;
    private $integerHelper;
    
    public function setUp() {
        
        $this->objectLoaderAdapterMock = $this->getMock('ObjectLoaders\Domain\ObjectLoaders\Adapters\ObjectLoaderAdapter');
        $this->objectLoaderMock = $this->getMock('ObjectLoaders\Domain\ObjectLoaders\ObjectLoader');
        $this->uuidMock = $this->getMock('Uuids\Domain\Uuids\Uuid');
        $this->integerMock = $this->getMock('Integers\Domain\Integers\Integer');
        $this->stringMock = $this->getMock('Strings\Domain\Strings\String');
        $this->dateTimeMock = $this->getMock('DateTimes\Domain\DateTimes\DateTime');
        $this->booleanAdapterMock = $this->getMock('Booleans\Domain\Booleans\Adapters\BooleanAdapter');
        $this->employeeMock = $this->getMock('Employees\Domain\Employees\Employee');
        
        $this->classNameElement = 'ConcreteEmployees\Infrastructure\Objects\ConcreteEmployee';
        $this->createdOnTimestampElement = time() - (24 * 60 * 60);
        $this->lastUpdatedOnTimestampElement = time();
        $this->firstNameElement = 'This is a first name';
        $this->lastNameElement = 'This is a last name';
        $this->numberElement = 'This is a number';
        $this->emptyLastNameElement = '';
        $this->emptyFirstNameElement = '';
        $this->emptyNumberElement = '';
        
        $this->builder = new ConcreteEmployeeBuilder($this->booleanAdapterMock, $this->objectLoaderAdapterMock);
        
        $this->objectLoaderAdapterHelper = new ObjectLoaderAdapterHelper($this, $this->objectLoaderAdapterMock);
        $this->objectLoaderHelper = new ObjectLoaderHelper($this, $this->objectLoaderMock);
        $this->dateTimeHelper = new DateTimeHelper($this, $this->dateTimeMock);
        $this->stringHelper = new StringHelper($this, $this->stringMock);
        $this->integerHelper = new PrimitiveHelper($this, $this->integerMock);
        
    }
    
    
    public function tearDown() {
        
    }
    
    public function testBuild_Success() {
        
        $this->objectLoaderAdapterHelper->expects_convertClassNameElementToObjectLoader_Success($this->objectLoaderMock, $this->classNameElement);
        $this->objectLoaderHelper->expects_instantiate_Success($this->employeeMock, array($this->uuidMock, $this->stringMock, $this->stringMock,  $this->integerMock, $this->dateTimeMock, $this->booleanAdapterMock));
        $this->stringHelper->expectsGet_multiple_Success(array($this->firstNameElement, $this->lastNameElement));
        $this->integerHelper->expectsGet_multiple_Success(array($this->numberElement));
        
        $employee = $this->builder->create()
                                        ->withUuid($this->uuidMock)
                                        ->withFirstName($this->stringMock)
                                        ->withLastName($this->stringMock)
                                        ->withNumber($this->integerMock)
                                        ->createdOn($this->dateTimeMock)                                        
                                        ->now();
        
        $this->assertEquals($this->employeeMock, $employee);
        
    }
    
    public function testBuild_throwsCannotInstantiateObjectException_throwsCannotBuildEntityException() {
        
        $this->objectLoaderAdapterHelper->expects_convertClassNameElementToObjectLoader_Success($this->objectLoaderMock, $this->classNameElement);
        $this->objectLoaderHelper->expects_instantiate_throwsCannotInstantiateObjectException(array($this->uuidMock, $this->stringMock, $this->stringMock,  $this->integerMock, $this->dateTimeMock, $this->booleanAdapterMock));
        
        $asserted = false;
        try {
        
            $this->builder->create()
                            ->withUuid($this->uuidMock)
                            ->withFirstName($this->stringMock)
                            ->withLastName($this->stringMock)
                            ->withNumber($this->integerMock)
                            ->createdOn($this->dateTimeMock)
                            ->now();
            
        } catch (CannotBuildEntityException $exception) {
            $asserted = true;
        }
        
        $this->assertTrue($asserted);
        
    }
    
    public function testBuild_throwsCannotConvertClassNameElementToObjectLoaderException_throwsCannotBuildEntityException() {
        
        $this->objectLoaderAdapterHelper->expects_convertClassNameElementToObjectLoader_throwsCannotConvertClassNameElementToObjectLoaderException($this->classNameElement);
        
        $asserted = false;
        try {
        
            $this->builder->create()
                            ->withUuid($this->uuidMock)
                            ->withFirstName($this->stringMock)
                            ->withLastName($this->stringMock)
                            ->withNumber($this->integerMock)
                            ->createdOn($this->dateTimeMock)
                            ->now();
            
        } catch (CannotBuildEntityException $exception) {
            $asserted = true;
        }
        
        $this->assertTrue($asserted);
        
    }
    
    
    public function testBuild_withoutFirstName_throwsCannotBuildEntityException() {
        
        $asserted = false;
        try {
        
            $this->builder->create()
                            ->withUuid($this->uuidMock)
                            ->withLastName($this->stringMock)
                            ->withNumber($this->integerMock)
                            ->createdOn($this->dateTimeMock)
                            ->now();
            
        } catch (CannotBuildEntityException $exception) {
            $asserted = true;
        }
        
        $this->assertTrue($asserted);
        
    }
    
    public function testBuild_withoutLastName_throwsCannotBuildEntityException() {
        
        $asserted = false;
        try {
        
            $this->builder->create()
                            ->withUuid($this->uuidMock)
                            ->withFirstName($this->stringMock)
                            ->withNumber($this->integerMock)
                            ->createdOn($this->dateTimeMock)
                            ->now();
            
        } catch (CannotBuildEntityException $exception) {
            $asserted = true;
        }
        
        $this->assertTrue($asserted);
        
    }
    
    public function testBuild_withoutNumber_throwsCannotBuildEntityException() {
        
        $asserted = false;
        try {
        
            $this->builder->create()
                            ->withUuid($this->uuidMock)
                            ->withFirstName($this->stringMock)
                            ->withLastName($this->stringMock)
                            ->createdOn($this->dateTimeMock)
                            ->now();
            
        } catch (CannotBuildEntityException $exception) {
            $asserted = true;
        }
        
        $this->assertTrue($asserted);
        
    }
    
    public function testBuild_withLastUpdatedOn_Success() {
        
        $this->objectLoaderAdapterHelper->expects_convertClassNameElementToObjectLoader_Success($this->objectLoaderMock, $this->classNameElement);
        $this->objectLoaderHelper->expects_instantiate_Success($this->employeeMock, array($this->uuidMock, $this->stringMock, $this->stringMock,  $this->integerMock, $this->dateTimeMock, $this->booleanAdapterMock, $this->dateTimeMock));
        $this->stringHelper->expectsGet_multiple_Success(array($this->firstNameElement, $this->lastNameElement));
        $this->integerHelper->expectsGet_multiple_Success(array($this->numberElement));
        
        $employee = $this->builder->create()
                                        ->withUuid($this->uuidMock)
                                        ->withFirstName($this->stringMock)
                                        ->withLastName($this->stringMock)
                                        ->withNumber($this->integerMock)
                                        ->createdOn($this->dateTimeMock)
                                        ->lastUpdatedOn($this->dateTimeMock)
                                        ->now();
        
        $this->assertEquals($this->employeeMock, $employee);
       
    }
    
}
