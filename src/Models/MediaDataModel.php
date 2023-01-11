<?php

namespace Webigniter\Models;

use CodeIgniter\Model;
use Webigniter\Libraries\MediaData;

class MediaDataModel extends Model
{
    protected $table = 'media_data';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = MediaData::class;

    protected $allowedFields = ['filename', 'alt'];

    protected $useTimestamps = false;

}