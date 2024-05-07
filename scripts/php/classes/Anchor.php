<?php
class Anchor extends AttributedInlineMarkup
{
    public function __construct(string $href, string | InlineMarkup $value, AnchorTarget $target = null)
    {
        parent::__construct("a", $value);
        $this->withAttribute("href", $href);

        if (!is_null($target))
            $this->withAttribute("target", $target->value);
    }

    public function withClassList(string ...$classList): Anchor
    {
        return parent::withClassList(...$classList);
    }

    public function withHref(string $href): Anchor
    {
        return parent::withAttribute("href", $href);
    }

    public function withText(string $text): Anchor
    {
        return parent::withInline(new RawText($text));
    }

    public function withInline(InlineMarkup $markup): Anchor
    {
        return parent::withInline($markup);
    }
}
