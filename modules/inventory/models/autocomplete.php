<?php
/**
 * @filesource modules/inventory/models/autocomplete.php
 */

namespace Inventory\Autocomplete;

use Gcms\Login;
use Kotchasan\Http\Request;

/**
 * ค้นหาครุภัณฑ์สำหรับ autocomplete.
 */
class Model extends \Kotchasan\Model
{
    /**
     * ค้นหาครุภัณฑ์สำหรับ autocomplete
     * คืนค่าเป็น JSON.
     *
     * @param Request $request
     */
    public function find(Request $request)
    {
        if ($request->initSession() && $request->isReferer() && Login::isMember()) {
            // ข้อมูลที่ส่งมา
            if ($request->post('equipment')->exists()) {
                $search = $request->post('equipment')->topic();
                $order = 'equipment';
            } elseif ($request->post('serial')->exists()) {
               $search = $request->post('serial')->topic();
               $order = 'serial';
            } elseif ($request->post('equipment_number')->exists()) {
                $search = $request->post('equipment_number')->topic();
                $order = 'equipment_number';
            } elseif ($request->post('toll_id')->exists()) {
                $search = $request->post('toll_id')->topic();
                $order = 'toll_id';
            }        
            // query
            $query = $this->db()->createQuery()
                ->select('id inventory_id', 'equipment' , 'serial' , 'equipment_number')
                ->from('inventory')
                ->groupBy('inventory_id')
                //->select('id V.inventory_id', 'V.equipment' , 'V.serial' , 'V.equipment_number', 'T.nameToll')
                //->from('inventory V')
                //->join('toll T', 'LEFT', array('T.id', 'V.toll_id'))
                ->limit($request->post('count', 30)->toInt())
                ->toArray();
            if (isset($search)) {
                $query->where(array($order, 'LIKE', "%$search%"))->order($order);
            }
            $result = $query->execute();
            if (!empty($result)) {
                // คืนค่า JSON
                echo json_encode($result);
            }
        }
    }
}
