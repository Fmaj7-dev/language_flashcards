<?php

namespace App\Utils;

class Table 
{
    protected $num_columns;

    protected $header;
    protected $data;

    function __construct($numColumns, $headerData) 
    {
        $num_columns = $numColumns;
        $header = $headerData;
    }

    public function getHeader()
    {
        return $header;
    }

    public function getNumColumns()
    {
        return $num_columns;
    }

    public function getData()
    {
        return $data;
    }

    public function appendRow($newData)
    {
        $data[] = $newData;
    }
}

