<?php
/**
 * @filesource modules/inventory/models/setup.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Inventory\Setup;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * โมเดลสำหรับ (setup.php).
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * Query ข้อมูลสำหรับส่งให้กับ DataTable.
     *
     * @return \Kotchasan\Database\QueryBuilder
     */
    public static function toDataTable()
    {
        $query = static::createQuery()
            ->select('I.id' , 'I.equipment', 'I.equipment_number' , 'Y.nameToll' , 'Z.bthDirection' ,
            'A.bthNumber' , 'I.serial','I.category_id' , 'I.type_id' ,'I.model_id')
            ->from('inventory I')
            ->join('toll Y', 'LEFT' , array('Y.id', 'I.toll_id'))
            ->join('bth_direction Z', 'LEFT' , array('Z.id', 'I.bth_direction_id'))
            ->join('bthnumber A', 'LEFT', array('A.id' , 'I.bth_number_id'))   
            ;
            if (!empty($where)) {
                $query->where($where);
        }
        return $query;
    }
    /**
     * รับค่าจาก action.
     *
     * @param Request $request
     */
    public function action(Request $request)
    {
        $ret = array();
        // session, referer, can_manage_inventory
        if ($request->initSession() && $request->isReferer() && $login = Login::isMember()) {
            if (Login::notDemoMode($login) && Login::checkPermission($login, 'can_manage_inventory')) {
                // รับค่าจากการ POST
                $action = $request->post('action')->toString();
                // id ที่ส่งมา
                if (preg_match_all('/,?([0-9]+),?/', $request->post('id')->toString(), $match)) {
                    // Model
                    $model = new \Kotchasan\Model();
                    // ตาราง
                    $table = $model->getTableName('inventory');
                    if ($action === 'delete') {
                        // ลบ
                        $model->db()->delete($table, array('id', $match[1]), 0);
                        // reload
                        $ret['location'] = 'reload';
                    }
                }
            }
        }
        if (empty($ret)) {
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่า JSON
        echo json_encode($ret);
    }
}
