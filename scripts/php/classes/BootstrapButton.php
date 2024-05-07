<?php
class BootstrapButton extends AbstractAttributedMarkup
{
    private readonly BootstrapStyle $style;

    private function __construct(string $tag, string $text, BootstrapStyle $style)
    {
        parent::__construct($tag, $text);
        parent::withClassList("btn", "btn-{$style->value}");
        $this->style = $style;
    }

    public static function normal(string $text, BootstrapStyle $style = BootstrapStyle::PRIMARY): BootstrapButton
    {
        return (new BootstrapButton("button", $text, $style))
            ->withAttribute("type", "button");
    }

    public static function forOpeningLink(string $href, string $text, AnchorTarget $target = AnchorTarget::SELF, BootstrapStyle $style = BootstrapStyle::PRIMARY): BootstrapButton
    {
        return (new BootstrapButton("a", $text, $style))
            ->withAttribute("href", $href)
            ->withAttribute("target", $target->value);
    }

    public static function forOpening(BootstrapTarget $target, string $text, BootstrapStyle $style = BootstrapStyle::PRIMARY)
    {
        return self::normal($text, $style)
            ->withAttribute("data-bs-toggle", $target->getName())
            ->withAttribute("data-bs-target", "#" . $target->getAttribute("id"));
    }

    public static function forClosing(BootstrapTarget $target = null, string $text = "", BootstrapStyle $style = BootstrapStyle::SECONDARY)
    {
        $btn = self::normal($text, $style);

        if (!is_null($target)) $btn->withAttribute("data-bs-dismiss", $target->getName());

        if (empty($text)) {
            $btn->classList = array_diff($btn->classList, ["btn", "btn-{$btn->style->value}"]);
            array_push($btn->classList, "btn-close", "m-auto");
        }

        return $btn;
    }

    public function withClassList(string ...$classList): BootstrapButton
    {
        return parent::withClassList(...$classList);
    }
}
