<?php


namespace App\Test\Utilities;


use Cake\Filesystem\File;

trait DebugTrait
{

    public function startDebug($var, $label = '')
    {
        $file = new File(WWW_ROOT . 'debug.html');
        $file->write($this->getBuffer($var, $label) . "\n");
        $result = $file->close();
    }

    public function extendDebug($var, $label = '')
    {
        $file = new File(WWW_ROOT . 'debug.html');
        $file->open('a');
        $file->append($this->getBuffer($var, $label) . "\n");
        $result = $file->close();
    }

    /**
     * @param $var
     */
    private function getBuffer($var, $label = '') :string
    {
        ob_start();
        osd($var, $label);
        $result = ob_get_contents();
        ob_clean();
        return $result;
    }

    public function writeFile($name = 'debug')
    {
        $file_name = "$name.html";
        $file = new File(WWW_ROOT . $file_name);
        $result = $file->write($this->_getBodyAsString(), 'w', true);
        debug($result ? "http://localhost/gpschedule/$file_name" : "failed to write $file_name");
        $file->close();
    }

    public function showJson()
    {
        debug(json_decode($this->_getBodyAsString()));
    }
    /**
     * add a "tested by: method-line#-value" node and debug a value
     *
     * @param string $method
     * @param int $line
     * @param string $string
     * @param $data
     */
    public function showResult(string $method, int $line, string $string, $data)
    {
        $method_name = explode('::', namespaceSplit($method)[1])[1];
//        $method_name = namespaceSplit($method)[1];
        $label = "$method_name line $line $string";

        debug([
            'tested by' => $label,
            'result' => $data,
        ]);
    }

}
