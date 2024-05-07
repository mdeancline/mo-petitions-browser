<?php
class SmartyDataDisplay implements DataDisplay
{
    private readonly Smarty $smarty;
    private readonly string $tplPath;
    private array $variables = [];

    public function __construct(Smarty $smarty, string $tplPath)
    {
        $this->smarty = $smarty;
        $this->tplPath = $tplPath;
    }

    public final function show(array $requestResult)
    {
        foreach ($this->variables as $variable)
            $this->smarty->assign($variable->getName(), $variable->getValue($requestResult));

        $this->smarty->display($this->tplPath);
    }

    public function assign(SmartyDataVariable $variable)
    {
        array_push($this->variables, $variable);
    }
}
