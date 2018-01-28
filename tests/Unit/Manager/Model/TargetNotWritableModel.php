<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 28.01.18
 * Time: 13:13
 */

namespace Tests\Unit\Manager\Model;


class TargetNotWritableModel
{
    protected $protected;
    protected $privateNotReadable;
    protected $privateReadable;
    protected $public;
}