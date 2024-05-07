<?php
class FormDisplay implements DataDisplay
{
    private readonly SmartyDataDisplay $helper;

    public function __construct(SmartyDataDisplay $helper)
    {
        $this->helper = $helper;
    }

    public function show(array $requestResult)
    {
        $dataRow = $requestResult["data"][0] ?? [];
        $formData = get_form_data() ?: $dataRow;

        foreach ($formData as $upperKey => $upperValue) {
            if (is_array($upperValue) && is_associative($upperValue)) {
                foreach ($upperValue as $lowerKey => $lowerValue) {
                    $value = is_array($lowerValue)
                        ? json_encode($lowerValue)
                        : $lowerValue;
                    $this->assignDefaultInputValue($dataRow, $lowerKey);
                    $this->helper->assign(new SimpleVariable($lowerKey, $value ?? ""));
                }
            } else {
                $this->assignDefaultInputValue($dataRow, $upperKey);
                $this->helper->assign(new SimpleVariable($upperKey, $upperValue ?? ""));
            }
        }

        $this->helper->show($requestResult);
    }

    private function assignDefaultInputValue(array $dataRow, string $name)
    {
        if (isset($dataRow[$name])) {
            $key = "default" . ucwords($name);
            $this->helper->assign(new SimpleVariable($key, $dataRow[$name]));
        }
    }
}
