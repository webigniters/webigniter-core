<?php
namespace Webigniter\Custom;

use Config\Database;

class CustomValidationRules
{
    //To check the database for a combined unique value
    //Usage: is_double_unique[table,field1,field2,field2_value,ignore_field,ignore_value]
    public function is_double_unique(string $str, string $details, array $data): bool
    {
        $params = explode(',',$details);

        if(count($params) < 3)
        {
            return false;
        }

        $db = Database::connect($data['DBGroup'] ?? null);

        $secondValue = $params[3] == '' ? null : $params[3];

        $ignoreField = count($params) > 5 ? $params[4]. '<>' : 'id >';
        $ignoreValue = count($params) > 5 ? $params[5] : 1;

        $query = $db->table($params[0])
            ->where($params[1], $str)
            ->where($params[2], $secondValue)
            ->where($ignoreField, $ignoreValue)
            ->get();

        return $query->getNumRows() == 0;
    }
}
