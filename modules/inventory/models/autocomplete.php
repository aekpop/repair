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
            }
            // query
            $query = $this->db()->createQuery()
                ->select('id inventory_id', 'equipment' , 'equipment_number' , 'serial' )
                //->select('id', 'equipment' , 'equipment_number' , 'serial' )
                ->from('inventory')
                ->limit($request->post('count', 20)->toInt())
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
