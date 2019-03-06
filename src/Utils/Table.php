<?php

namespace App\Utils;

class Table 
{
    protected $title;

    protected $header;
    protected $data;

    function __construct($title, $headerData) 
    {
        $this->title = $title;
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

    public function getTitle()
    {
        return $this->title;
    }

    public function appendRow($newData)
    {
        $this->data[] = $newData;
    }

    public function setRows($rows)
    {
        $this->data[] = $rows;
    }
}

