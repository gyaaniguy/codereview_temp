<?php

namespace Gyaaniguy\Upworktest\Model;

use Exception;
use Gyaaniguy\Upworktest\Helpers\Enums;
use Gyaaniguy\Upworktest\Model\Users\AbstractUser;
use Gyaaniguy\Upworktest\Model\Users\Guardians\ParentOfStudent;
use Gyaaniguy\Upworktest\Model\Users\Guardians\Teacher;
use Gyaaniguy\Upworktest\Model\Users\Student;

class Message
{


    private AbstractUser $sender;
    private AbstractUser $receiver;
    private string $messageText;
    private int $creationTime;
    private string $messageType;

    public function __construct($sender, $receiver, $messageText, $messageType)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->messageText = $messageText;
        $this->messageType = $messageType;
        $this->creationTime = time();
    }

    /**
     * @return string
     */
    public function getMessageType(): string
    {
        return $this->messageType;
    }

    /**
     * @param string $messageType
     */
    public function setMessageType(string $messageType): void
    {
        $this->messageType = $messageType;
    }

    /**
     * @return string
     */
    public function getMessageText(): string
    {
        return $this->messageText;
    }

    public function getFormattedCreationTime()
    {
        return date('d M Y h:i A', $this->creationTime);
    }

    /**
     * @param int $senderId
     */
    public function setSender(AbstractUser $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @param AbstractUser $receiver
     */
    public function setReceiver(AbstractUser $receiver): void
    {
        $this->receiver = $receiver;
    }

    public function getSenderFullName(): string
    {
        return $this->sender->getFullName();
    }

    public function getReceiverFullName(): string
    {
        return $this->receiver->getFullName();
    }

    /**
     * @throws Exception
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            throw new Exception('Message is invalid');
        }
        //Proceed to actually attempt to send the message.
        return true;
    }

    public function validate(): bool
    {
        if ($this->messageType == Enums::MESSAGE_TYPE_SYSTEM && (!$this->sender instanceof Teacher || !$this->receiver instanceof Student)) {
            return false;
        }
        if (($this->sender instanceof Student || $this->sender instanceof ParentOfStudent) && !$this->receiver instanceof Teacher) {
            return false;
        }
        return true;
    }


}