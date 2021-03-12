<?php declare(strict_types=1);

namespace IiifViewersTest\Controller;

use OmekaTestHelper\Controller\OmekaControllerTestCase;

abstract class IiifViewersControllerTestCase extends OmekaControllerTestCase
{
    protected $item;

    public function setUp(): void
    {
        $this->loginAsAdmin();

        $response = $this->api()->create('items');
        $this->item = $response->getContent();
    }

    public function tearDown(): void
    {
        $this->api()->delete('items', $this->item->id());
    }
}
