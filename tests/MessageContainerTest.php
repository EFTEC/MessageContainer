<?php


use eftec\MessageContainer;
use PHPUnit\Framework\TestCase;


class MessageContainerTest extends TestCase
{
    private $messageList;

    public function testThrow(): void
    {
        $ml = new MessageContainer();
        $ml->LogOnError(true)->throwOnError(true,false);
        try {
            $ml->addItem('test','it is an error');
        } catch(Exception $ex) {
            $this->assertEquals('it is an error',$ex->getMessage());
        }
        try {
            $ml->addItem('test','it is an warning','warning');
            $this->assertTrue(true);
        } catch(Exception $ex) {
            $this->assertEquals(false,$ex->getMessage());
        }
    }
    public function testArray(): void
    {
        $ml = new MessageContainer();
        $ml->addItem('lock1','error1');
        $ml->addItem('lock1','warning1','warning');
        $ml->addItem('lock1','info1','info');
        $ml->addItem('lock1','success1','success');

        $this->assertEquals(['error1','warning1','info1','success1'],$ml->allArray());
        $this->assertEquals(
            [0=>['id' => 'lock1','level' => 'error','msg' => 'error1']
                ,1=>['id' => 'lock1','level' => 'warning','msg' => 'warning1']
                ,2=>['id' => 'lock1','level' => 'info','msg' => 'info1']
                ,3=>['id' => 'lock1','level' => 'success','msg' => 'success1']
            ],$ml->allAssocArray());
        $this->assertEquals(true,$ml->hasError(true));
        $this->assertEquals(['lock1'],$ml->allIds());

    }
    public function testMessagesArray(): void
    {
        $ml = new MessageContainer();
        $ml->addItem('c1', 'message error c1-1');
        $ml->addItem('c1', 'message error c1-2');
        $ml->addItem('c1', 'message warning c1-2', 'warning');

        $ml->addItem('c2', 'message error c2-1');
        $ml->addItem('c2', 'message error c2-2');
        $ml->addItem('c2', 'message info c2-2', 'info');


        self::assertEquals(
            [
                0 => [
                    'id' => 'c1',
                    'level' => 'error',
                    'msg' => 'message error c1-1',
                ],
                1 => [
                    'id' => 'c1',
                    'level' => 'error',
                    'msg' => 'message error c1-2',
                ],
                2 => [
                    'id' => 'c1',
                    'level' => 'warning',
                    'msg' => 'message warning c1-2',
                ],
                3 => [
                    'id' => 'c2',
                    'level' => 'error',
                    'msg' => 'message error c2-1',
                ],
                4 => [
                    'id' => 'c2',
                    'level' => 'error',
                    'msg' => 'message error c2-2',
                ],
                5 => [
                    'id' => 'c2',
                    'level' => 'info',
                    'msg' => 'message info c2-2',
                ]
            ]
            , $ml->allAssocArray());

        self::assertEquals(
            [
                0 => [
                    'id' => 'c1',
                    'level' => 'error',
                    'msg' => 'message error c1-1',
                ],
                1 => [
                    'id' => 'c1',
                    'level' => 'error',
                    'msg' => 'message error c1-2',
                ],
                2 => [
                    'id' => 'c1',
                    'level' => 'warning',
                    'msg' => 'message warning c1-2',
                ]
            ]
            , $ml->get('c1')->allAssocArray());

        self::assertEquals(
            [
                0 => [
                    'id' => 'c1',
                    'level' => 'warning',
                    'msg' => 'message warning c1-2',
                ]
            ]
            , $ml->get('c1')->allAssocArray('warning'));

        self::assertEquals(
            [
                0 => [
                    'id' => 'c1',
                    'level' => 'error',
                    'msg' => 'message error c1-1',
                ],
                1 => [
                    'id' => 'c1',
                    'level' => 'error',
                    'msg' => 'message error c1-2',
                ],
                2 => [
                    'id' => 'c1',
                    'level' => 'warning',
                    'msg' => 'message warning c1-2',
                ],
                3 => [
                    'id' => 'c2',
                    'level' => 'error',
                    'msg' => 'message error c2-1',
                ],
                4 => [
                    'id' => 'c2',
                    'level' => 'error',
                    'msg' => 'message error c2-2',
                ]
            ]
            , $ml->allAssocArray('errorwarning'));

        self::assertEquals(
            [
                [
                    'id' => 'c1',
                    'level' => 'warning',
                    'msg' => 'message warning c1-2',
                ]
            ]
            , $ml->allAssocArray('warning'));
    }

    public function testContextCurly(): void
    {
        $ml = new MessageContainer();
        $ml->addItem('c1', 'var1={{var1}} var2={{var2}}', 'error', ['var1' => 'hello', 'var2' => 'world']);
        self::assertEquals('var1=hello var2=world', $ml->firstErrorText());
        self::assertEquals('var1=hello var2=world', $ml->get('c1')->firstError());
        $ml = new MessageContainer();
        $ml->addItem('c1', 'var1={{var1}} var2={{var2}}');
        self::assertEquals('var1= var2=', $ml->firstErrorText());
        self::assertEquals('var1= var2=', $ml->get('c1')->firstError());
        $ml->addItem('c1', 'var1={{var1}} var2={{var2}}', 'error', ['var1' => 'hello', 'var2' => 'world']);
        self::assertEquals('var1= var2=', $ml->firstErrorText());
        self::assertEquals('var1=hello var2=world', $ml->get('c1')->allError()[1]);
        $ml = new MessageContainer();
        $ml->addItem('c1', 'id={{_idlocker}} var1={{var1}} var2={{var2}}', 'error', ['var1' => 'hello', 'var2' => 'world']);
        self::assertEquals('id=c1 var1=hello var2=world', $ml->firstErrorText());
        self::assertEquals('id=c1 var1=hello var2=world', $ml->get('c1')->firstError());
    }

    public function testMessages(): void
    {
        $ml = new MessageContainer();
        $ml->addItem('c1', 'message error c1-1');
        $ml->addItem('c1', 'message error c1-2');

        $ml->addItem('c2', 'message error c2-1');
        $ml->addItem('c2', 'message error c2-2');

        self::assertEquals(['message error c1-1', 'message error c1-2'], $ml->get('c1')->allErrorOrWarning());
        self::assertEquals(['message error c1-1', 'message error c1-2'], $ml->get('c1')->allErrorOrWarning());

        self::assertEquals([
            0 => 'message error c1-1',
            1 => 'message error c1-2',
            2 => 'message error c2-1',
            3 => 'message error c2-2'
        ], $ml->allErrorOrWarningArray());
    }

    public function testMessageList(): void
    {
        $this->messageList->resetAll();
        self::assertEquals(0, $this->messageList->errorCount);
        $this->messageList->addItem('containere', 'errorm');
        $this->messageList->addItem('containere', 'errorm2');
        $this->messageList->addItem('containeri', 'infom', 'info');
        $this->messageList->addItem('containeri', 'infom2', 'info');
        $this->messageList->addItem('container1', 'warningm', 'warning');
        $this->messageList->addItem('container1', 'warningm2', 'warning');
        $this->messageList->addItem('containers', 'successm', 'success');
        $this->messageList->addItem('containers', 'successm2', 'success');
        self::assertEquals(2, $this->messageList->errorCount);
        self::assertEquals(2, $this->messageList->warningCount);
        self::assertEquals(2, $this->messageList->infoCount);
        self::assertEquals(2, $this->messageList->successCount);
        self::assertEquals('warningm', $this->messageList->items['container1']->first());
        self::assertEquals('warningm', $this->messageList->items['container1']->firstErrorOrWarning());
        self::assertEquals('warningm2', $this->messageList->items['container1']->last());
        self::assertEquals('', $this->messageList->items['container1']->last('','error'));
        self::assertEquals('warningm2', $this->messageList->items['container1']->last('','errorwarning'));
        self::assertEquals('warningm2', $this->messageList->items['container1']->last('','warning'));
        self::assertEquals('infom2', $this->messageList->items['containeri']->last('','info'));
        self::assertEquals('successm2', $this->messageList->items['containers']->last('','success'));
        self::assertEquals('warningm2', $this->messageList->items['container1']->lastErrorOrWarning());

        self::assertEquals(null, $this->messageList->items['container1']->firstError());
        self::assertEquals('errorm', $this->messageList->firstErrorOrWarning());
        self::assertEquals('errorm', $this->messageList->firstErrorText());
        self::assertEquals('infom', $this->messageList->firstInfoText());
        self::assertEquals('successm', $this->messageList->firstSuccessText());
        self::assertEquals('warningm', $this->messageList->firstWarningText());

        self::assertEquals(null, $this->messageList->items['container1']->lastError());
        self::assertEquals('warningm2', $this->messageList->lastErrorOrWarning());
        self::assertEquals('errorm2', $this->messageList->lastErrorText());
        self::assertEquals('infom2', $this->messageList->lastInfoText());
        self::assertEquals('successm2', $this->messageList->lastSuccessText());
        self::assertEquals('warningm2', $this->messageList->lastWarningText());

        self::assertEquals(2, $this->messageList->get('containere')->countError());
        self::assertEquals(2, $this->messageList->get('container1')->countWarning());
        self::assertEquals(2, $this->messageList->get('containeri')->countInfo());
        self::assertEquals(2, $this->messageList->get('containers')->countSuccess());
        self::assertEquals(2, $this->messageList->infoCount);
        self::assertEquals(2, $this->messageList->errorCount);
        self::assertEquals(2, $this->messageList->warningCount);
        self::assertEquals(2, $this->messageList->successCount);

        self::assertEquals('warning', $this->messageList->cssClass('container1'));

    }
    public function testHasError(): void
    {
        $this->messageList->resetAll();
        self::assertEquals(0, $this->messageList->errorCount);
        $this->messageList->addItem('containere', 'errorm');
        $this->assertEquals(true,$this->messageList->getLocker('containere')->hasError());
    }
    public function testAll(): void
    {
        $this->messageList->resetAll();
        self::assertEquals(0, $this->messageList->errorCount);
        $this->messageList->addItem('containere', 'errorm');
        $this->messageList->addItem('containere', 'warningm','warning');
        $this->messageList->addItem('containere', 'infom','info');
        $this->messageList->addItem('containere', 'successm','success');
        $this->assertEquals(['errorm','warningm','infom','successm'],$this->messageList->get('containere')->all());
        $this->assertEquals(['errorm'],$this->messageList->get('containere')->all('error'));
        $this->assertEquals(['warningm'],$this->messageList->get('containere')->all('warning'));
        $this->assertEquals(['infom'],$this->messageList->get('containere')->all('info'));
        $this->assertEquals(['successm'],$this->messageList->get('containere')->all('success'));
    }

    protected function setUp() : void
    {
        $this->messageList = new MessageContainer();
    }


}
