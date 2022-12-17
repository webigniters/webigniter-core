<?php

namespace Webigniter\Models;

use CodeIgniter\Model;
use Webigniter\Libraries\AttachedElement;

class AttachedElementsModel extends Model
{
    protected $table = 'attached_elements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = AttachedElement::class;

    protected $allowedFields = ['content_id', 'element_id', 'settings'];
}