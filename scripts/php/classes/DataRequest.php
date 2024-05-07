<?php
interface DataRequest extends Stringable
{
    public function submit(DataMessageDictionary $dictionary): array;
}
