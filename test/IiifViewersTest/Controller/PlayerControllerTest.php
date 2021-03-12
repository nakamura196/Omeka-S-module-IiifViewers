<?php declare(strict_types=1);

namespace IiifViewersTest\Controller;

class PlayerControllerTest extends IiifViewersControllerTestCase
{
    public function testIndexActionCanBeAccessed(): void
    {
        $this->dispatch('/item/' . $this->item->id() . '/play');

        $this->assertResponseStatusCode(200);
    }
}
