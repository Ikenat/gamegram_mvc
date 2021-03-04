<?php

class BootstrapAlert
{
    private $text;
    private $options;

    public function __construct($text, $options = [])
    {
        //??
        $this->text = $text;
        $this->options = $options;
    }

    public function alert()
    {

        $color = $this->options['color'] ?? DANGER;
        $class = ALERT . ' ' . ALERT . '-' . $color . ' ';

        $class .= $this->options['class'] ?? '';

        return '<div class =" ' . $class . '">' .
        $this->text . '
        </div>';
    }
}