<?php

namespace Webigniter\Models;

use CodeIgniter\Model;
use Webigniter\Libraries\AttachedPartial;

class AttachedPartialsModel extends Model
{
    protected $table = 'attached_partials';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = AttachedPartial::class;

    protected $allowedFields = ['content_id', 'partial_id', 'data'];
}