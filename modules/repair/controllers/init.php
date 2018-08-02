<?php
/**
 * @filesource modules/repair/controllers/init.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Init;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * Init Module.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\KBase
{
    /**
     * ฟังก์ชั่นเริ่มต้นการทำงานของโมดูลที่ติดตั้ง
     * และจัดการเมนูของโมดูล.
     *
     * @param Request                $request
     * @param \Index\Menu\Controller $menu
     */
    public static function execute(Request $request, $menu, $login)
    {
        $submenus = array(
            array(
                'text' => '{LNG_Get a repair}',
                'url' => 'index.php?module=repair-receive',
            ),
        );
        if (Login::checkPermission($login, array('can_manage_repair', 'can_repair'))) {
            $submenus[] = array(
                'text' => '{LNG_Repair list}',
                'url' => 'index.php?module=repair-setup',
            );
        }
        // repair module
        $menu->add('module', '{LNG_Repair Jobs}', null, $submenus);
        // เมนูตั้งค่า
        $submenus = array();
        foreach (Language::get('REPAIR_CATEGORIES') as $key => $label) {
            $submenus[] = array(
                'text' => $label,
                'url' => 'index.php?module=repair-category&amp;type='.$key,
            );
        }
        $menu->add('settings', '{LNG_Repair}', null, $submenus);
    }

    /**
     * รายการ permission ของโมดูล.
     *
     * @param array $permissions
     *
     * @return array
     */
    public static function updatePermissions($permissions)
    {
        $permissions['can_manage_repair'] = '{LNG_Can manage repair}';
        $permissions['can_repair'] = '{LNG_Repairman}';

        return $permissions;
    }
}
