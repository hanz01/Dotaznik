<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 19.3.2018
 * Time: 19:04
 */

abstract class Question
{
    protected $question;

    public function __construct($qustion)
    {
        $this->question = $qustion;
    }

    abstract function renderFront();

}