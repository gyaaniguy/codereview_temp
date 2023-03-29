<?php

use Gyaaniguy\Upworktest\Helpers\Enums;
use Gyaaniguy\Upworktest\Model\Message;
use Gyaaniguy\Upworktest\Model\Users\Guardians\ParentOfStudent;
use Gyaaniguy\Upworktest\Model\Users\Guardians\Teacher;
use Gyaaniguy\Upworktest\Model\Users\Student;
use Gyaaniguy\Upworktest\Model\Users\AbstractUser;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{

    public function testParentMessaging()
    {
        $student = $this->_getStudent();
        $parentOfStudent = $this->_getParentOfStudent();
        $teacher = $this->_getTeacher();
        $message = $this->_makeMessage($parentOfStudent, $teacher);
        $this->assertTrue($message->save());

        // Parent should fail to send system message
        $message->setMessageType(Enums::MESSAGE_TYPE_SYSTEM);
        try {
            $message->save();
            self::fail("Parent can't send SYSTEM message");
        } catch (Exception $e) {
            self::assertStringContainsStringIgnoringCase('Message is invalid', $e->getMessage());
        }
        // Can send manual message to teacher
        $message->setMessageType(Enums::MESSAGE_TYPE_MANUAL);
        $this->assertTrue($message->save());

        // but not to student.
        $message->setReceiver($student);
        try {
            $message->save();
            self::fail("Student can't send message to parents");
        } catch (Exception $e) {
            self::assertStringContainsStringIgnoringCase('Message is invalid', $e->getMessage());
        }
    }

    /**
     * @return Student
     */
    private function _getStudent(): Student
    {
        return new Student(1, 'Nikhil', 'Jain', 'nik.jain@gmail.com');
    }

    /**
     * @return ParentOfStudent
     */
    public function _getParentOfStudent(): ParentOfStudent
    {
        return new ParentOfStudent(1, 'father name', 'Jain', 'father@gmail.com');
    }

    public function _makeMessage(AbstractUser $parentOfStudent, AbstractUser $student, string $messageText = 'My child!'): Message
    {
        return new Message($parentOfStudent, $student, $messageText, Enums::MESSAGE_TYPE_MANUAL);
    }

    public function testStudentMessaging()
    {
        $student = $this->_getStudent();
        $parentOfStudent = $this->_getParentOfStudent();
        $teacher = $this->_getTeacher();
        $message = $this->_makeMessage($student, $teacher);
        $this->assertTrue($message->save());
        
        // Parent should fail to send system message
        $message->setMessageType(Enums::MESSAGE_TYPE_SYSTEM);
        try {
            $message->save();
            self::fail("Student can't send SYSTEM message");
        } catch (Exception $e) {
            self::assertStringContainsStringIgnoringCase('Message is invalid', $e->getMessage());
        }
        // Can send manual message
        $message->setMessageType(Enums::MESSAGE_TYPE_MANUAL);
        $this->assertTrue($message->save());

        // but not to parents.
        $message->setReceiver($parentOfStudent);
        try {
            $message->save();
            self::fail("Student can't send message to parents");
        } catch (Exception $e) {
            self::assertStringContainsStringIgnoringCase('Message is invalid', $e->getMessage());
        }

    }

    public function testTeacherMessaging()
    {
        $teacher = $this->_getTeacher();
        $parentOfStudent = $this->_getParentOfStudent();
        $message = $this->_makeMessage($teacher, $parentOfStudent);
        $this->assertTrue($message->save());

        $message->setMessageType(Enums::MESSAGE_TYPE_SYSTEM);
        try {
            $message->save();
            self::fail("Teacher can't send SYSTEM message to Parent");
        } catch (Exception $e) {
            self::assertStringContainsStringIgnoringCase('Message is invalid', $e->getMessage());
        }

        //Teacher Can send SYSTEM message to Student
        $student = $this->_getStudent();
        $message->setReceiver($student);
        $message->setMessageType(Enums::MESSAGE_TYPE_SYSTEM);
        $this->assertTrue($message->save());
        $message->setMessageType(Enums::MESSAGE_TYPE_MANUAL);
        $this->assertTrue($message->save());
    }

    /**
     * @return Teacher
     */
    public function _getTeacher(): Teacher
    {
        return new Teacher(1, 'Teacher', 'Jain', 'teacher@gmail.com');
    }

    public function testFullName()
    {
        $teacher = $this->_getTeacher();
        $parentOfStudent = $this->_getParentOfStudent();
        $message = $this->_makeMessage($teacher, $parentOfStudent);
        $this->assertNotEmpty($message->getSenderFullName());
        $this->assertNotEmpty($message->getReceiverFullName());
        $this->assertIsString($message->getReceiverFullName());
        $this->assertIsString($message->getSenderFullName());
        $this->assertEquals($message->getSenderFullName(), $teacher->getFullName());
        $this->assertEquals($message->getReceiverFullName(), $parentOfStudent->getFullName());
    }

    public function testMessageOptionsValidity()
    {
        $teacher = $this->_getTeacher();
        $parentOfStudent = $this->_getParentOfStudent();
        $msg = 'hire me already';
        $message = $this->_makeMessage($teacher, $parentOfStudent, $msg);
        // Test Message Text
        $this->assertEquals($msg, $message->getMessageText());
        // Test Message Type
        $this->assertEquals(Enums::MESSAGE_TYPE_MANUAL, $message->getMessageType());
        // Test changing Message type
        $message->setMessageType(Enums::MESSAGE_TYPE_SYSTEM);
        $this->assertEquals(Enums::MESSAGE_TYPE_SYSTEM, $message->getMessageType());

        // Test time
        $this->assertIsString($message->getFormattedCreationTime());
        // Time should contain year - 202'3. 202'4 etc
        $this->assertStringContainsString('202', $message->getFormattedCreationTime());
    }
}