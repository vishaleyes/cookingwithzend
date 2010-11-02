<?php

class IndexControllerTest extends ControllerTestCase
{

	public function testIndexAction()
	{
		$this->dispatch('/');
		$this->assertAction("index");
		$this->assertController("index");
	}

}