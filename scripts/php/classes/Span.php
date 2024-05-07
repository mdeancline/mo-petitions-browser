<?php
class Span extends AttributedInlineMarkup
{
    public function __construct(string | InlineMarkup $value)
    {
        parent::__construct("span", $value);
    }
}
