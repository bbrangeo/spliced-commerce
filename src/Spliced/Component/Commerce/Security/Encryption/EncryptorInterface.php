<?php

namespace Spliced\Component\Commerce\Security\Encryption;

interface EncryptorInterface
{

    public function encrypt($protectCode, $toEncrypt);

    public function decrypt($protectCode, $toDecrypt);

    public function generateIv();
}
