<?php
/**
 * @filesource modules/repair/models/home.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Home;

use Kotchasan\Database\Sql;

/**
 * โมเดลสำหรับอ่านข้อมูลแสดงในหน้า  Home.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * อ่านงานซ่อมใหม่.
     *
     * @return object
     */
    public static function getNew($login)
    {
        if (isset(self::$cfg->repair_first_status)) {
            $model = new static();
            $q1 = $model->db()->createQuery()
                ->select('repair_id', Sql::MAX('id', 'max_id'))
                ->from('repair_status')
                ->groupBy('repair_id');
            $search = $model->db()->createQuery()
                ->selectCount()
                ->from('repair_status S')
                ->join(array($q1, 'T'), 'LEFT', array(array('T.repair_id', 'S.repair_id'), array('S.id', 'T.max_id')))
                ->where(array('S.status', self::$cfg->repair_first_status))
                ->toArray()
                ->execute();
            if (!empty($search)) {
                return $search[0]['count'];
            }
        }

        return 0;
    }
}
