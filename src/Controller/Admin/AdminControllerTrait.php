<?php
namespace App\Controller\Admin;

trait AdminControllerTrait
{
    /**
     * simple helper function to convert ini values like 10M or 256K to integer
     *
     * @param string $val
     * @return int
     */
    private function toBytes(string $val): int
    {
        $suffix = strtolower(substr(trim($val), -1));

        $v = (int) $val;

        switch ($suffix) {
            case 'g':
                $v *= 1024 * 1024 *1024;
                break;
            case 'm':
                $v *= 1024 * 1024;
                break;
            case 'k':
                $v *= 1024;
        }
        return $v;
    }
}
