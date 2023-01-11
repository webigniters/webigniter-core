<?php

namespace Webigniter\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use Webigniter\Libraries\Partial;

class PartialsModel extends Model
{
    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);

        $validationMessages = [
            'name' => [
                'required' => ucfirst(lang('errors.required', [lang('general.name')])),
                'min_length' => ucfirst(lang('errors.min_length', [lang('general.name'), 3])),
                'is_unique' => ucfirst(lang('errors.is_unique', [lang('general.partial'), lang('general.name')]))
            ],
            'view_file' => [
                'required' => ucfirst(lang('errors.required', [lang('general.view_file')])),
            ]
        ];

        $this->setValidationMessages($validationMessages);
    }

    protected $table = 'partials';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = Partial::class;

    protected $allowedFields = ['name', 'view_file'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name'     => 'required|min_length[3]|is_unique[partials.name]',
        'view_file' => 'required',
    ];
}