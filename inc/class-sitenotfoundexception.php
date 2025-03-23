<?php

namespace HM\Cavalcade\Runner;

use RuntimeException;

class SiteNotFoundException extends RuntimeException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
