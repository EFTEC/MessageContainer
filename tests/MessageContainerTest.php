<?php

namespace eftec\tests;

use DateTime;
use eftec\MessageContainer;
use PHPUnit\Framework\TestCase;


class MessageContainerTest extends TestCase
{
    private $messageList;


    protected function setUp()
    {
        $this->messageList=new MessageContainer();
    }




    public function testMessages()
    {
        $ml = new MessageContainer();
        $ml->addItem('c1', 'message error c1-1', 'error');
        $ml->addItem('c1', 'message error c1-2', 'error');

        $ml->addItem('c2', 'message error c2-1', 'error');
        $ml->addItem('c2', 'message error c2-2', 'error');

        self::assertEquals(['message error c1-1', 'message error c1-2'], $ml->get('c1')->allErrorOrWarning());
        self::assertEquals(['message error c1-1', 'message error c1-2'], $ml->get('c1')->allErrorOrWarning());

        self::assertEquals([
            0 => 'message error c1-1',
            1 => 'message error c1-2',
            2 => 'message error c2-1',
            3 => 'message error c2-2'
        ], $ml->allErrorOrWarningArray());
    }

    
    public function testMessageList() {
        $this->messageList->resetAll();
        self::assertEquals(0,$this->messageList->errorCount);
        $this->messageList->addItem('containere','errorm','error');
        $this->messageList->addItem('containeri','infom','info');
        $this->messageList->addItem('container1','warningm','warning');
        $this->messageList->addItem('containers','successm','success');
        self::assertEquals(1,$this->messageList->errorCount);
        self::assertEquals(1,$this->messageList->warningCount);
        self::assertEquals(1,$this->messageList->infoCount);
        self::assertEquals(1,$this->messageList->successCount);
        self::assertEquals('warningm',$this->messageList->items['container1']->first());
        self::assertEquals('warningm',$this->messageList->items['container1']->firstErrorOrWarning());
        self::assertEquals(null,$this->messageList->items['container1']->firstError());
        self::assertEquals('errorm',$this->messageList->firstErrorOrWarning());
        self::assertEquals('errorm',$this->messageList->firstErrorText());
        self::assertEquals('infom',$this->messageList->firstInfoText());
        self::assertEquals('successm',$this->messageList->firstSuccessText());
        self::assertEquals('warningm',$this->messageList->firstWarningText());
        self::assertEquals('warning',$this->messageList->cssClass('container1'));

    }



}