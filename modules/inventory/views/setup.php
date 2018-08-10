<?php
/**
 * @filesource modules/inventory/views/setup.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Inventory\Setup;

use Gcms\Login;
use Kotchasan\DataTable;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=inventory-setup.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * @var mixed
     */
    private $params;

    /**
     * ตารางรายชื่อสมาชิก
     *
     * @param Request $request
     * @param array   $login
     *
     * @return string
     */
    public function render(Request $request, $login)
    {
        $fields = array('id', 'equipment','equipment_number' , 'nameToll' , 'bthDirection' ,
         'bthNumber' , 'serial');
        $headers = array(
            'equipment' => array(
                'text' => '{LNG_Equipment}',
                'sort' => 'equipment',
            ),
            'equipment_number' => array(
                'text' => '{LNG_Equipment_number}',
                'sort' => 'equipment_number',
            ),
            'nameToll' => array(
                'text' => '{LNG_nameToll}',
            //    'sort' => 'toll_id',
            ),
            'bthDirection' => array(
                'text' => '{LNG_bthDirection}',
            //    'sort' => 'bth_direction_id',
            ),
            'bthNumber' => array(
                'text' => '{LNG_bthNumber}',
            //   'sort' => 'bth_number_id',
            ),
            'serial' => array(
                'text' => '{LNG_Serial/Registration number}',
                'sort' => 'serial',
            ),
        );
        $cols = array();
        $filters = array();
        foreach (Language::get('INVENTORY_CATEGORIES') as $type => $text) {
            $fields[] = $type;
            $headers[$type] = array(
                'text' => $text,
                'class' => 'center',
            );
            $cols[$type] = array('class' => 'center');
            $this->params[$type] = \Inventory\Category\Model::init($type);
            $filters[$type] = array(
                'name' => $type,
                'default' => 0,
                'text' => $text,
                'options' => array(0 => '{LNG_all items}') + $this->params[$type]->toSelect(),
                'value' => $request->request($type)->toInt(),
            );
        }
        $fields[] = '"" picture';
        $headers['picture'] = array(
            'text' => '{LNG_Image}',
            'class' => 'center',
        );
        $cols['picture'] = array('class' => 'center');
        // URL สำหรับส่งให้ตาราง
        $uri = $request->createUriWithGlobals(WEB_URL.'index.php');
        // ตาราง
        $table = new DataTable(array(
            /* Uri */
            'uri' => $uri,
            /* Model */
            'model' => \Inventory\Setup\Model::toDataTable(),
            /* ฟิลด์ที่กำหนด (หากแตกต่างจาก Model) */
            'fields' => $fields,
            /* รายการต่อหน้า */
            'perPage' => $request->cookie('inventory_perPage', 30)->toInt(),
            /* เรียงลำดับ */
            'sort' => $request->cookie('inventory_sort', 'id desc')->toString(),
            /* ฟังก์ชั่นจัดรูปแบบการแสดงผลแถวของตาราง */
            'onRow' => array($this, 'onRow'),
            /* คอลัมน์ที่ไม่ต้องแสดงผล */
            'hideColumns' => array('id'),
            /* คอลัมน์ที่สามารถค้นหาได้ */
            'searchColumns' => array('equipment', 'equipment_number', 'serial' ),
            /* ตั้งค่าการกระทำของของตัวเลือกต่างๆ ด้านล่างตาราง ซึ่งจะใช้ร่วมกับการขีดถูกเลือกแถว */
            'action' => 'index.php/inventory/model/setup/action',
            'actionCallback' => 'dataTableActionCallback',
            'actions' => array(
                array(
                    'id' => 'action',
                    'class' => 'ok',
                    'text' => '{LNG_With selected}',
                    'options' => array(
                        'delete' => '{LNG_Delete}',
                    ),
                ),
            ),
            /* ตัวเลือกด้านบนของตาราง ใช้จำกัดผลลัพท์การ query */
            'filters' => $filters,
            /* ส่วนหัวของตาราง และการเรียงลำดับ (thead) */
            'headers' => $headers,
            /* รูปแบบการแสดงผลของคอลัมน์ (tbody) */
            'cols' => $cols,
            /* ปุ่มแสดงในแต่ละแถว */
            'buttons' => array(
                array(
                    'class' => 'hidden',
                    'href' => $uri->createBackUri(array('module' => 'inventory-write', 'id' => ':id')),
                    'text' => '{LNG_Edit}',
                ),
            ),
        ));
        // save cookie
        setcookie('inventory_perPage', $table->perPage, time() + 3600 * 24 * 365, '/');
        setcookie('inventory_sort', $table->sort, time() + 3600 * 24 * 365, '/');

        return $table->render();
    }

    /**
     * จัดรูปแบบการแสดงผลในแต่ละแถว.
     *
     * @param array $item
     *
     * @return array
     */
    public function onRow($item, $o, $prop)
    {
        $thumb = is_file(ROOT_PATH.DATA_FOLDER.'inventory/'.$item['id'].'.jpg') ? WEB_URL.DATA_FOLDER.'inventory/'.$item['id'].'.jpg' : WEB_URL.'modules/inventory/img/noimage.png';
        $item['picture'] = '<img src="'.$thumb.'" style="max-height:50px;max-width:50px" alt=thumbnail>';
        foreach ($this->params as $key => $obj) {
            $item[$key] = $obj->get($item[$key]);
        }

        return $item;
    }
}
