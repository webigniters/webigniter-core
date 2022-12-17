<?php

namespace Webigniter\Models;

use CodeIgniter\Model;
use Webigniter\Libraries\Element;

class ElementsModel extends Model
{
    protected $table = 'elements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = Element::class;

    protected $allowedFields = ['name', 'language', 'class', 'partial', 'image', 'settings'];
}