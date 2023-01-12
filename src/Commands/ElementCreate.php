<?php
namespace Webigniter\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Webigniter\Models\ElementsModel;

class ElementCreate extends BaseCommand
{
    protected $group       = 'WebIgniter';
    protected $name        = 'element:create';
    protected $description = 'Creates the basic files and database settings for an element';
    protected $usage       = 'element:create <name>';
    protected $arguments   = ['name' => 'Name of the new element'];

    public function run(array $params)
    {
        if(!key_exists(0,$params))
        {
            CLI::write(CLI::color('Error: name is mandatory [element:create <name>]', 'red'));
        }
        else{
            $elementsModel = new ElementsModel();

            $existingElement = $elementsModel->where('name', strtolower($params[0]))->find();
            if($existingElement)
            {
                CLI::write(CLI::color('Error: There is already an element with this name', 'red'));
                die();
            }

            CLI::write(CLI::color('Creating element ['.$params[0].']', 'yellow'));

            if(key_exists(1,$params) && $params[1] === 'core')
            {
                CLI::write(CLI::color('Core option specified', 'yellow'));
                $namespace = 'Webigniter';
                $folder = ROOTPATH.'\vendor\webigniters\webigniter-core\src';
            }
            else{
                $namespace = 'App';
                $folder = APPPATH;
            }

            @mkdir($folder.'/Libraries/Elements/');
            write_file($folder.'/Libraries/Elements/'.ucfirst($params[0]).'.php', $this->makeEmptyElementClass($namespace, $params[0]));

            @mkdir($folder.'/Views/elements/');
            write_file($folder.'/Views/elements/'.strtolower($params[0]).'.php', $this->makeEmptyElementView());

            $insertData = [
                'name' => strtolower($params[0]),
                'language' => strtolower($params[0]),
                'class' => $namespace.'\Libraries\Elements\\'.ucfirst($params[0]),
                'partial' => $namespace.'\Views\elements\\'.strtolower($params[0]).'.php',
                'image' => 1,
                'settings' => '{"name":"text","default_value":"text"}'
            ];

            $elementsModel->insert($insertData);

            CLI::write('Element succesfully created', 'yellow');
        }
    }

    private function makeEmptyElementClass(string $namespace, string $name): string
    {
        $classContent = '<?php
namespace '.$namespace.'\Libraries\Elements;
';

        if($namespace != 'Webigniter'){
            $classContent .= 'use Webigniter\Libraries\Elements\Elements;
';
        }

        $classContent .= '
class '.$name.' extends Elements
{
    public function output(): string
    {
        return $this->elementData[$this->fieldName];
    }
}';

        return $classContent;
    }


    private function makeEmptyElementView(): string
    {
        $viewContent = '<?php
/** @var object $elementData */
/** @var array|string $value */
/** @var string $fieldName */
?>

<input class="form-control" id="<?=url_title($elementData->name);?>" type="text" name="<?=$fieldName;?>" value="<?=set_value($fieldName, $value);?>" />';

        return $viewContent;
    }
}
