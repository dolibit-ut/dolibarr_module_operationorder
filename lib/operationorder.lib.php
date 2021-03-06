<?php
/* Copyright (C) 2020 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\file		lib/operationorder.lib.php
 *	\ingroup	operationorder
 *	\brief		This file is an example module library
 *				Put some comments here
 */

/**
 * @return array
 */

dol_include_once('operationorder/class/operationorderuserplanning.class.php');

function operationorderAdminPrepareHead()
{
    global $langs, $conf;

    $langs->load('operationorder@operationorder');

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/operationorder/admin/operationorder_setup.php", 1);
    $head[$h][1] = $langs->trans("Parameters");
    $head[$h][2] = 'settings';
    $h++;

    $head[$h][0] = dol_buildpath("/operationorder/admin/operationorder_extrafields.php", 1);
    $head[$h][1] = $langs->trans("ExtraFields");
    $head[$h][2] = 'extrafields';
	$h++;

	$head[$h][0] = dol_buildpath("/operationorder/admin/operationorderjoursoff_setup.php", 1);
	$head[$h][1] = $langs->trans("oojoursOff");
	$head[$h][2] = 'oojoursOff';
	$h++;

	$head[$h][0] = dol_buildpath("/operationorder/admin/fullcalendar_setup.php", 1);
	$head[$h][1] = $langs->trans("Planning");
	$head[$h][2] = 'fullcalendar';
	$h++;

	if (!empty($conf->multicompany->enabled))
	{
		$head[$h][0] = dol_buildpath("/operationorder/admin/multicompany_sharing.php", 1);
		$head[$h][1] = $langs->trans("multicompanySharing");
		$head[$h][2] = 'multicompanySharing';
		$h++;
	}

    $head[$h][0] = dol_buildpath("/operationorder/admin/operationorder_about.php", 1);
    $head[$h][1] = $langs->trans("About");
    $head[$h][2] = 'about';
    $h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    //$this->tabs = array(
    //	'entity:+tabname:Title:@operationorder:/operationorder/mypage.php?id=__ID__'
    //); // to add new tab
    //$this->tabs = array(
    //	'entity:-tabname:Title:@operationorder:/operationorder/mypage.php?id=__ID__'
    //); // to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'operationorder');

    return $head;
}


/**
 * @return array
 */
function operationorderStatusAdminPrepareHead()
{
	global $langs, $conf, $db;

	$object = new OperationOrderStatus($db);

	$langs->load('operationorder@operationorder');

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/operationorder/admin/operationorderstatus_setup.php", 1);
	$head[$h][1] = $langs->trans("Parameters");
	$head[$h][2] = 'settings';
	$h++;


	complete_head_from_modules($conf, $langs, $object, $head, $h, 'operationorderstatus');

	return $head;
}


/**
 * Return array of tabs to used on pages for third parties cards.
 *
 * @param 	OperationOrder	$object		Object company shown
 * @return 	array				Array of tabs
 */
function operationorder_prepare_head(OperationOrder $object)
{
    global $db, $langs, $conf;

    $langs->load("operationorder@operationorder");

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/operationorder/operationorder_card.php", 1).'?id='.$object->id;
    $head[$h][1] = $langs->trans("OperationOrderCard");
    $head[$h][2] = 'card';
    $h++;

    if (isset($object->fields['note_public']) || isset($object->fields['note_private']))
    {
        $nbNote = 0;
        if (!empty($object->note_private)) $nbNote++;
        if (!empty($object->note_public)) $nbNote++;
        $head[$h][0] = dol_buildpath('/operationorder/note.php', 1).'?id='.$object->id;
        $head[$h][1] = $langs->trans('Notes');
        if ($nbNote > 0) $head[$h][1].= '<span class="badge marginleftonlyshort">'.$nbNote.'</span>';
        $head[$h][2] = 'note';
        $h++;
    }

    require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
    require_once DOL_DOCUMENT_ROOT.'/core/class/link.class.php';
    $upload_dir = $conf->operationorder->dir_output . "/operationorder/" . dol_sanitizeFileName($object->ref);
    $nbFiles = count(dol_dir_list($upload_dir, 'files', 0, '', '(\.meta|_preview.*\.png)$'));
    $nbLinks=Link::count($db, $object->element, $object->id);
    $head[$h][0] = dol_buildpath("/operationorder/document.php", 1).'?id='.$object->id;
    $head[$h][1] = $langs->trans('Documents');
    if (($nbFiles+$nbLinks) > 0) $head[$h][1].= '<span class="badge marginleftonlyshort">'.($nbFiles+$nbLinks).'</span>';
    $head[$h][2] = 'document';
    $h++;

    $head[$h][0] = dol_buildpath("/operationorder/operationorder_agenda.php", 1).'?id='.$object->id;
    $head[$h][1] = $langs->trans("Events");
    $head[$h][2] = 'agenda';
    $h++;

    $head[$h][0] = dol_buildpath("/operationorder/operationorder_info.php", 1).'?id='.$object->id;
    $head[$h][1] = $langs->trans("OperationOrderInfo");
    $head[$h][2] = 'info';
    $h++;


    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    //$this->tabs = array(
    //	'entity:+tabname:Title:@operationorder:/operationorder/mypage.php?id=__ID__'
    //); // to add new tab
    //$this->tabs = array(
    //	'entity:-tabname:Title:@operationorder:/operationorder/mypage.php?id=__ID__'
    //); // to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'operationorder@operationorder');

    complete_head_from_modules($conf, $langs, $object, $head, $h, 'operationorder@operationorder', 'remove');

    return $head;
}

/**
 * @param Form      $form       Form object
 * @param OperationOrder  $object     OperationOrder object
 * @param string    $action     Triggered action
 * @return string
 */
function getFormConfirmOperationOrder($form, $object, $action)
{
    global $langs, $user;

    $formconfirm = '';

    if ($action === 'setStatus' && !empty($user->rights->operationorder->write))
    {

		$fk_status = GETPOST('fk_status' , 'int');

		if(!empty($fk_status)){
			// vérification des droits
			$statusAllowed = new OperationOrderStatus($object->db);
			$res = $statusAllowed->fetch($fk_status);
			if($res>0 && $statusAllowed->userCan($user, 'changeToThisStatus')){
				$body = $langs->trans('ConfirmValidateOperationOrderStatusBody', $object->ref, $statusAllowed->label);
				$formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id.'&fk_status='.$fk_status, $langs->trans('ConfirmValidateOperationOrderStatusTitle', $statusAllowed->label), $body, 'confirm_setStatus', '', 0, 1);
			}else{
				setEventMessage($langs->trans('SetStatusStatusNotAllowed'), 'errors');
			}
		}
		else{
			setEventMessage($langs->trans('SetStatusStatusNotAllowed'), 'errors');
		}


         }
    elseif ($action === 'close' && !empty($user->rights->operationorder->write))
    {
        $body = $langs->trans('ConfirmCloseOperationOrderBody');
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmCloseOperationOrderTitle'), $body, 'confirm_close', '', 0, 1);
    }
    elseif ($action === 'modify' && !empty($user->rights->operationorder->write))
    {
        $body = $langs->trans('ConfirmModifyOperationOrderBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmModifyOperationOrderTitle'), $body, 'confirm_modify', '', 0, 1);
    }
    elseif ($action === 'delete' && !empty($user->rights->operationorder->write))
    {
        $body = $langs->trans('ConfirmDeleteOperationOrderBody');
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmDeleteOperationOrderTitle'), $body, 'confirm_delete', '', 0, 1);
    }
    elseif ($action === 'clone' && !empty($user->rights->operationorder->write))
    {
        $body = $langs->trans('ConfirmCloneOperationOrderBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmCloneOperationOrderTitle'), $body, 'confirm_clone', '', 0, 1);
    }
    elseif ($action == 'ask_deleteline')
    {
        $formconfirm = $form->formconfirm($_SERVER["PHP_SELF"].'?id='.$object->id.'&lineid='.GETPOST('lineid'), $langs->trans('DeleteProductLine'), $langs->trans('ConfirmDeleteProductLine'), 'confirm_deleteline', '', 0, 1);
    }

    return $formconfirm;
}



/**
 * Return array of tabs to used on pages for third parties cards.
 *
 * @param 	OperationOrderStatus	$object		Object company shown
 * @return 	array				Array of tabs
 */
function operationOrderStatusPrepareHead(OperationOrderStatus $object)
{
	global $db, $langs, $conf;

	$langs->load("operationorder@operationorder");

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/operationorder/operationorderstatus_card.php", 1).'?id='.$object->id;
	$head[$h][1] = $langs->trans("OperationOrderStatusCard");
	$head[$h][2] = 'card';
	$h++;

	complete_head_from_modules($conf, $langs, $object, $head, $h, 'operationorder@operationorder');

	complete_head_from_modules($conf, $langs, $object, $head, $h, 'operationorder@operationorder', 'remove');

	return $head;
}



/**
 * @param Form      $form       Form object
 * @param OperationOrder  $object     OperationOrder object
 * @param string    $action     Triggered action
 * @return string
 */
function getFormConfirmOperationOrderStatus($form, $object, $action)
{
	global $langs, $user;

	$formconfirm = '';

	if ($action === 'valid' && !empty($user->rights->operationorder->write))
	{
		$body = $langs->trans('ConfirmValidateOperationOrderBody', $object->getRef());
		$formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmValidateOperationOrderTitle'), $body, 'confirm_validate', '', 0, 1);
	}
	elseif ($action === 'modify' && !empty($user->rights->operationorder->write))
	{
		$body = $langs->trans('ConfirmModifyOperationOrderBody', $object->ref);
		$formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmModifyOperationOrderTitle'), $body, 'confirm_modify', '', 0, 1);
	}
	elseif ($action === 'delete' && !empty($user->rights->operationorder->write))
	{
		$body = $langs->trans('ConfirmDeleteOperationOrderBody');
		$formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmDeleteOperationOrderTitle'), $body, 'confirm_delete', '', 0, 1);
	}


	return $formconfirm;
}


/**
 * Return an object
 *
 * @param 	string	$objecttype		Type of object ('invoice', 'order', 'expedition_bon', 'myobject@mymodule', ...)
 * @param 	int		$withpicto		Picto to show
 * @param 	string	$option			More options
 * @return	Commonobject			object id/type
 */
function OperationOrderObjectAutoLoad($objecttype, &$db)
{
	global $conf, $langs;

	$ret = -1;
	$regs = array();

	// Parse $objecttype (ex: project_task)
	$module = $myobject = $objecttype;

	// If we ask an resource form external module (instead of default path)
	if (preg_match('/^([^@]+)@([^@]+)$/i', $objecttype, $regs)) {
		$myobject = $regs[1];
		$module = $regs[2];
	}


	if (preg_match('/^([^_]+)_([^_]+)/i', $objecttype, $regs))
	{
		$module = $regs[1];
		$myobject = $regs[2];
	}

	// Generic case for $classpath
	$classpath = $module.'/class';

	// Special cases, to work with non standard path
	if ($objecttype == 'facture' || $objecttype == 'invoice') {
		$classpath = 'compta/facture/class';
		$module='facture';
		$myobject='facture';
	}
	elseif ($objecttype == 'commande' || $objecttype == 'order') {
		$classpath = 'commande/class';
		$module='commande';
		$myobject='commande';
	}
	elseif ($objecttype == 'propal')  {
		$classpath = 'comm/propal/class';
	}
	elseif ($objecttype == 'supplier_proposal')  {
		$classpath = 'supplier_proposal/class';
	}
	elseif ($objecttype == 'shipping') {
		$classpath = 'expedition/class';
		$myobject = 'expedition';
		$module = 'expedition_bon';
	}
	elseif ($objecttype == 'delivery') {
		$classpath = 'livraison/class';
		$myobject = 'livraison';
		$module = 'livraison_bon';
	}
	elseif ($objecttype == 'contract') {
		$classpath = 'contrat/class';
		$module='contrat';
		$myobject='contrat';
	}
	elseif ($objecttype == 'member') {
		$classpath = 'adherents/class';
		$module='adherent';
		$myobject='adherent';
	}
	elseif ($objecttype == 'cabinetmed_cons') {
		$classpath = 'cabinetmed/class';
		$module='cabinetmed';
		$myobject='cabinetmedcons';
	}
	elseif ($objecttype == 'fichinter') {
		$classpath = 'fichinter/class';
		$module='ficheinter';
		$myobject='fichinter';
	}
	elseif ($objecttype == 'task') {
		$classpath = 'projet/class';
		$module='projet';
		$myobject='task';
	}
	elseif ($objecttype == 'stock') {
		$classpath = 'product/stock/class';
		$module='stock';
		$myobject='stock';
	}
	elseif ($objecttype == 'inventory') {
		$classpath = 'product/inventory/class';
		$module='stock';
		$myobject='inventory';
	}
	elseif ($objecttype == 'mo') {
		$classpath = 'mrp/class';
		$module='mrp';
		$myobject='mo';
	}

	// Generic case for $classfile and $classname
	$classfile = strtolower($myobject); $classname = ucfirst($myobject);
	//print "objecttype=".$objecttype." module=".$module." subelement=".$subelement." classfile=".$classfile." classname=".$classname;

	if ($objecttype == 'invoice_supplier') {
		$classfile = 'fournisseur.facture';
		$classname = 'FactureFournisseur';
		$classpath = 'fourn/class';
		$module = 'fournisseur';
	}
	elseif ($objecttype == 'order_supplier') {
		$classfile = 'fournisseur.commande';
		$classname = 'CommandeFournisseur';
		$classpath = 'fourn/class';
		$module = 'fournisseur';
	}
	elseif ($objecttype == 'stock') {
		$classpath = 'product/stock/class';
		$classfile = 'entrepot';
		$classname = 'Entrepot';
	}

	if (!empty($conf->$module->enabled))
	{

		$res = dol_include_once('/'.$classpath.'/'.$classfile.'.class.php');
		if ($res)
		{
			if (class_exists($classname)) {
				return new $classname($db);
			}
		}
	}
	return $ret;
}

/**
 * @param $object OperationOrder
 * @param $line OperationOrderDet
 * @param $showSubmitBtn bool
 * @return string
 */
function displayFormFieldsByOperationOrder($object, $line= false, $showSubmitBtn = true, $actionURL = false)
{
    global $langs, $db, $form, $hookmanager;

    $outForm = '';

    if($line && $line->id > 0){
        $action = 'edit';
    }
    else{

        $action = 'create';
        $line=new OperationOrderDet($db);
		$line->fk_operation_order = $object->id;
        // set default values
        $line->qty = '';
        $line->price = '';
    }

    if($actionURL)
    {
        $actionURL = $_SERVER["PHP_SELF"].'?id='.$object->id;

        // Ancors
        $actionURL .= ($action == 'create') ? '#addline' : '#item_'.$line->id;
    }
    else {
        $actionURL = '';
    }

	$parameters = array(
		'actionUrl' =>& $actionURL,
		'line' =>& $line
	);

	$reshook = $hookmanager->executeHooks('displayFormFieldsByOperationOrder', $parameters, $object, $action);

	if($reshook > 0){
		$outForm = $hookmanager->resPrint;
	}
    else{
		$outForm.=  ($action == 'create') ? '<a name="addline" ></a>':'';


		$outForm.= '<form name="addproduct" action="' . $actionURL .'" method="POST">' . "\n";
		$outForm.= '<input type="hidden" name="token" value="' . $_SESSION ['newtoken'] . '">' . "\n";
		$outForm.= '<input type="hidden" name="id" value="' . $object->id . '">' . "\n";
		$outForm.= '<input type="hidden" name="fk_parent_line" value="' . intval($line->fk_parent_line) . '">' . "\n";
		$outForm.= '<input type="hidden" name="mode" value="">' . "\n";

		if($action == 'edit') {
			$outForm .= '<input type="hidden" name="action" value="updateline">' . "\n";
			$outForm .= '<input type="hidden" name="save" value="1">' . "\n";
			$outForm .= '<input type="hidden" name="editline" value="'.$line->id.'">' . "\n";
			$outForm .= '<input type="hidden" name="lineid" value="'.$line->id.'">' . "\n";
		}else{
			$outForm .= '<input type="hidden" name="action" value="addline">' . "\n";
		}

		$line->fields = dol_sort_array($line->fields, 'position');


		$outForm.= '<table class="table-full">';

		// Display each line fields
		foreach($line->fields as $key => $val){
			$outForm.= getFieldCardOutputByOperationOrder($line, $key);
		}

		if($showSubmitBtn){

			$outForm.=  '<tr>';
			$outForm.=  '	<td colspan="2"><hr/></td>';
			$outForm.=  '</tr>';

			$outForm.=  '<tr>';
			$outForm.=  '	<td>';
			$outForm.=  '	</td>';
			$outForm.=  '	<td>';
			if($action == 'create'){
				$outForm.=  '<button type="submit" class="button" >'.$langs->trans('Add').'</button>';
			}else{
				$outForm.=  '<button type="submit" class="button" >'.$langs->trans('Save').'</button>';
			}
			$outForm.=  '	<button type="reset" class="button" >'.$langs->trans('Reset').'</button>';
			$outForm.=  '	</td>';
			$outForm.=  '</tr>';
		}

		$outForm.= '</table>';

		$outForm.= '</form>';
	}

	return $outForm;
}

/**
 * Return HTML string to show a field into a page
 * Code very similar with showOutputField of extra fields
 *
 * @param  CommonObject   $object		       Array of properties of field to show
 * @param  string  $key            Key of attribute
 * @param  string  $moreparam      To add more parametes on html input tag
 * @param  string  $keysuffix      Prefix string to add into name and id of field (can be used to avoid duplicate names)
 * @param  string  $keyprefix      Suffix string to add into name and id of field (can be used to avoid duplicate names)
 * @param  mixed   $morecss        Value for css to define size. May also be a numeric.
 * @param  int	   $nonewbutton   Force to not show the new button on field that are links to object
 * @return string
 */
function getFieldCardOutputByOperationOrder($object, $key, $moreparam = '', $keysuffix = '', $keyprefix = '', $morecss = '', $nonewbutton = 0, $params = array()){

    global $langs, $form;

    $val = $object->fields[$key];

    // Discard if extrafield is a hidden field on form
    if (abs($val['visible']) != 1 && abs($val['visible']) != 3) return;

    $mode = 'edit'; // edit or view

    // for some case if you need to change display mode
    if($key == 'xxxxxx') {
        $mode = 'view';
    }

    if (array_key_exists('enabled', $val) && isset($val['enabled']) && ! verifCond($val['enabled'])) return;	// We don't want this field

    $outForm=  '<tr id="field_'.$key.'">';
    $outForm.=  '<td';
    $outForm.=  ' class="titlefieldcreate';
    if ($val['notnull'] > 0) $outForm.=  ' fieldrequired';
    if ($val['type'] == 'text' || $val['type'] == 'html') $outForm.=  ' tdtop';
    $outForm.=  '"';
    $outForm.=  '>';

    if (!empty($val['help'])) $outForm.=  $form->textwithpicto($langs->trans($val['label']), $langs->trans($val['help']));
    else $outForm.=  $langs->trans($val['label']);
    $outForm.=  '</td>';

    $outForm.=  '<td>';

    // Load value from object
    $value = '';
    if(isset($object->{$key})){
        $value = $object->{$key};
    }

    if(GETPOSTISSET($key)){
        if (in_array($val['type'], array('int', 'integer'))) $value = GETPOST($key, 'int');
        elseif ($val['type'] == 'text' || $val['type'] == 'html') $value = GETPOST($key, 'none');
        else $value = GETPOST($key, 'alpha');
    }

    if(!empty($val['fieldCallBack']) && is_callable($val['fieldCallBack'])){
        $outForm.=  call_user_func ($val['fieldCallBack'], $object, $val, $key, $value, $moreparam, $keysuffix, $keyprefix, $morecss, $nonewbutton, $params);
    }else{
        if($mode == 'edit'){
            $outForm.=  $object->showInputField($val, $key, $value, $moreparam, $keysuffix, $keyprefix, $morecss, $nonewbutton);
        }
        else{
            $outForm.=  $object->showOutputField($val, $key, $value, $moreparam, $keysuffix, $keyprefix, $morecss, $nonewbutton);
        }
    }

    $outForm.=  '</td>';

    $outForm.=  '</tr>';

    return $outForm;
}

/**
 * Add line and reccursive child to an OR
 *
 * @param  OperationOrder  $object
 * @param  int  $fk_product
 * @param  int  $qty
 * @param  int  $type
 * @param  string  $product_desc
 * @param  mixed   $predef
 * @param  int	   $time_plannedhour
 * @param  int	   $time_plannedmin
 * @param  int	   $time_spenthour
 * @param  int	   $time_spentmin
 * @param  int	   $fk_warehouse
 * @param  int	   $date_start
 * @param  int	   $date_end
 * @param  string  $product_label
 * @return int > 0 if OK, < 0 if KO
 */

function addLineAndChildToOR ($object, $fk_product, $qty, $price, $type, $product_desc = '', $predef = '', $time_plannedhour = '', $time_plannedmin = '', $time_spenthour = '', $time_spentmin = '', $fk_warehouse = '', $pc = '', $date_start, $date_end, $product_label = ''){

    global $langs, $db, $conf;

    $error = 0;

    $qty = price2num($qty);
    $time_planned = $time_plannedhour * 60 * 60 + $time_plannedmin * 60; // store in seconds
    $time_spent = $time_spenthour * 60 * 60 + $time_spentmin * 60;

    // Extrafields
    $extrafields = new ExtraFields($db);
    $extralabelsline = $extrafields->fetch_name_optionals_label($object->table_element_line);
    $array_options = $extrafields->getOptionalsFromPost($object->table_element_line, $predef);
    // Unset extrafield
    if (is_array($extralabelsline)) {
        // Get extra fields
        foreach ($extralabelsline as $key => $value) {
            unset($_POST["options_".$key]);
        }
    }

    if (empty($fk_product) && $type < 0) {
        setEventMessages($langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv('Type')), null, 'errors');
        $error++;
    }
    if ($qty == '') {
        setEventMessages($langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv('Qty')), null, 'errors');
        $error++;
    }
    if (empty($fk_product)) {
        setEventMessages($langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv('Product')), null, 'errors');
        $error++;
    }

    if (empty($error) && $qty >= 0 && $fk_product) {

            $prod = new Product($db);
            $prod->fetch($fk_product);

            $label = (($product_label && $product_label != $prod->label) ? $product_label : '');

            $desc = '';

            // Define output language
            if (!empty($conf->global->MAIN_MULTILANGS) && !empty($conf->global->PRODUIT_TEXTS_IN_THIRDPARTY_LANGUAGE)) {
                $outputlangs = $langs;
                $newlang = '';
                if (empty($newlang) && GETPOST('lang_id', 'aZ09'))
                    $newlang = GETPOST('lang_id', 'aZ09');
                if (empty($newlang))
                    $newlang = $object->thirdparty->default_lang;
                if (!empty($newlang)) {
                    $outputlangs = new Translate("", $conf);
                    $outputlangs->setDefaultLang($newlang);
                }

                $desc = (!empty($prod->multilangs [$outputlangs->defaultlang] ["description"])) ? $prod->multilangs [$outputlangs->defaultlang] ["description"] : $prod->description;
            } else {
                $desc = $prod->description;
            }

            if (!empty($product_desc) && !empty($conf->global->MAIN_NO_CONCAT_DESCRIPTION)) $desc = $product_desc;
            else $desc = dol_concatdesc($desc, $product_desc, '', !empty($conf->global->MAIN_CHANGE_ORDER_CONCAT_DESCRIPTION));

            $type = $prod->type;

        $desc = dol_htmlcleanlastbr($desc);

        $info_bits = 0;

        // Insert line
        $result = $object->addline($desc, $qty, $price, $fk_warehouse, $pc, $time_planned, $time_spent, $fk_product, $info_bits, $date_start, $date_end, $type, -1, 0, GETPOST('fk_parent_line'), $label, $array_options, '', 0);

        if ($result > 0) {

            $recusiveAddResult = $object->recurciveAddChildLines($result,$fk_product, $qty);

            if($recusiveAddResult<0)
            {
                $error++;
                setEventMessage($langs->trans('ErrorsOccuredDuringLineChildrenInsert').'<br>code error: '.$recusiveAddResult.'<br>'.$object->error, 'errors');
                if(!empty($this->errors)){
                    setEventMessages($this->errors, 'errors');
                }
            }

            $ret = $object->fetch($object->id); // Reload to get new records

            if($ret > 0) return $ret;
            else $error++;

        }
    } else {
        $error ++;
        setEventMessages($object->error, $object->errors, 'errors');
    }

    return (!empty($error)) ? -1 : 0;
}


/** Parses a string into a DateTime object, optionally forced into the given timezone.
 * @param $string
 * @param null $timezone
 * @return DateTime
 * @throws Exception
 */
function OO_parseFullCalendarDateTime($string, $timezone=null) {

	$date = new DateTime($string);
	if ($timezone) {
		// If our timezone was ignored above, force it.
		$date->setTimezone($timezone);
	}
	return $date;
}


/**
 * @param string $hex color in hex
 * @param integer $percent 0 to 100
 * @return string
 */
function OO_colorDarker($hex, $percent)
{
	$steps = intval(255 * $percent / 100) * -1;
	return OO_colorAdjustBrightness($hex, $steps);
}

/**
 * @param string $hex color in hex
 * @param integer $percent 0 to 100
 * @return string
 */
function OO_colorLighten($hex, $percent)
{
	$steps = intval(255 * $percent / 100);
	return OO_colorAdjustBrightness($hex, $steps);
}


/**
 * @param string $hex color in hex
 * @param integer $steps Steps should be between -255 and 255. Negative = darker, positive = lighter
 * @return string
 */
function OO_colorAdjustBrightness($hex, $steps)
{
	// Steps should be between -255 and 255. Negative = darker, positive = lighter
	$steps = max(-255, min(255, $steps));
	// Normalize into a six character long hex string
	$hex = str_replace('#', '', $hex);
	if (strlen($hex) == 3) {
		$hex = str_repeat(substr($hex, 0, 1), 2).str_repeat(substr($hex, 1, 1), 2).str_repeat(substr($hex, 2, 1), 2);
	}
	// Split into three parts: R, G and B
	$color_parts = str_split($hex, 2);
	$return = '#';
	foreach ($color_parts as $color) {
		$color   = hexdec($color); // Convert to decimal
		$color   = max(0, min(255, $color + $steps)); // Adjust color
		$return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
	}
	return $return;
}

/**
 * fonction retournant un tableau des operationsActions à générer
 * en fonction de la date de départ
 * et de la durée théorique ou forcée de l'OR
 * @param string $dateStart date de départ
 * @param string $TimeSpending durée théorique ou forcée de l'OR format 00:00
 *
 * @return array exemple avec appel getOperationOrderActionsArray("2020-05-29 15:00:00", "03:20") :
 * array(
 * 		'2020-05-29' => array (
 * 			'dateStart' => "2020-05-29 15:00:00"
 * 			'dateEnd'	=> "2020-05-29 17:00:00"
 * 			'timeSpent'	=> 7200 (en secondes)
 * 		)
 * 		'2020-04-13' => array (
 * 			'dateStart' => "2020-06-02 08:00:00"
 * 			'dateEnd'	=> "2020-06-02 09:20:00"
 * 			'timeSpent'	=> 4800 (en secondes)
 * 		)
 * 		'total' => array (
 * 			'dateStart' => "2020-05-29 15:00:00"
 * 			'dateEnd'	=> "2020-06-02 09:20:00"
 * 			'timeSpent'	=> 12000 (en secondes)
 * 			'timeSpentHours'	=> "03:20"
 * 			'excluded' 	=> array(
 * 				"2020-05-30" => "week end"
 * 				"2020-05-31" => "week end"
 * 				"2020-06-01" => "férié"
 * 			)
 * 		)
 * );
 */
function getOperationOrderActionsArray($dateStart = '', $TimeSpending = '')
{
	global $db, $conf;

	$results['total']['timeSpent'] = 0;
	$results['total']['timeSpentHours'] = 0;
	$results['total']['excluded'] = array();

	$THoraire = getTHoraire();
//	echo '<pre>'; print_r($THoraire);

	if (empty($dateStart) || empty($TimeSpending)) return $results;

	dol_include_once('/operationorder/class/operationorderjoursoff.class.php');
	dol_include_once('/core/lib/date.lib.php');
	$jourOff = new OperationOrderJoursOff($db);

	$testDate = strtotime($dateStart);
	$timeSpendingArray = explode(':', $TimeSpending);
	$timeSpendingSec = convertTime2Seconds($timeSpendingArray[0], $timeSpendingArray[1]);
	$remainingTime = $timeSpendingSec;

	$i=0;

	while ($results['total']['timeSpent'] < $timeSpendingSec && $i < 15)
	{
		$DayOfWeek = date("N", $testDate);
		$key = date("Y-m-d", $testDate);

		$dayKey = 'defaultHours';
		if (!empty($THoraire[$DayOfWeek]['boundings'])) $dayKey = $DayOfWeek;

		foreach ($THoraire[$dayKey]['boundings'] as $boundings)
		{
			$jourFerie = $jourOff->isOff(date("Y-m-d 00:00:00", $testDate));

			$testDayStart = strtotime(date("Y-m-d ".$boundings['startHour'].":00", $testDate));
			$testDayEnd = strtotime(date("Y-m-d ".$boundings['endHour'].":00", $testDate));

			if ($testDate < $testDayStart) $testDate = $testDayStart;

			if (
				$testDate >= $testDayEnd // départ après fermeture
				|| $DayOfWeek > 5// we
				|| $jourFerie// férié
			)
			{
				// passer au jour suivant

				if (!array_key_exists($key, $results['total']['excluded']))
				{
					$rejectMsg = '';
					if ($testDate >= $testDayEnd) $rejectMsg = "to late";
					else if ($DayOfWeek > 5) $rejectMsg = "week end";
					else if ($jourFerie) $rejectMsg = "férié";
					$results['total']['excluded'][$key] = $rejectMsg;
				}

				continue;
			}

			if ($testDate >= $testDayStart && $testDate < $testDayEnd) // on est dans les horaires
			{
				if (array_key_exists($key, $results['total']['excluded'])) unset($results['total']['excluded'][$key]);
				if (empty($results['total']['dateStart'])) $results['total']['dateStart'] = $results[$key]['dateStart'];
				$timeDiff = $testDayEnd-$testDate;

				$results[$key]['dateStart'] = date("Y-m-d H:i:s", $testDate);

				if ($timeDiff >= $remainingTime) // le temps dispo est suppérieur au temps demandé
				{
					$results[$key]['timeSpent']+= $remainingTime;
				}
				else
				{
					$results[$key]['timeSpent']+= $timeDiff;
				}
				$remainingTime-= $results[$key]['timeSpent'];
				$results[$key]['timeSpentHours'] = convertSecondToTime($results[$key]['timeSpent'], 'allhourmin');

				$results['total']['dateEnd'] = $results[$key]['dateEnd'] = date("Y-m-d H:i:s", $testDate + $results[$key]['timeSpent']);
				$results['total']['timeSpent'] += $results[$key]['timeSpent'];
				if (empty($remainingTime)) break 2;


			}
			else continue;

			if (empty($results['total']['dateStart'])) $results['total']['dateStart'] = $results[$key]['dateStart'];

		}

		$testDate = strtotime('+1 day', $testDate);
		$DayOfWeek = date("N", $testDate);
		$dayKey = 'defaultHours';
		if (!empty($THoraire[$DayOfWeek]['boundings'])) $dayKey = $DayOfWeek;

		$testDate = strtotime(date("Y-m-d ".$THoraire[$dayKey]['boundings'][0]['startHour'].":s", $testDate));


		$i++;
	}
	$results['total']['timeSpentHours'] = convertSecondToTime($results['total']['timeSpent'], 'allhourmin');
//	var_dump(array(date("Y-m-d", $testDate), $results['total']['timeSpent'] , $timeSpendingSec));

	return $results;
}

function getTHoraire()
{
	global $conf;

	$THoraire = array(
		'defaultHours' => array(
			'boundings' => array(
				array(
					'startHour' => "08:00",
					'endHour'	=> "17:00"
				)
			)

		)
	);

	$TConfDayOfWeek = array(
		'MAIN_INFO_OPENINGHOURS_MONDAY',
		'MAIN_INFO_OPENINGHOURS_TUESDAY',
		'MAIN_INFO_OPENINGHOURS_WEDNESDAY',
		'MAIN_INFO_OPENINGHOURS_THURSDAY',
		'MAIN_INFO_OPENINGHOURS_FRIDAY',
		'MAIN_INFO_OPENINGHOURS_SATURDAY',
		'MAIN_INFO_OPENINGHOURS_SUNDAY'
	);

	for ($i = 1; $i < 8; $i++)
	{
		if (!empty($conf->global->{$TConfDayOfWeek[$i-1]}))
		{
			$confTest = trim($conf->global->{$TConfDayOfWeek[$i-1]});
			$plages = explode(' ', $confTest);
			if (is_array($plages) && !empty($plages))
			{

				if (count($plages) > 1)
				{
					foreach ($plages as $str)
					{
						$boundings = explode ('-', $str);
						if (count($boundings) != 2) continue;

						if (strpos($boundings[0], 'h')) $boundings[0] = preg_replace('/h/', ':', $boundings[0]);
						if (!strpos($boundings[0], ':')) $boundings[0] = $boundings[0].':00';

						if (strpos($boundings[1], 'h')) $boundings[1] = preg_replace('/h/', ':', $boundings[1]);
						if (!strpos($boundings[1], ':')) $boundings[1] = $boundings[1].':00';

						$THoraire[$i]['boundings'][] = array(
							'startHour' => $boundings[0],
							'endHour'	=> $boundings[1]
						);
					}
				}
				else
				{
					$boundings = explode ('-', $plages[0]);
					if (count($boundings) != 2) continue;

					if (strpos($boundings[0], 'h')) $boundings[0] = preg_replace('/h/', ':', $boundings[0]);
					if (!strpos($boundings[0], ':')) $boundings[0] = $boundings[0].':00';

					if (strpos($boundings[1], 'h')) $boundings[1] = preg_replace('/h/', ':', $boundings[1]);
					if (!strpos($boundings[1], ':')) $boundings[1] = $boundings[1].':00';

					$THoraire[$i]['boundings'][] = array(
						'startHour' => $boundings[0],
						'endHour'	=> $boundings[1]
					);
				}
			}

		}
	}

	return $THoraire;
}

function createOperationOrderAction($startTime, $endTime, $allDay, $id_operationorder){

    global $langs, $db, $user, $conf;

    dol_include_once('/operationorder/class/operationorder.class.php');
    dol_include_once('/operationorder/class/operationorderaction.class.php');

    $error = 0;

    $db->begin();

    if(!empty($id_operationorder))
    {

        $operationorder = new OperationOrder($db);
        $res = $operationorder->fetch($id_operationorder);

        if ($res)
        {
            $action_or = new OperationOrderAction($db);

            $action_or->dated = $startTime;
            //OR temps forcé ou temps théorique ou rien
            if($operationorder->time_planned_f) $action_or->datef = $startTime + $operationorder->time_planned_f;
            else $action_or->datef = $startTime + $operationorder->time_planned_t;

            if (!empty($operationorder->time_planned_t) || !empty($operationorder->time_planned_f))
            {
                if (!empty($operationorder->time_planned_f)) $TRes = getOperationOrderActionsArray(date("Y-m-d H:i:s", $action_or->dated), convertSecondToTime($operationorder->time_planned_f));
                else $TRes = getOperationOrderActionsArray(date("Y-m-d H:i:s", $action_or->dated), convertSecondToTime($operationorder->time_planned_t));
                $action_or->datef = $TRes['total']['dateEnd'];
            }

            $action_or->fk_operationorder = $id_operationorder;
            $action_or->fk_user_author = $user->id;

            $res = $action_or->save($user);

            $operationorder = new OperationOrder($db);
            $res = $operationorder->fetch($id_operationorder);
            if(empty($operationorder->array_options)) $operationorder->fetch_optionals();
            $operationorder->planned_date = intval($action_or->dated);
            $operationorder->save($user);
            $fk_status = $conf->global->OPODER_STATUS_ON_PLANNED;

            $statusAllowed = new OperationOrderStatus($db);
            $res = $statusAllowed->fetch($fk_status);
            if ($res > 0 && $statusAllowed->userCan($user, 'changeToThisStatus'))
            {
                $res = $operationorder->setStatus($user, $fk_status);

                if($res < 0) $error++;
            } else {
                $error++;
            }
        }
        else
        {
            $error++;
        }
    } else {
        $error++;
    }

    if(!$error) {
        $db->commit();
        return 1;
    } else {
        $db->rollback();
        return -1;
    }
}

/**
 * Renvoie l'html qui permet l'affichage du planning utilisateur
 * @param object $object
 * @param string $object_type, "user" ou "usergroup"
 * @param string $action
 * @param integer $usercanmodify
 * @return string $out
 */

function getOperationOrderUserPlanningToDisplay($object, $object_type, $action = '', $usercanmodify = 0){

    global $langs, $db;

    $error = 0;
    $TDays = array('Monday'=> 'lundi', 'Tuesday' => 'mardi', 'Wednesday' => 'mercredi', 'Thursday' => 'jeudi', 'Friday' => 'vendredi', 'Saturday' => 'samedi', 'Sunday' => 'dimanche');

    $userplanning = new OperationOrderUserPlanning($db);
    $res = $userplanning->fetchByObject($object->id, $object_type);
    if($res < 0) $error ++;

    if(!$error)
    {

        $out = '';


        if($action != 'edit')
        {

            $out .= '<table width="100%" class="liste noborder nobottom">';
            $out .= '<tr class="liste_titre">';
            $out .= '<td>&nbsp</td>';
            $out .= '<td class="center">'.$langs->trans('MorningD').'</td>';
            $out .= '<td class="center">'.$langs->trans('MorningF').'</td>';
            $out .= '<td class="center">'.$langs->trans('AfternoonD').'</td>';
            $out .= '<td class="center">'.$langs->trans('AfternoonF').'</td>';
            $out .= '</tr>';

            foreach ($TDays as $key => $value)
            {

                $out .= '<tr>';

                //Title
                $out .= '<td>'.$langs->trans($key).'</td>';

                //MorningD
                $field = ''.$value.'_heuredam';
                $out .= '<td class="center">'.$userplanning->$field.'</td>';

                //MorningF
                $field = ''.$value.'_heurefam';
                $out .= '<td class="center">'.$userplanning->$field.'</td>';

                //AfternoonD
                $field = ''.$value.'_heuredpm';
                $out .= '<td class="center">'.$userplanning->$field.'</td>';

                //AfternoonF
                $field = ''.$value.'_heurefpm';
                $out .= '<td class="center">'.$userplanning->$field.'</td>';


                $out .= '</tr>';

            }

            $out .= '</table>';

            $out .= '<div class = "center">';

            if($usercanmodify) $out .= '<a class="butAction" href = "'.$_SERVER['PHP_SELF'].'?objectid='.$object->id.'&objecttype='.$object_type.'&action=edit">'.$langs->trans('Modify').'</a>';
            if(empty($userplanning->active)){
                if($usercanmodify) $out .= '<a class="butAction" href = "'.$_SERVER['PHP_SELF'].'?objectid='.$object->id.'&objecttype='.$object_type.'&action=activate">'.$langs->trans('Activate').'</a>';
            } else {
                if($usercanmodify) $out .= '<a class="butAction" href = "'.$_SERVER['PHP_SELF'].'?objectid='.$object->id.'&objecttype='.$object_type.'&action=disable">'.$langs->trans('Disable').'</a>';
            }

            $out .= '</div>';

        } else {


            $out .= '<form method="POST" action="'.$_SERVER['PHP_SELF'].'"';
            $out .= '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
            $out .= '<input type="hidden" name="action" value="save">';
            $out .= '<input type="hidden" name="objectid" value="'.$object->id.'">';
            $out .= '<input type="hidden" name="objecttype" value="'.$object_type.'">';

            $out .= '<table width="100%" class="liste noborder nobottom">';
            $out .= '<tr class="liste_titre">';
            $out .= '<td>&nbsp</td>';
            $out .= '<td>'.$langs->trans('MorningD').'</td>';
            $out .= '<td>'.$langs->trans('MorningF').'</td>';
            $out .= '<td>'.$langs->trans('AfternoonD').'</td>';
            $out .= '<td>'.$langs->trans('AfternoonF').'</td>';
            $out .= '</tr>';

            foreach ($TDays as $key => $value)
            {

                $out .= '<tr class="oddeven">';

                //Title
                $out .= '<td>'.$langs->trans($key).'</td>';

                $form=new TFormCore($_SERVER['PHP_SELF'],'form1','POST');

                //MorningD
                $field = ''.$value.'_heuredam';
                $out .= '<td>'.$form->timepicker('',$field, $userplanning->$field ,5,5, '', 'text', 'H:i', '12:00am', '12:00pm').'</td>';

                //MorningF
                $field = ''.$value.'_heurefam';
                $out .= '<td>'.$form->timepicker('',$field, $userplanning->$field ,5,5, '', 'text', 'H:i', '12:00am', '12:00pm').'</td>';

                //AfternoonD
                $field = ''.$value.'_heuredpm';
                $out .= '<td>'.$form->timepicker('',$field, $userplanning->$field ,5,5, '', 'text', 'H:i', '12:00pm', '12:00am').'</td>';

                //AfternoonF
                $field = ''.$value.'_heurefpm';
                $out .= '<td>'.$form->timepicker('',$field, $userplanning->$field ,5,5, '', 'text', 'H:i', '12:00pm', '12:00am').'</td>';


                $out .= '</tr>';

            }

            $out .= '</table>';

            $out .= '<div class="center"><input type="submit" class="button" name="save" value="'.$langs->trans('Save').'">';
            $out .= '</div>';
        }

    }

    if(!$error) return $out;
    else return -1;
}

/**
 * Renvoie le planning utilisateur / groupe utilisateur à appliquer si il existe en fonction de l'utilisateur et de l'entité
 * @return array si planning existe, 0 si inexistant, -1 si erreur
 */

function getOperationOrderUserPlanningSchedule(){

    require_once DOL_DOCUMENT_ROOT.'/user/class/usergroup.class.php';

    global $db, $conf;

    $TSchedules = array();
    $TSchedulesByUser = array();
    $TDays = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');

    //usergroup paramétré
    $fk_groupuser = $conf->global->OPERATION_ORDER_GROUPUSER_DEFAULTPLANNING;

    //initialisation userplanning
    $userplanning = new OperationOrderUserPlanning($db);

    if(!empty($fk_groupuser))
    {
        $usergroup = new UserGroup($db);
        $res = $usergroup->fetch($fk_groupuser);
        $TUsers = $usergroup->listUsersForGroup(); //TODO Verif par entité

        //userplanning en fonction des utilisateurs
        foreach ($TUsers as $user)
        {
            $res = $userplanning->fetchByObject($user->id, 'user');

            //si l'utilisateur a un planning actif alors on utilise son planning
            if ($res > 0 && $userplanning->active > 0)
            {
                $TSchedulesByUser[] = $userplanning;
            }
            //si l'utilisateur n'a pas de planning actif ou que le planning est inexistant alors on utilise son planning
            else {

                $res = $userplanning->fetchByObject($fk_groupuser, 'usergroup');

                if ($res > 0 && $userplanning->active > 0)
                {
                    $TSchedulesByUser[] = $userplanning;
                }

            }

            foreach ($TDays as $day)
            {
                $i = 0;

                foreach ($TSchedulesByUser as $userplanning)
                {
                    if(empty($userplanning->{$day.'_heuredam'})
                    && empty($userplanning->{$day.'_heurefam'})
                    && empty($userplanning->{$day.'_heuredpm'})
                    && empty($userplanning->{$day.'_heurefpm'}))
                        continue;

                    if(empty($userplanning->{$day.'_heuredam'})) $userplanning->{$day.'_heuredam'} = '00:00';
                    if(empty($userplanning->{$day.'_heurefam'})) $userplanning->{$day.'_heurefam'} = '00:00';
                    if(empty($userplanning->{$day.'_heuredpm'})) $userplanning->{$day.'_heuredpm'} = '00:00';
                    if(empty($userplanning->{$day.'_heurefpm'})) $userplanning->{$day.'_heurefpm'} = '00:00';

                    if (empty($TSchedules[$day]))
                    {
                        $TSchedules[$day][$i]['min'] = $userplanning->{$day.'_heuredam'};
                        $TSchedules[$day][$i]['max'] = $userplanning->{$day.'_heurefam'};
                        $i++;
                        $TSchedules[$day][$i]['min'] = $userplanning->{$day.'_heuredpm'};
                        $TSchedules[$day][$i]['max'] = $userplanning->{$day.'_heurefpm'};
                    }
                    else
                    {
                        $scheduletoaddam = true;
                        $scheduletoaddpm = true;

                        foreach($TSchedules[$day] as $schedule){

                            //si l'heure de début est inférieure au minimum et que l'heure de fin est contenue dans le créneau, alors on usurpe le minimum
                            if($userplanning->{$day.'_heuredam'} < $schedule['min'] && ($userplanning->{$day.'_heurefam'} <= $schedule['max'] && $userplanning->{$day.'_heurefam'} >= $schedule['min'])){
                                $schedule['min'] = $userplanning->{$day.'_heuredam'};
                                $scheduletoaddam = false;
                            }
                            elseif($userplanning->{$day.'_heuredpm'} < $schedule['min'] && ($userplanning->{$day.'_heurefpm'} <= $schedule['max'] && $userplanning->{$day.'_heurefpm'} >= $schedule['min'])){
                                $schedule['min'] = $userplanning->{$day.'_heuredpm'};
                                $scheduletoaddpm = false;
                            }

                            //si l'heure de fin est supérieure au maximum et que l'heure du début est contenue dans le créneau, alors on usurpe le maximum
                            elseif($userplanning->{$day.'_heurefam'} > $schedule['max'] && ($userplanning->{$day.'_heuredam'} >= $schedule['min'] && $userplanning->{$day.'_heuredam'} <= $schedule['max']))
                            {
                                $schedule['max'] = $userplanning->{$day.'_heurefam'};
                                $scheduletoaddam = false;

                            }
                            elseif($userplanning->{$day.'_heurefpm'} > $schedule['max'] && ($userplanning->{$day.'_heuredpm'} >= $schedule['min'] && $userplanning->{$day.'_heuredpm'} <= $schedule['max']))
                            {
                                $schedule['max'] = $userplanning->{$day.'_heurefpm'};
                                $scheduletoaddpm = false;

                            }

                            //si l'heure de fin est supérieure au maximum et que l'heure de début est inférieure au minimum alors on usurpe le min et le max
                            elseif($userplanning->{$day.'_heuredam'} <= $schedule['min'] && $userplanning->{$day.'_heurefam'} >= $schedule['max']){
                                $schedule['min'] = $userplanning->{$day.'_heuredam'};
                                $schedule['max'] = $userplanning->{$day.'_heurefam'};
                                $scheduletoaddam = false;

                            }
                            elseif($userplanning->{$day.'_heuredpm'} <= $schedule['min'] && $userplanning->{$day.'_heurefpm'} >= $schedule['max']){
                                $schedule['min'] = $userplanning->{$day.'_heuredpm'};
                                $schedule['max'] = $userplanning->{$day.'_heurefpm'};
                                $scheduletoaddpm = false;

                            }

                            elseif($userplanning->{$day.'_heuredam'} >= $schedule['min'] && $userplanning->{$day.'_heurefam'} <= $schedule['max']){
                                $scheduletoaddam = false;
                            }
                            elseif($userplanning->{$day.'_heuredpm'} >= $schedule['min'] && $userplanning->{$day.'_heurefpm'} <= $schedule['max']){
                                $scheduletoaddpm = false;
                            }

                        }

                        if($scheduletoaddam) {
                            $TSchedules[$day][] = array('min' => $userplanning->{$day.'_heuredam'}, 'max' => $userplanning->{$day.'_heurefam'});
                        }
                        elseif($scheduletoaddpm) {
                            $TSchedules[$day][] = array('min' => $userplanning->{$day.'_heuredpm'}, 'max' => $userplanning->{$day.'_heurefpm'});
                        }
                    }

                    $i++;
                }
            }

        }

//        //userplanning en fonction du groupe d'utilisateurs
//        if (empty($TSchedules))
//        {
//            $res = $userplanning->fetchByObject($fk_groupuser, 'usergroup');
//
//            if ($res > 0 && $userplanning->active > 0)
//            {
//                foreach ($userplanning->fields as $key => $value)
//                {
//                    $TSchedules[$key] = $userplanning->$key;
//                }
//            }
//        }
    }

    return $TSchedules;
}
