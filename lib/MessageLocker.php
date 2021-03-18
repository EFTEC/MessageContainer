<?php /** @noinspection UnknownInspectionInspection */

/** @noinspection PhpUnused */

namespace eftec;

/**
 * Class MessageLocker
 *
 * @package       eftec
 * @author        Jorge Castro Castillo
 * @version       1.0 2021-03-17
 * @copyright (c) Jorge Castro C. MIT License  https://github.com/EFTEC/MessageContainer
 * @see           https://github.com/EFTEC/MessageContainer
 */
class MessageLocker {
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
     */
    public function __construct() {
        $this->errorMsg = [];
        $this->warningMsg = [];
        $this->infoMsg = [];
        $this->successMsg = [];
    }

    /**
     * It adds an error to the locker.
     *
     * @param mixed $msg
     */
    public function addError($msg) {
        $this->errorMsg[] = $msg;
    }

    /**
     * It adds a warning to the locker.
     *
     * @param mixed $msg
     */
    public function addWarning($msg) {
        $this->warningMsg[] = $msg;
    }

    /**
     * It adds an information to the locker.
     *
     * @param mixed $msg
     */
    public function addInfo($msg) {
        $this->infoMsg[] = $msg;
    }

    /**
     * It adds a success to the locker.
     *
     * @param mixed $msg
     */
    public function addSuccess($msg) {
        $this->successMsg[] = $msg;
    }

    /**
     * It returns the number of errors contained in the locker
     *
     * @return int
     */
    public function countError() {
        return count($this->errorMsg);
    }
    /**
     * It returns the number of errors or warnings contained in the locker
     *
     * @return int
     */
    public function countErrorOrWarning() {
        return $this->countError() + $this->countWarning();
    }
    /**
     * It returns the number of warnings contained in the locker
     *
     * @return int
     */
    public function countWarning() {
        return count($this->warningMsg);
    }
    /**
     * It returns the number of infos contained in the locker
     *
     * @return int
     */
    public function countInfo() {
        return count($this->infoMsg);
    }
    /**
     * It returns the number of successes contained in the locker
     *
     * @return int
     */
    public function countSuccess() {
        return count($this->successMsg);
    }

    /**
     * It returns the first message of error, if any. Otherwise it returns the default value
     *
     * @param string $default
     *
     * @return null|string
     */
    public function firstError($default = null) {
        if (isset($this->errorMsg[0])) {
            return $this->errorMsg[0];
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
    public function firstErrorOrWarning($default = null) {
        $r = $this->firstError();
        if ($r === null) {
            $r = $this->firstWarning();
        }
        return ($r === null) ? $default : $r;
    }

    /**
     * It returns the first message of warning, if any. Otherwise it returns the default value
     *
     * @param string $default
     *
     * @return null|string
     */
    public function firstWarning($default = null) {
        if (isset($this->warningMsg[0])) {
            return $this->warningMsg[0];
        }
        return $default;
    }

    /**
     * It returns the first message of info, if any. Otherwise it returns the default value
     *
     * @param string $default
     *
     * @return null|string
     */
    public function firstInfo($default = null) {
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
    public function firstSuccess($default = null) {
        if (isset($this->successMsg[0])) {
            return $this->successMsg[0];
        }
        return $default;
    }

    /**
     * It returns the first message of any kind.<br>
     * If error then it returns the first message of error<br>
     * If not, if warning then it returns the first message of warning<br>
     * If not, then it show the first info message (if any)<br>
     * If not, then it shows the first success message (if any)<br>
     * If not, then it shows the default message.
     *
     * @param string $defaultMsg
     *
     * @return string
     */
    public function first($defaultMsg = '') {
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
     * Returns all messages of errors, or an empty array if none.
     *
     * @return string[]
     */
    public function allError() {
        return $this->errorMsg;
    }

    /**
     * Returns all messages of errors or warnings, or an empty array if none
     *
     * @return string[]
     */
    public function allErrorOrWarning() {
        return @array_merge($this->errorMsg, $this->warningMsg);
    }

    /**
     * Returns all messages of warning, or an empty array if none.
     *
     * @return string[]
     */
    public function allWarning() {
        return $this->warningMsg;
    }

    /**
     * Returns all messages of info, or an empty array if none.
     *
     * @return string[]
     */
    public function allInfo() {
        return $this->infoMsg;
    }

    /**
     * Returns all messages of success, or an empty array if none.
     *
     * @return string[]
     */
    public function allSuccess() {
        return $this->successMsg;
    }

    /**
     * It returns true if there is an error (or error and warning).
     *
     * @param bool $includeWarning If true then it also returns if there is a warning
     * @return bool
     */
    public function hasError($includeWarning=false) {
        $tmp=$includeWarning
            ? count($this->errorMsg)
            : count($this->errorMsg)+count($this->warningMsg);
        return $tmp !==0;
    }
}