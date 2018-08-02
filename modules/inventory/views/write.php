<?php
/**
 * @filesource modules/inventory/views/write.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Inventory\Write;

use Kotchasan\Html;
use Kotchasan\Language;

/**
 * module=inventory-write.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * ฟอร์มสร้าง/แก้ไข เอกสาร.
     *
     * @param object $index
     * @param array  $login
     *
     * @return string
     */
    public function render($index, $login)
    {
        // form
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/inventory/model/write/submit',
            'onsubmit' => 'doFormSubmit',
            'ajax' => true,
            'token' => true,
        ));
        $fieldset = $form->add('fieldset', array(
            'title' => '{LNG_Details of} {LNG_Equipment}',
        ));
        // equipment
        $fieldset->add('text', array(
            'id' => 'equipment',
            'labelClass' => 'g-input icon-edit',
            'itemClass' => 'item',
            'label' => '{LNG_Equipment}',
            'placeholder' => '{LNG_Details of} {LNG_Equipment}',
            'maxlength' => 64,
            'value' => $index->equipment,
        ));
        // serial
        $fieldset->add('text', array(
            'id' => 'serial',
            'labelClass' => 'g-input icon-number',
            'itemClass' => 'item',
            'label' => '{LNG_Serial/Registration number}',
            'maxlength' => 20,
            'value' => $index->serial,
        ));
        foreach (Language::get('INVENTORY_CATEGORIES') as $key => $label) {
            $fieldset->add('select', array(
                'id' => $key,
                'labelClass' => 'g-input icon-category',
                'itemClass' => 'item',
                'label' => $label,
                'options' => \Inventory\Category\Model::init($key)->toSelect(),
                'value' => isset($index->{$key}) ? $index->{$key} : 0,
            ));
        }
        // picture
        if (is_file(ROOT_PATH.DATA_FOLDER.'inventory/'.$index->id.'.jpg')) {
            $img = WEB_URL.DATA_FOLDER.'inventory/'.$index->id.'.jpg';
        } else {
            $img = WEB_URL.'modules/inventory/img/noimage.png';
        }
        $fieldset->add('file', array(
            'id' => 'picture',
            'labelClass' => 'g-input icon-upload',
            'itemClass' => 'item',
            'label' => '{LNG_Image}',
            'comment' => '{LNG_Browse image uploaded, type :type} ({LNG_resized automatically})',
            'dataPreview' => 'imgPicture',
            'previewSrc' => $img,
        ));
        $fieldset = $form->add('fieldset', array(
            'class' => 'submit',
        ));
        // submit
        $fieldset->add('submit', array(
            'class' => 'button ok large',
            'value' => '{LNG_Save}',
        ));
        // id
        $fieldset->add('hidden', array(
            'id' => 'id',
            'value' => $index->id,
        ));
        \Gcms\Controller::$view->setContentsAfter(array(
            '/:type/' => 'jpg, jpeg, png',
        ));

        return $form->render();
    }
}
