# MessageContainer
It is a Message Container for PHP, similar in functionality MessageBag for Laravel

[![Packagist](https://img.shields.io/packagist/v/eftec/messagecontainer.svg)](https://packagist.org/packages/eftec/MessageContainer)
[![Total Downloads](https://poser.pugx.org/eftec/messagecontainer/downloads)](https://packagist.org/packages/eftec/MessageContainer)
[![Maintenance](https://img.shields.io/maintenance/yes/2021.svg)]()
[![composer](https://img.shields.io/badge/composer-%3E1.8-blue.svg)]()
[![php](https://img.shields.io/badge/php->5.6-green.svg)]()
[![php](https://img.shields.io/badge/php-7.x-green.svg)]()
[![php](https://img.shields.io/badge/php-8.x-green.svg)]()
[![CocoaPods](https://img.shields.io/badge/docs-70%25-yellow.svg)]()


# MessageContainer
Class MessageList
## Field items (MessageItem[])
Array of containers
## Field errorcount (int)
Number of errors stored globally
## Field warningcount (int)
Number of warnings stored globally
## Field errorOrWarning (int)
Number of errors or warning stored globally
## Field infocount (int)
Number of information stored globally
## Field successcount (int)
Number of success stored globally
## Field cssClasses (string[])
Used to convert a type of message to a css class

## Method __construct()
MessageList constructor.

## Method resetAll()


## Method addItem()
You could add a message (including errors,warning..) and store it in a $id
### Parameters:
* **$id** Identified of the container message (where the message will be stored) (string)
* **$message** message to show. Example: 'the value is incorrect' (string)
* **$level** =['error','warning','info','success'][$i] (string)

## Method allIds()
It obtains all the ids for all the containers.

## Method getMessage()
It returns an message item. If the item doesn't exist then it returns an empty object (not null)
### Parameters:
* **$id** Id of the container (string)

## Method get()
Alias of $this->getMessage()
### Parameters:
* **$id** Id of the container (string)

## Method cssClass()
It returns a css class associated with the type of errors inside a container<br>
If the container contains more than one message, then it uses the most severe one (error,warning,etc.)
### Parameters:
* **$id** Id of the container (string)

## Method firstErrorOrWarning()
It returns the first message of error (if any), if not,
it returns the first message of warning (if any)

## Method firstErrorText()
It returns the first message of error (if any)
### Parameters:
* **$includeWarning** if true then it also includes warning but any error has priority. (bool)

## Method firstWarningText()
It returns the first message of warning (if any)

## Method firstInfoText()
It returns the first message of information (if any)

## Method firstSuccessText()
It returns the first message of success (if any)

## Method allInfoArray()
It returns an array with all messages of info of all containers.

## Method allWarningArray()
It returns an array with all messages of warning of all containers.

## Method AllSuccessArray()
It returns an array with all messages of success of all containers.

## Method allArray()
It returns an array with all messages of any type of all containers

## Method allErrorOrWarningArray()
It returns an array with all messages of errors and warnings of all containers.

## Method allErrorArray()
It returns an array with all messages of error of all containers.
### Parameters:
* **$includeWarning** if true then it also include warnings. (bool)

## Method hasError()
It returns true if there is an error (or error and warning).
### Parameters:
* **$includeWarning** If true then it also returns if there is a warning (bool)


# MessageItem
Class MessageItem

## Method __construct()
MessageItem constructor.

## Method addError()
It adds an error.
### Parameters:
* **$msg** param mixed $msg (mixed)

## Method addWarning()
It adds a warning.
### Parameters:
* **$msg** param mixed $msg (mixed)

## Method addInfo()
It adds an information.
### Parameters:
* **$msg** param mixed $msg (mixed)

## Method addSuccess()
It adds a success.
### Parameters:
* **$msg** param mixed $msg (mixed)

## Method countError()


## Method countErrorOrWarning()


## Method countWarning()


## Method countInfo()


## Method countSuccess()


## Method firstError()
It returns the first message of error, if any. Otherwise it returns the default value
### Parameters:
* **$default** param string $default (string)

## Method firstErrorOrWarning()
It returns the first message of error or warning (in this order), if any. Otherwise it returns the default value
### Parameters:
* **$default** param string $default (string)

## Method firstWarning()
It returns the first message of warning, if any. Otherwise it returns the default value
### Parameters:
* **$default** param string $default (string)

## Method firstInfo()
It returns the first message of info, if any. Otherwise it returns the default value
### Parameters:
* **$default** param string $default (string)

## Method firstSuccess()
It returns the first message of success, if any. Otherwise it returns the default value
### Parameters:
* **$default** param string $default (string)

## Method first()
It returns the first message of any kind.<br>
If error then it returns the first message of error<br>
If not, if warning then it returns the first message of warning<br>
If not, then it shows the first info message (if any)<br>
If not, then it shows the first success message (if any)<br>
If not, then it shows the default message.
### Parameters:
* **$defaultMsg** param string $defaultMsg (string)

## Method allError()
Returns all messages of errors, or an empty array.

## Method allErrorOrWarning()
Returns all messages of errors or warnings, or an empty array

## Method allWarning()
Returns all messages of warning, or an empty array.

## Method allInfo()
Returns all messages of info, or an empty array.

## Method allSuccess()
Returns all messages of success, or an empty array.

## Method hasError()
It returns true if there is an error (or error and warning).
### Parameters:
* **$includeWarning** If true then it also returns if there is a warning (bool)


## changelog

* 1.0 2021-03-17 first version 


