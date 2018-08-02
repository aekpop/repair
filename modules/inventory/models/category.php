<?php
/**
 * @filesource modules/inventory/models/category.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Inventory\Category;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * หมวดหมู่ของโรงเรียน.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * @var array
     */
    private $datas = array();

    /**
     * อ่านรายชื่อหมวดหมู่จากฐานข้อมูลตามภาษาปัจจุบัน
     * สำหรับการแสดงผล.
     *
     * @param string $type
     *
     * @return \static
     */
    public static function init($type)
    {
        // Model
        $model = new static();
        // Query
        $query = $model->db()->createQuery()
            ->select('id', 'category_id', 'topic')
            ->from('category')
            ->where(array('type', $type))
            ->order('category_id')
            ->toArray()
            ->cacheOn();
        foreach ($query->execute() as $item) {
            $model->datas[$item['category_id']] = array(
                'id' => $item['id'],
                'category_id' => $item['category_id'],
                'topic' => $item['topic'],
            );
        }

        return $model;
    }

    /**
     * อ่านหมวดหมู่สำหรับใส่ลงใน DataTable
     * ถ้าไม่มีคืนค่าข้อมูลเปล่าๆ 1 แถว.
     *
     * @param string $type
     *
     * @return array
     */
    public function toDataTable()
    {
        if (empty($this->datas)) {
            $this->datas = array(
                array('id' => 0, 'category_id' => 0, 'topic' => ''),
            );
        }

        return $this->datas;
    }

    /**
     * ลิสต์รายการหมวดหมู่
     * สำหรับใส่ลงใน select.
     *
     * @return array
     */
    public function toSelect()
    {
        $result = array();
        foreach ($this->datas as $category_id => $item) {
            $result[$category_id] = $item['topic'];
        }

        return $result;
    }

    /**
     * อ่านหมวดหมู่จาก $category_id
     * ไม่พบ คืนค่าว่าง.
     *
     * @param int $category_id
     *
     * @return string
     */
    public function get($category_id)
    {
        return isset($this->datas[$category_id]) ? $this->datas[$category_id]['topic'] : '';
    }

    /**
     * บันทึกหมวดหมู่.
     *
     * @param Request $request
     */
    public function submit(Request $request)
    {
        $ret = array();
        // session, referer, can_config
        if ($request->initSession() && $request->isReferer() && $login = Login::isMember()) {
            if (Login::notDemoMode($login) && Login::checkPermission($login, 'can_config')) {
                // ค่าที่ส่งมา
                $type = $request->post('type')->topic();
                $save = array();
                $category_exists = array();
                foreach ($request->post('category_id')->toInt() as $key => $value) {
                    if (isset($category_exists[$value])) {
                        $ret['ret_category_id_'.$key] = Language::replace('This :name already exist', array(':name' => 'ID'));
                    } else {
                        $category_exists[$value] = $value;
                        $save[$key]['category_id'] = $value;
                    }
                }
                foreach ($request->post('topic')->topic() as $key => $value) {
                    if (isset($save[$key])) {
                        $save[$key]['topic'] = $value;
                    }
                }
                if (empty($ret)) {
                    // ชื่อตาราง
                    $table_name = $this->getTableName('category');
                    // db
                    $db = $this->db();
                    // ลบข้อมูลเดิม
                    $db->delete($table_name, array('type', $type), 0);
                    // เพิ่มข้อมูลใหม่
                    foreach ($save as $item) {
                        if (isset($item['topic'])) {
                            $item['type'] = $type;
                            $db->insert($table_name, $item);
                        }
                    }
                    // คืนค่า
                    $ret['alert'] = Language::get('Saved successfully');
                    $ret['location'] = 'reload';
                }
            }
            // คืนค่า JSON
            echo json_encode($ret);
        }
    }
}
