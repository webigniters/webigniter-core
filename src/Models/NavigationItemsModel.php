<?php

namespace Webigniter\Models;

use CodeIgniter\Model;
use Webigniter\Libraries\NavigationItem;

class NavigationItemsModel extends Model
{
    protected $table = 'navigation_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = NavigationItem::class;

    protected $allowedFields = ['navigation_id', 'parent_id', 'name', 'content_id', 'link', 'order'];
}