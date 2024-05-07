<?php
interface InlineMarkup extends HTMLMarkup
{
    public function withText(string $text): InlineMarkup;
    public function withInline(InlineMarkup $markup): InlineMarkup;
}
