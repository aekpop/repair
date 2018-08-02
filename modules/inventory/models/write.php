<?php
/**
 * @filesource modules/inventory/models/write.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Inventory\Write;

use Gcms\Login;
use Kotchasan\File;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * เพิ่ม/แก้ไข ข้อมูล Inventory.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * อ่านข้อมูลรายการที่เลือก
     * ถ้า $id = 0 หมายถึงรายการใหม่
     * คืนค่าข้อมูล object ไม่พบคืนค่า null.
     *
     * @param int  $id     ID
     * @param bool $new_id true คืนค่า ID ของรายการใหม่ (สำหรับการบันทึก), false คืนค่า ID หากเป็นรายการใหม่
     *
     * @return object|null
     */
    public static function get($id, $new_id = false)
    {
        // Model
        $model = new static();
        if (empty($id)) {
            // ใหม่
            $id = $new_id ? $model->db()->getNextId($model->getTableName('inventory')) : 0;

            return (object) array(
                'id' => $id,
                'equipment' => '',
                'serial' => '',
                'picture' => '',
            );
        } else {
            // แก้ไข อ่านรายการที่เลือก
            return $model->db()->createQuery()
                ->from('inventory')
                ->where(array('id', $id))
                ->first();
        }
    }

    /**
     * บันทึกข้อมูลที่ส่งมาจากฟอร์ม write.php.
     *
     * @param Request $request
     */
    public function submit(Request $request)
    {
        $ret = array();
        // session, token, can_manage_inventory
        if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
            if (Login::notDemoMode($login) && Login::checkPermission($login, 'can_manage_inventory')) {
                // ค่าที่ส่งมา
                $save = array(
                    'equipment' => $request->post('equipment')->topic(),
                    'serial' => $request->post('serial')->topic(),
                );
                foreach (Language::get('INVENTORY_CATEGORIES') as $key => $label) {
                    $save[$key] = $request->post($key)->toInt();
                }
                $id = $request->post('id')->toInt();
                // ตรวจสอบรายการที่เลือก
                $index = self::get($id, true);
                if (!$index) {
                    // ไม่พบ
                    $ret['alert'] = Language::get('Sorry, Item not found It&#39;s may be deleted');
                } elseif ($save['equipment'] == '') {
                    // ไม่ได้กรอก equipment
                    $ret['ret_equipment'] = 'Please fill in';
                } else {
                    if ($save['serial'] == '') {
                        // ไม่ได้กรอก serial
                        $ret['ret_serial'] = 'Please fill in';
                    } else {
                        // ค้นหา serial ซ้ำ
                        $search = $this->db()->first($this->getTableName('inventory'), array('serial', $save['serial']));
                        if ($search && ($index->id == 0 || $index->id != $search->id)) {
                            $ret['ret_serial'] = Language::replace('This :name already exist', array(':name' => Language::get('Serial/Registration number')));
                        }
                    }
                    if (empty($ret)) {
                        // อัปโหลดไฟล์
                        foreach ($request->getUploadedFiles() as $item => $file) {
                            /* @var $file \Kotchasan\Http\UploadedFile */
                            if ($file->hasUploadFile()) {
                                $dir = ROOT_PATH.DATA_FOLDER.'inventory/';
                                if (!File::makeDirectory($dir)) {
                                    // ไดเรคทอรี่ไม่สามารถสร้างได้
                                    $ret['ret_'.$item] = sprintf(Language::get('Directory %s cannot be created or is read-only.'), DATA_FOLDER.'inventory/');
                                } elseif (!$file->validFileExt(array('jpg', 'jpeg', 'png'))) {
                                    // ชนิดของไฟล์ไม่ถูกต้อง
                                    $ret['ret_'.$item] = Language::get('The type of file is invalid');
                                } elseif ($item == 'picture') {
                                    try {
                                        $file->resizeImage(array('jpg', 'jpeg', 'png'), $dir, $index->id.'.jpg', self::$cfg->inventory_w);
                                    } catch (\Exception $exc) {
                                        // ไม่สามารถอัปโหลดได้
                                        $ret['ret_'.$item] = Language::get($exc->getMessage());
                                    }
                                }
                            } elseif ($file->hasError()) {
                                // ข้อผิดพลาดการอัปโหลด
                                $ret['ret_'.$item] = Language::get($file->getErrorMessage());
                            }
                        }
                    }
                    if (empty($ret)) {
                        if ($id == 0) {
                            // ใหม่
                            $save['id'] = $index->id;
                            $save['create_date'] = date('Y-m-d H:i:s');
                            $this->db()->insert($this->getTableName('inventory'), $save);
                        } else {
                            // แก้ไข
                            $this->db()->update($this->getTableName('inventory'), $id, $save);
                        }
                        // คืนค่า
                        $ret['alert'] = Language::get('Saved successfully');
                        $ret['location'] = $request->getUri()->postBack('index.php', array('module' => 'inventory-setup'));
                    }
                }
            }
        }
        if (empty($ret)) {
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่าเป็น JSON
        echo json_encode($ret);
    }
}
