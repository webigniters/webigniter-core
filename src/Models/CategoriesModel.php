<?php

namespace Webigniter\Models;

use CodeIgniter\Model;
use Webigniter\Libraries\Category;

class CategoriesModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = Category::class;

    protected $allowedFields = ['name', 'slug', 'require_slug', 'parent_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}