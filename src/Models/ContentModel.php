<?php

namespace Webigniter\Models;

use CodeIgniter\Model;
use Webigniter\Libraries\Content;

class ContentModel extends Model
{
    protected $table = 'content';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = Content::class;

    protected $allowedFields = ['name', 'slug', 'view_file', 'category_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}