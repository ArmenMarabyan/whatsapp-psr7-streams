<?php

declare(strict_types=1);

namespace Armen\WhatsappPsr7Streams;

enum MediaType :string
{
    case IMAGE = 'WhatsApp Image Keys';
    case VIDEO = 'WhatsApp Video Keys';
    case AUDIO = 'WhatsApp Audio Keys';
    case DOCUMENT = 'WhatsApp Document Keys';
}