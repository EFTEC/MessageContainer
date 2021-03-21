<?php /** @noinspection UnknownInspectionInspection */

/** @noinspection PhpUnused */

namespace eftec;

/**
 * Class MessageLocker
 *
 * @package       eftec
 * @author        Jorge Castro Castillo
 * @version       1.2 2021-03-21
 * @copyright (c) Jorge Castro C. MIT License  https://github.com/EFTEC/MessageContainer
 * @see           https://github.com/EFTEC/MessageContainer
 */
class MessageLocker
{
    /**
     * @var array It is an associative array with the context of the locker.<br>
     *            The context is only set once, it is for optimization. So, if the contexts contains information
     *            (not null) then it is not updated.
     */
    private $context;
    /** @var mixed|null The id of the locker */
    private $idLocker;
    /** @var string[] */
    private $errorMsg;
    /** @var string[] */
    private $warningMsg;
    /** @var string[] */
    private $infoMsg;
    /** @var string[] */
    private $successMsg;

    /**
     * MessageLocker constructor.
     * @param null|string $idLocker
     * @param array|null  $context
     */
    public function __construct($idLocker = null, &$context = null)
    {
        $this->idLocker = $idLocker;
        $this->errorMsg = [];
        $this->warningMsg = [];
        $this->infoMsg = [];
        $this->successMsg = [];
        $this->setContext($context);
    }

    /**
     * We set the context only if the current context is null.
     *
     * @param array|null $context The new context.
     */
    public function setContext(&$context)
    {
        if ($this->context === null) {
            $this->context =& $context;
        }
    }

    /**
     * It adds an error to the locker.
     *
     * @param mixed $msg The message to store
     */
    public function addError($msg)
    {
        $this->errorMsg[] = $this->replaceCurlyVariable($msg);
    }

    /**
     * Replaces all variables defined between {{ }} by a variable inside the dictionary of values.<br>
     * Example:<br>
     *      replaceCurlyVariable('hello={{var}}',['var'=>'world']) // hello=world<br>
     *      replaceCurlyVariable('hello={{var}}',['varx'=>'world']) // hello=<br>
     *      replaceCurlyVariable('hello={{var}}',['varx'=>'world'],true) // hello={{var}}<br>
     *
     * @param string $string The input value. It could contains variables defined as {{namevar}}
     * @return string|null
     * @see https://github.com/EFTEC/mapache-commons
     */
    public function replaceCurlyVariable($string)
    {
        if (strpos($string, '{{') === false) {
            return $string; // nothing to replace.
        }
        $string = str_replace('{{_idlocker}}', $this->idLocker, $string);
        return preg_replace_callback('/{{\s?(\w+)\s?}}/u', function ($matches) {
            if (is_array($matches)) {
                $item = substr($matches[0], 2, -2); // removes {{ and }}
                return isset($this->context[$item]) ? $this->context[$item] : '';
            }
            $item = substr($matches, 2, -2); // removes {{ and }}
            if (isset($this->context[$item])) {
                return $this->context[$item];
            }
            return '';
        }, $string);
    }

    /**
     * It adds a warning to the locker.
     *
     * @param mixed $msg The message to store
     */
    public function addWarning($msg)
    {
        $this->warningMsg[] = $this->replaceCurlyVariable($msg);
    }

    /**
     * It adds an information to the locker.
     *
     * @param mixed $msg The message to store
     */
    public function addInfo($msg)
    {
        $this->infoMsg[] = $this->replaceCurlyVariable($msg);
    }

    /**
     * It adds a success to the locker.
     *
     * @param mixed $msg The message to store
     */
    public function addSuccess($msg)
    {
        $this->successMsg[] = $this->replaceCurlyVariable($msg);
    }

    /**
     * It returns the number of errors or warnings contained in the locker
     *
     * @return int
     */
    public function countErrorOrWarning()
    {
        return $this->countError() + $this->countWarning();
    }

    /**
     * It returns the number of errors contained in the locker
     *
     * @return int
     */
    public function countError()
    {
        return count($this->errorMsg);
    }

    /**
     * It returns the number of warnings contained in the locker
     *
     * @return int
     */
    public function countWarning()
    {
        return count($this->warningMsg);
    }

    /**
     * It returns the number of infos contained in the locker
     *
     * @return int
     */
    public function countInfo()
    {
        return count($this->infoMsg);
    }

    /**
     * It returns the number of successes contained in the locker
     *
     * @return int
     */
    public function countSuccess()
    {
        return count($this->successMsg);
    }

    /**
     * It returns the first message of any kind.<br>
     * If error then it returns the first message of error<br>
     * If not, if warning then it returns the first message of warning<br>
     * If not, then it show the first info message (if any)<br>
     * If not, then it shows the first success message (if any)<br>
     * If not, then it shows the default message.
     *
     * @param string      $defaultMsg
     * @param null|string $level =[null,'error','warning','errorwarning','info','success'][$i] the level to show (by
     *                           default it shows the first message of any level
     *                           , starting with error)
     * @return string
     */
    public function first($defaultMsg = '', $level = null)
    {
        switch ($level) {
            case 'error':
                return $this->firstError($defaultMsg);
            case 'warning':
                return $this->firstWarning($defaultMsg);
            case 'errorwarning':
                return $this->firstErrorOrWarning($defaultMsg);
            case 'info':
                return $this->firstInfo($defaultMsg);
            case 'success':
                return $this->firstSuccess($defaultMsg);
        }
        $r = $this->firstErrorOrWarning();
        if ($r !== null) {
            return $r;
        }
        $r = $this->firstInfo();
        if ($r !== null) {
            return $r;
        }
        $r = $this->firstSuccess();
        if ($r !== null) {
            return $r;
        }
        return $defaultMsg;
    }

    /**
     * It returns the first message of error, if any. Otherwise it returns the default value
     *
     * @param string $default
     *
     * @return null|string
     */
    public function firstError($default = null)
    {
        if (isset($this->errorMsg[0])) {
            return $this->errorMsg[0];
        }
        return $default;
    }

    /**
     * It returns the first message of warning, if any. Otherwise it returns the default value
     *
     * @param string $default
     *
     * @return null|string
     */
    public function firstWarning($default = null)
    {
        if (isset($this->warningMsg[0])) {
            return $this->warningMsg[0];
        }
        return $default;
    }

    /**
     * It returns the first message of error or warning (in this order), if any. Otherwise it returns the default value
     *
     * @param string $default
     *
     * @return null|string
     */
    public function firstErrorOrWarning($default = null)
    {
        $r = $this->firstError();
        if ($r === null) {
            $r = $this->firstWarning();
        }
        return ($r === null) ? $default : $r;
    }

    /**
     * It returns the first message of info, if any. Otherwise it returns the default value
     *
     * @param string $default
     *
     * @return null|string
     */
    public function firstInfo($default = null)
    {
        if (isset($this->infoMsg[0])) {
            return $this->infoMsg[0];
        }
        return $default;
    }

    /**
     * It returns the first message of success, if any. Otherwise it returns the default value
     *
     * @param string $default
     *
     * @return null|string
     */
    public function firstSuccess($default = null)
    {
        if (isset($this->successMsg[0])) {
            return $this->successMsg[0];
        }
        return $default;
    }

    /**
     * Returns all messages or an empty array if none.
     *
     * @param null|string $level =[null,'error','warning','errorwarning','info','success'][$i] the level to show. Null
     *                           means it shows all errors
     * @return string[]
     */
    public function all($level = null)
    {
        switch ($level) {
            case 'error':
                return $this->allError();
            case 'warning':
                return $this->allWarning();
            case 'errorwarning':
                return $this->allErrorOrWarning();
            case 'info':
                return $this->allInfo();
            case 'success':
                return $this->allSuccess();
        }
        return @array_merge($this->errorMsg, $this->warningMsg, $this->infoMsg, $this->successMsg);
    }

    /**
     * Returns all messages of errors (as an array of string), or an empty array if none.
     *
     * @return string[]
     */
    public function allError()
    {
        return $this->errorMsg;
    }

    /**
     * Returns all messages of warning, or an empty array if none.
     *
     * @return string[]
     */
    public function allWarning()
    {
        return $this->warningMsg;
    }

    /**
     * Returns all messages of errors or warnings, or an empty array if none
     *
     * @return string[]
     */
    public function allErrorOrWarning()
    {
        return @array_merge($this->errorMsg, $this->warningMsg);
    }

    /**
     * Returns all messages of info, or an empty array if none.
     *
     * @return string[]
     */
    public function allInfo()
    {
        return $this->infoMsg;
    }

    /**
     * Returns all messages of success, or an empty array if none.
     *
     * @return string[]
     */
    public function allSuccess()
    {
        return $this->successMsg;
    }

    /**
     * It returns an associative array of the form:<br>
     * <pre>
     * [
     *  ['id'=>'', // id of the locker
     *  'level'=>'' // level of message (error, warning, info or success)
     *  'msg'=>'' // the message to show
     *  ]
     * ]
     * </pre>
     *
     * @param null|string $level    =[null,'error','warning','errorwarning','info','success'][$i] the level to show.
     *                              Null means it shows all messages regardless of the level (starting with error)
     * @return array
     */
    public function allAssocArray($level = null)
    {
        $result = [];
        if ($level === 'error' || $level === 'errorwarning' || $level === null) {
            $tmp = $this->allError();
            foreach ($tmp as $vmsg) {
                $result[] = ['id' => $this->idLocker, 'level' => 'error', 'msg' => $vmsg];
            }
        }
        if ($level === 'warning' || $level === 'errorwarning' || $level === null) {
            $tmp = $this->allWarning();
            foreach ($tmp as $vmsg) {
                $result[] = ['id' => $this->idLocker, 'level' => 'warning', 'msg' => $vmsg];
            }
        }
        if ($level === 'info' || $level === null) {
            $tmp = $this->allInfo();
            foreach ($tmp as $vmsg) {
                $result[] = ['id' => $this->idLocker, 'level' => 'info', 'msg' => $vmsg];
            }
        }
        if ($level === 'success' || $level === null) {
            $tmp = $this->allSuccess();
            foreach ($tmp as $vmsg) {
                $result[] = ['id' => $this->idLocker, 'level' => 'success', 'msg' => $vmsg];
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
    public function hasError($includeWarning = false)
    {
        $tmp = $includeWarning
            ? count($this->errorMsg)
            : count($this->errorMsg) + count($this->warningMsg);
        return $tmp !== 0;
    }
}