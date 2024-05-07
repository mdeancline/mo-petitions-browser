<?php
class Bold extends AttributedInlineMarkup
{
    public function __construct(string | InlineMarkup $value)
    {
        parent::__construct("b", $value);
    }
}
