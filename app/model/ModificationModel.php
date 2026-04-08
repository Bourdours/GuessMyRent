<?php

namespace GmR\model;

use GmR\model\Model;

class ModificationModel extends Model
{
    function __construct()
    {
        $this->tableName = 'ESTATE_MODIFICATION';
        $this->primaryKey = "id_modification";
    }
}
