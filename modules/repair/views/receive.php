<?php
/**
 * @filesource modules/repair/views/receive.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Receive;

use Kotchasan\Html;

/**
 * เพิ่ม-แก้ไข แจ้งซ่อม
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * module=repair-receive.
     *
     * @param object $index
     *
     * @return string
     */
    public function render($index)
    {
        // form
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/repair/model/receive/submit',
            'onsubmit' => 'doFormSubmit',
            'ajax' => true,
            'token' => true,
        ));
        $fieldset = $form->add('fieldset', array(
            'title' => '{LNG_Repair job description}',
        ));
        $groups = $fieldset->add('groups', array(
            'comment' => '{LNG_Find equipment by} {LNG_Equipment}, {LNG_Serial/Registration number}',
        ));
        // equipment
        $groups->add('text', array(
            'id' => 'equipment',
            'labelClass' => 'g-input icon-edit',
            'itemClass' => 'width30',
            'label' => '{LNG_Equipment}',
            'maxlength' => 64,
            'value' => $index->equipment,
        ));
        // serial
        $groups->add('text', array(
            'id' => 'serial',
            'labelClass' => 'g-input icon-number',
            'itemClass' => 'width30',
            'label' => '{LNG_Serial/Registration number}',
            'maxlength' => 30,
            'value' => $index->serial,
        ));
<<<<<<< HEAD
        // equipment_number
=======
        // serial
>>>>>>> a2f6fb06bd8e0ae8e767b6adf1e4f9dbff8ae287
        $groups->add('text', array(
            'id' => 'equipment_number',
            'labelClass' => 'g-input icon-number',
            'itemClass' => 'width30',
            'label' => '{LNG_Equipment_number}',
<<<<<<< HEAD
            'maxlength' => 30,
=======
            'maxlength' => 20,
>>>>>>> a2f6fb06bd8e0ae8e767b6adf1e4f9dbff8ae287
            'value' => $index->equipment_number,
        ));
        // inventory_id
        $fieldset->add('hidden', array(
            'id' => 'inventory_id',
            'value' => $index->inventory_id,
        ));
        // job_description
        $fieldset->add('textarea', array(
            'id' => 'job_description',
            'labelClass' => 'g-input icon-file',
            'itemClass' => 'item',
            'label' => '{LNG_problems and repairs details}',
            'rows' => 5,
            'value' => $index->job_description,
        ));
        // id
        $fieldset->add('hidden', array(
            'id' => 'id',
            'value' => $index->id,
        ));
        // comment
        $fieldset->add('text', array(
            'id' => 'comment',
            'labelClass' => 'g-input icon-comments',
            'itemClass' => 'item',
            'label' => '{LNG_Comment}',
            'comment' => '{LNG_Note or additional notes}',
            'maxlength' => 255,
            'value' => $index->comment,
        ));
        // status_id
        $fieldset->add('select', array(
            'id' => 'status_id',
            'labelClass' => 'g-input icon-comments',
            'itemClass' => 'item',
            'label' => '{LNG_Status Report}',
            //'comment' => '{LNG_Note or additional notes status}',
            //'maxlength' => 50,
            'options' => \Repair\Status\Model::create()->toSelect(),
            'value' => $index->status_id,
        ));
        $fieldset = $form->add('fieldset', array(
            'class' => 'submit',
        ));
        // submit
        $fieldset->add('submit', array(
            'id' => 'save',
            'class' => 'button save large icon-save',
            'value' => '{LNG_Save}',
        ));
        $form->script('initRepairGet();');

        return $form->render();
    }
}
