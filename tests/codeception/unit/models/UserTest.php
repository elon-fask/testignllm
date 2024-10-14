<?php

namespace tests\codeception\unit\models;

use yii\codeception\TestCase;
use app\models\User;
use Codeception\Specify;

class UserTest extends TestCase
{
    //use Specify;
    
    protected function setUp()
    {
        parent::setUp();
        // uncomment the following to load fixtures for user table
        //$this->loadFixtures(['user']);
    }
    public function testValidateCorrectPassword()
    {
        $model = new User();
        $model->password = md5('samplepassword');
        /*
        $this->specify('should return true if the password is matching', function () use ($model) {
            expect('should return true if password is the same', $model->validatePassword('samplepassword'))->true();
        });
        */
        
        $this->assertTrue($model->validatePassword('samplepassword'));
    }
    
    public function testValidateIncorrectPassword()
    {
        $model = new User();
        $model->password = md5('samplepassword');
        /*
        $this->specify('should return false if the password is matching', function () use ($model) {
            expect('should return false if password is not the same', $model->validatePassword('test'))->false();
        });
        */
        $this->assertFalse($model->validatePassword('test'));
    }
    // TODO add test methods here
}
