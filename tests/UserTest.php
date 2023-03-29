<?php

use Gyaaniguy\Upworktest\Model\Users\AbstractUser;
use Gyaaniguy\Upworktest\Model\Users\Guardians\AbstractGuardian;
use Gyaaniguy\Upworktest\Model\Users\Guardians\Teacher;
use Gyaaniguy\Upworktest\Model\Users\Student;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{

    public function testCreateStudent()
    {
        // This should not be hardcoded. In a real world scenario, this should be fetched from a config file.
        $defaultProfilePic = '/images/default-profile-pic.jpg';

        $email = 'nik.jain@gmail.com';
        $user = new Student(1, 'Nikhil', 'Jain', $email, '');
        $this->assertInstanceOf(Student::class, $user);
        $this->assertEquals('Nikhil Jain', $user->getFullName());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($defaultProfilePic, $user->getProfilePhoto());
        $this->assertEquals(1, $user->getUserId());
        $this->assertTrue($user->save());

        //set bad profile photo
        $user->setProfilePicture('not_a_image.txt');
        try {
            $user->save();
            self::fail("Should not save bad photo");
        } catch (Exception $e) {
            self::assertStringContainsStringIgnoringCase('Could not validate User', $e->getMessage());
        }
    }

    public function testTeacher()
    {
        $email = 'teacher.jain@gmail.com';
        $user = new Teacher(2, 'Teacher', 'Jain', $email, '');
        $user->setSalutation('Mr.');
        $this->assertInstanceOf(Teacher::class, $user);
        $this->assertInstanceOf(AbstractUser::class, $user);
        $this->assertInstanceOf(AbstractGuardian::class, $user); // Might be overkill ?

        $this->assertEquals('Mr. Teacher Jain', $user->getFullName());
        $this->assertEquals($email, $user->getEmail());
    }
}