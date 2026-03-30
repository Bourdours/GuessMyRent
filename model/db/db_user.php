<?php

class User extends DbConnect
{
    function testConnect()
    {
        self::connect();
        echo "bien connecté";
    }
}
