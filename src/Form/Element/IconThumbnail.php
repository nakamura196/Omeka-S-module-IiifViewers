<?php
namespace IiifViewers\Form\Element;

use Laminas\Form\Element;

/**
 * Textarea element for HTML.
 *
 * Purifies the markup after form submission.
 * 設定フォームアイコン表示用Element
 */
class IconThumbnail extends Element
{
    protected $attributes = [
        'type' => 'icon-thumnail',
    ];
}
