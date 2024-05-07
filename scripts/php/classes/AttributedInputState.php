<?php
interface AttributedInputState extends InputState
{
    public function applyTo(Input $input);
}
