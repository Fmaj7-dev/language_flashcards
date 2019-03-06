<?php

namespace App\Utils;

class Table 
{
    protected $num_columns;

    protected $header;
    protected $data;

    function __construct($numColumns, $headerData) 
    {
        $this->num_columns = $numColumns;
        $this->header = $headerData;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getNumColumns()
    {
        return $this->num_columns;
    }

    public function getData()
    {
        return $this->data;
    }

    public function appendRow($newData)
    {
        $this->data[] = $newData;
    }
}

