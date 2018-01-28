<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 28.01.18
 * Time: 13:09
 */

namespace Tests\Unit\Manager\Model;

class SourceModel
{
    protected $protected = 'protected';
    private $privateNotReadable = 'privateNotReadable';
    private $privateReadable = 'privateReadable';
    public $public = 'public';

    public function getPrivateReadable()
    {
        return $this->privateReadable;
    }
}