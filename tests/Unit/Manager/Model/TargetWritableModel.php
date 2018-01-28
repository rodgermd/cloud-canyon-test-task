<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 28.01.18
 * Time: 13:12
 */

namespace Tests\Unit\Manager\Model;

class TargetWritableModel
{
    private $protected;
    private $privateNotReadable;
    private $privateReadable;
    private $public;

    /**
     * @return mixed
     */
    public function getProtected()
    {
        return $this->protected;
    }

    /**
     * @param mixed $protected
     *
     * @return $this
     */
    public function setProtected($protected)
    {
        $this->protected = $protected;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrivateNotReadable()
    {
        return $this->privateNotReadable;
    }

    /**
     * @param mixed $privateNotReadable
     *
     * @return $this
     */
    public function setPrivateNotReadable($privateNotReadable)
    {
        $this->privateNotReadable = $privateNotReadable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrivateReadable()
    {
        return $this->privateReadable;
    }

    /**
     * @param mixed $privateReadable
     *
     * @return $this
     */
    public function setPrivateReadable($privateReadable)
    {
        $this->privateReadable = $privateReadable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param mixed $public
     *
     * @return $this
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }
}