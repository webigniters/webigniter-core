<?php

namespace Webigniter\Models;

use CodeIgniter\Model;
use Webigniter\Libraries\Navigation;

class NavigationsModel extends Model
{
    protected $table = 'navigations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = Navigation::class;

    protected $allowedFields = ['name'];
}