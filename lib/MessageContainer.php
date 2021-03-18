<?php /** @noinspection UnknownInspectionInspection */
/** @noinspection SlowArrayOperationsInLoopInspection */

/** @noinspection PhpUnused */

namespace eftec;

/**
 * Class MessageList
 *
 * @package       eftec
 * @author        Jorge Castro Castillo
 * @version       1.0 2021-03-17
 * @copyright (c) Jorge Castro C. mit License  https://github.com/EFTEC/MessageContainer
 * @see           https://github.com/EFTEC/MessageContainer
 */
class MessageContainer
{
    /** @var  MessageLocker[] Array of containers */
    public $items;
    /** @var int Number of errors stored globally */
    public $errorCount = 0;
    /** @var int Number of warnings stored globally */
    public $warningCount = 0;
    /** @var int Number of errors or warning stored globally */
    public $errorOrWarningCount = 0;
    /** @var int Number of information stored globally */
    public $infoCount = 0;
    /** @var int Number of success stored globally */
    public $successCount = 0;
    /** @var string[] Used to convert a type of message to a css class */
    public $cssClasses = ['error' => 'danger', 'warning' => 'warning', 'info' => 'info', 'success' => 'success'];
    private $firstError;
    private $firstWarning;
    private $firstInfo;
    private $firstSuccess;

    /**
     * MessageList constructor.
     */
    public function __construct()
    {
        $this->items = array();
    }

    /**
     * It resets all the container and flush all the results.
     */
    public function resetAll()
    {
        $this->errorCount = 0;
        $this->warningCount = 0;
        $this->errorOrWarningCount = 0;
        $this->infoCount = 0;
        $this->successCount = 0;
        $this->items = array();
        $this->firstError = null;
        $this->firstWarning = null;
        $this->firstInfo = null;
        $this->firstSuccess = null;
    }

    /**
     * You could add a message (including errors,warning..) and store it in a $idLocker
     *
     * @param string $idLocker Identified of the locker (where the message will be stored)
     * @param string $message  message to show. Example: 'the value is incorrect'
     * @param string $level    =['error','warning','info','success'][$i]
     */
    public function addItem($idLocker, $message, $level = 'error')
    {
        $idLocker = ($idLocker === '') ? '0' : $idLocker;
        if (!isset($this->items[$idLocker])) {
            $this->items[$idLocker] = new MessageLocker();
        }
        switch ($level) {
            case 'error':
                $this->errorCount++;
                $this->errorOrWarningCount++;
                if ($this->firstError === null) {
                    $this->firstError = $message;
                }
                $this->items[$idLocker]->addError($message);
                break;
            case 'warning':
                $this->warningCount++;
                $this->errorOrWarningCount++;
                if ($this->firstWarning === null) {
                    $this->firstWarning = $message;
                }
                $this->items[$idLocker]->addWarning($message);
                break;
            case 'info':
                $this->infoCount++;
                if ($this->firstInfo === null) {
                    $this->firstInfo = $message;
                }
                $this->items[$idLocker]->addInfo($message);
                break;
            case 'success':
                $this->successCount++;
                if ($this->firstSuccess === null) {
                    $this->firstSuccess = $message;
                }
                $this->items[$idLocker]->addSuccess($message);
                break;
        }
    }

    /**
     * It obtains all the ids for all the lockers.
     *
     * @return array
     */
    public function allIds()
    {
        return array_keys($this->items);
    }

    /**
     * It returns a MessageLocker containing an locker.<br>
     * <b>If the locker doesn't exist then it returns an empty object (not null)</b>
     *
     * @param string $idLocker Id of the locker
     *
     * @return MessageLocker
     */
    public function getMessage($idLocker)
    {
        $idLocker = ($idLocker === '') ? '0' : $idLocker;
        if (!isset($this->items[$idLocker])) {
            return new MessageLocker(); // we returns an empty error.
        }
        return $this->items[$idLocker];
    }

    /**
     * Alias of $this->getMessage()
     * @param string $idLocker Id of the locker
     * @return MessageLocker
     */
    public function get($idLocker) {
        return $this->getMessage($idLocker);
    }

    /**
     * It returns a css class associated with the type of errors inside a locker<br>
     * If the locker contains more than one message, then it uses the most severe one (error,warning,etc.)
     *
     * @param string $idLocker Id of the locker
     *
     * @return string
     */
    public function cssClass($idLocker)
    {
        $idLocker = ($idLocker === '') ? '0' : $idLocker;
        if (!isset($this->items[$idLocker])) {
            return '';
        }
        if (@$this->items[$idLocker]->countError()) {
            return $this->cssClasses['error'];
        }
        if ($this->items[$idLocker]->countWarning()) {
            return $this->cssClasses['warning'];
        }
        if ($this->items[$idLocker]->countInfo()) {
            return $this->cssClasses['info'];
        }
        if ($this->items[$idLocker]->countSuccess()) {
            return $this->cssClasses['success'];
        }
        return '';
    }

    /**
     * It returns the first message of error (if any)<br>
     * If not, then it returns the first message of warning (if any)
     *
     * @return string empty if there is none
     * @see \eftec\MessageContainer::firstErrorText
     */
    public function firstErrorOrWarning()
    {
        return $this->firstErrorText(true);
    }

    /**
     * It returns the first message of error (if any)
     *
     * @param bool $includeWarning if true then it also includes warning but any error has priority.
     * @return string empty if there is none
     */
    public function firstErrorText($includeWarning = false)
    {
        if ($includeWarning) {
            if ($this->errorCount) {
                return $this->firstError;
            }
            return ($this->warningCount === 0) ? '' : $this->firstWarning;
        }
        return ($this->errorCount === 0) ? '' : $this->firstError;
    }

    /**
     * It returns the first message of warning (if any)
     *
     * @return string empty if there is none
     */
    public function firstWarningText()
    {
        return ($this->warningCount === 0) ? '' : $this->firstWarning;
    }

    /**
     * It returns the first message of information (if any)
     *
     * @return string empty if there is none
     */
    public function firstInfoText()
    {
        return ($this->infoCount === 0) ? '' : $this->firstInfo;
    }

    /**
     * It returns the first message of success (if any)
     *
     * @return string empty if there is none
     */
    public function firstSuccessText()
    {
        return ($this->successCount === 0) ? '' : $this->firstSuccess;
    }

    /**
     * It returns an array with all messages of info of all lockers.
     *
     * @return string[] empty if there is none
     */
    public function allInfoArray()
    {
        $r = array();
        foreach ($this->items as $v) {
            $r = array_merge($r, $v->allInfo());
        }
        return $r;
    }

    /**
     * It returns an array with all messages of warning of all lockers.
     *
     * @return string[] empty if there is none
     */
    public function allWarningArray()
    {
        $r = array();
        foreach ($this->items as $v) {
            $r = array_merge($r, $v->allWarning());
        }
        return $r;
    }

    /**
     * It returns an array with all messages of success of all lockers.
     *
     * @return string[] empty if there is none
     */
    public function AllSuccessArray()
    {
        $r = array();
        foreach ($this->items as $v) {
            $r = array_merge($r, $v->allSuccess());
        }
        return $r;
    }

    /**
     * It returns an array with all messages of any type of all lockers
     *
     * @return string[] empty if there is none
     */
    public function allArray()
    {
        $r = array();
        foreach ($this->items as $v) {
            $r = array_merge($r, $v->allError());
            $r = array_merge($r, $v->allWarning());
            $r = array_merge($r, $v->allInfo());
            $r = array_merge($r, $v->allSuccess());
        }
        return $r;
    }

    /**
     * It returns an array with all messages of errors and warnings of all lockers.
     *
     * @return string[] empty if there is none
     * @see \eftec\MessageContainer::allErrorArray
     */
    public function allErrorOrWarningArray()
    {
        return $this->allErrorArray(true);
    }

    /**
     * It returns an array with all messages of error of all lockers.
     *
     * @param bool $includeWarning if true then it also include warnings.
     * @return string[] empty if there is none
     */
    public function allErrorArray($includeWarning = false)
    {
        if ($includeWarning) {
            $r = array();
            foreach ($this->items as $v) {
                $r = array_merge($r, $v->allError());
                $r = array_merge($r, $v->allWarning());
            }
            return $r;
        }
        $r = array();
        foreach ($this->items as $v) {
            $r = array_merge($r, $v->allError());
        }
        return $r;
    }

    /**
     * It returns true if there is an error (or error and warning).
     *
     * @param bool $includeWarning If true then it also returns if there is a warning
     * @return bool
     */
    public function hasError($includeWarning = false)
    {
        $tmp = $includeWarning
            ? $this->errorCount
            : $this->errorOrWarningCount;
        return $tmp !== 0;
    }
}