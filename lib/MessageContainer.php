<?php /** @noinspection PhpMissingParamTypeInspection */
/** @noinspection UnknownInspectionInspection */
/** @noinspection SlowArrayOperationsInLoopInspection */

/** @noinspection PhpUnused */

namespace eftec;

/**
 * Class MessageList
 *
 * @package       eftec
 * @author        Jorge Castro Castillo
 * @version       1.2 2021-03-21
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
    public function resetAll(): void
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
     * You could add a message (including errors,warning, etc.) and store it in a $idLocker
     *
     * @param string $idLocker Identified of the locker (where the message will be stored)
     * @param string $message  message to show. Example: 'the value is incorrect'.<br>
     *                         You can also use variables (if you are set a context). Ex: {{var1}} <br>
     *                         You can also show the idlocker. Ex: {{_idlocker}}<br>
     * @param string $level    =['error','warning','info','success'][$i]
     * @param array  $context  [optional] it is an associative array with the values of the item<br>
     *                         For optimization, the context is not update if exists another context.
     */
    public function addItem($idLocker, $message, $level = 'error', $context = null): void
    {
        $idLocker = ($idLocker === '') ? '0' : $idLocker;
        if (!isset($this->items[$idLocker])) {
            $this->items[$idLocker] = new MessageLocker($idLocker, $context);
        } else {
            $this->items[$idLocker]->setContext($context);
        }
        // if the message contains a curly braces, then it is convert using the context.
        $messageTransformed = $this->items[$idLocker]->replaceCurlyVariable($message);
        switch ($level) {
            case 'error':
                $this->errorCount++;
                $this->errorOrWarningCount++;
                if ($this->firstError === null) {
                    $this->firstError = $messageTransformed;
                }
                $this->items[$idLocker]->addError($message);
                break;
            case 'warning':
                $this->warningCount++;
                $this->errorOrWarningCount++;
                if ($this->firstWarning === null) {
                    $this->firstWarning = $messageTransformed;
                }
                $this->items[$idLocker]->addWarning($message);
                break;
            case 'info':
                $this->infoCount++;
                if ($this->firstInfo === null) {
                    $this->firstInfo = $messageTransformed;
                }
                $this->items[$idLocker]->addInfo($message);
                break;
            case 'success':
                $this->successCount++;
                if ($this->firstSuccess === null) {
                    $this->firstSuccess = $messageTransformed;
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
    public function allIds(): array
    {
        return array_keys($this->items);
    }

    /**
     * Alias of $this->getMessage()
     * @param string $idLocker ID of the locker
     * @return MessageLocker
     */
    public function get($idLocker): MessageLocker
    {
        return $this->getLocker($idLocker);
    }

    /**
     * It returns a MessageLocker containing a locker.<br>
     * <b>If the locker doesn't exist then it returns an empty object (not null)</b>
     *
     * @param string $idLocker ID of the locker
     *
     * @return MessageLocker
     */
    public function getLocker($idLocker = ''): MessageLocker
    {
        $idLocker = ($idLocker === '') ? '0' : $idLocker;
        return $this->items[$idLocker] ?? new MessageLocker($idLocker);
    }

    /**
     * It returns a css class associated with the type of errors inside a locker<br>
     * If the locker contains more than one message, then it uses the most severe one (error,warning,etc.)<br>
     * The method uses the field <b>$this->cssClasses</b>, so you can change the CSS classes.
     * <pre>
     * $this->clsssClasses=['error'=>'class-red','warning'=>'class-yellow','info'=>'class-green','success'=>'class-blue'];
     * $css=$this->cssClass('customerId');
     * </pre>
     *
     * @param string $idLocker ID of the locker
     *
     * @return string
     */
    public function cssClass($idLocker): string
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
     * It returns the first message of error or empty if none<br>
     * If not, then it returns the first message of warning or empty if none
     *
     * @param string $default if not message is found, then it returns this value
     * @return string empty if there is none
     * @see \eftec\MessageContainer::firstErrorText
     */
    public function firstErrorOrWarning($default = ''): string
    {
        return $this->firstErrorText($default, true);
    }

    /**
     * It returns the first message of error or empty if none
     *
     * @param string $default if not message is found, then it returns this value.
     * @param bool   $includeWarning if true then it also includes warning but any error has priority.
     * @return string empty if there is none
     */
    public function firstErrorText($default = '', $includeWarning = false): string
    {
        if ($includeWarning) {
            if ($this->errorCount) {
                return $this->firstError;
            }
            return ($this->warningCount === 0) ? $default : $this->firstWarning;
        }
        return ($this->errorCount === 0) ? $default : $this->firstError;
    }

    /**
     * It returns the first message of warning or empty if none
     *
     * @param string $default if not message is found, then it returns this value
     * @return string empty if there is none
     */
    public function firstWarningText($default = ''): string
    {
        return ($this->warningCount === 0) ? $default : $this->firstWarning;
    }

    /**
     * It returns the first message of information or empty if none
     *
     * @param string $default if not message is found, then it returns this value
     * @return string empty if there is none
     */
    public function firstInfoText($default = ''): string
    {
        return ($this->infoCount === 0) ? $default : $this->firstInfo;
    }

    /**
     * It returns the first message of success or empty if none
     *
     * @param string $default if not message is found, then it returns this value
     * @return string empty if there is none
     */
    public function firstSuccessText($default = ''): string
    {
        return ($this->successCount === 0) ? $default : $this->firstSuccess;
    }

    /**
     * It returns an array with all messages of any type of all lockers
     *
     * @param null|string $level =[null,'error','warning','errorwarning','info','success'][$i] the level to show.<br>
     *                           Null means it shows all errors
     * @return string[] empty if there is none
     */
    public function allArray($level = null): array
    {
        switch ($level) {
            case 'error':
                return $this->allErrorArray();
            case 'warning':
                return $this->allWarningArray();
            case 'errorwarning':
                return $this->allErrorOrWarningArray();
            case 'info':
                return $this->allInfoArray();
            case 'success':
                return $this->allSuccessArray();
        }
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
     * It returns an array with all messages of error of all lockers.
     *
     * @param bool $includeWarning if true then it also includes warnings.
     * @return string[] empty if there is none
     */
    public function allErrorArray($includeWarning = false): array
    {
        $r = array();
        if ($includeWarning) {
            foreach ($this->items as $v) {
                $r = array_merge($r, $v->allError());
                $r = array_merge($r, $v->allWarning());
            }
            return $r;
        }
        foreach ($this->items as $v) {
            $r = array_merge($r, $v->allError());
        }
        return $r;
    }

    /**
     * It returns an array with all messages of warning of all lockers.
     *
     * @return string[] empty if there is none
     */
    public function allWarningArray(): array
    {
        $r = array();
        foreach ($this->items as $v) {
            $r = array_merge($r, $v->allWarning());
        }
        return $r;
    }

    /**
     * It returns an array with all messages of errors and warnings of all lockers.
     *
     * @return string[] empty if there is none
     * @see \eftec\MessageContainer::allErrorArray
     */
    public function allErrorOrWarningArray(): array
    {
        return $this->allErrorArray(true);
    }

    /**
     * It returns an array with all messages of info of all lockers.
     *
     * @return string[] empty if there is none
     */
    public function allInfoArray(): array
    {
        $r = array();
        foreach ($this->items as $v) {
            $r = array_merge($r, $v->allInfo());
        }
        return $r;
    }

    /**
     * It returns an array with all messages of success of all lockers.
     *
     * @return string[] empty if there is none
     */
    public function AllSuccessArray(): array
    {
        $r = array();
        foreach ($this->items as $v) {
            $r = array_merge($r, $v->allSuccess());
        }
        return $r;
    }

    /**
     * It returns an associative array of the form <br>
     * <pre>
     * [
     *  ['id'=>'', // ID of the locker
     *  'level'=>'' // level of message (error, warning, info or success)
     *  'msg'=>'' // the message to show
     *  ]
     * ]
     * </pre>
     *
     * @param null|string $level
     * @return array
     */
    public function allAssocArray($level = null): array
    {
        $result = [];
        foreach ($this->items as $v) {
            if ($level === 'error' || $level === 'errorwarning' || $level === null) {
                $tmp = $v->allAssocArray('error');
                $result = array_merge($result, $tmp);
            }
            if ($level === 'warning' || $level === 'errorwarning' || $level === null) {
                $tmp = $v->allAssocArray('warning');
                $result = array_merge($result, $tmp);
            }
            if ($level === 'info' || $level === null) {
                $tmp = $v->allAssocArray('info');
                $result = array_merge($result, $tmp);
            }
            if ($level === 'success' || $level === null) {
                $tmp = $v->allAssocArray('success');
                $result = array_merge($result, $tmp);
            }
        }
        return $result;
    }

    /**
     * It returns true if there is an error (or error and warning).
     *
     * @param bool $includeWarning If true then it also returns if there is a warning
     * @return bool
     */
    public function hasError($includeWarning = false): bool
    {
        $tmp = $includeWarning
            ? $this->errorCount
            : $this->errorOrWarningCount;
        return $tmp !== 0;
    }
}
