//<?php

/*
 *
 * Shopkeeper plugin group goods in cart
 * 
 * System event: OnSHKcartLoad
 * 
 * settings: &groupWrapTpl=group wrapper;string;cart-group-tpl
 */
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}
$e = &$modx->Event;
$groupWrapTpl = isset($groupWrapTpl) ? $groupWrapTpl : '';

if ($e->name == 'OnSHKcartLoad' && $GLOBALS['shkconf']['cartType']=="full"  && $groupWrapTpl) {
	require_once MODX_BASE_PATH."assets/snippets/shopkeeper/classes/class.shopkeeper.php";
	$shkconf = $GLOBALS['shkconf'];
	$shk = new Shopkeeper($modx, $shkconf);
	$output = "";
	$purchasesByGroup = array(); 
	$additByGroup = array();
	
	$thisPage = $modx->documentIdentifier;
	$this_page_url = is_int($thisPage) ? $modx->makeUrl($thisPage, '', '', 'full') : null;

  	$purchases = unserialize($_SESSION['purchases']);
  	$addit_params = !empty($_SESSION['addit_params']) ? unserialize($_SESSION['addit_params']) : array();
  	$inner = '';
	if ($purchases && count($purchases)){
		foreach ($purchases as $key => $purchase) {
			$purchasesByGroup[$purchase['tv_add']['shk_group']][] = $purchase;
			$additByGroup[$purchase['tv_add']['shk_group']][] = $addit_params[$key];
		}

		$purchases = array();
		$addit_params = array();
		$num = 0;
		foreach ($purchasesByGroup as $key => $group_temp) {
			$group = array();
			foreach ($group_temp as $pkey => $purchase){				
				$purchases[] = $purchase;
				$addit_params[] = $additByGroup[$key][$pkey];
				$group[$num] = $purchase;
				$num++;
			}
			$innerGroup = $shk->getStuffList($group,$additByGroup[$key],'table',false,$this_page_url);
			$totalGroup = $shk->getTotal($group_temp,$additByGroup[$key]);
			$params['price_total'] = $totalGroup[1];
			$params['total_items'] = $totalGroup[0];
			$params['group'] = $innerGroup;
			$params['group_name'] = $key;
			$innerGroup = $modx->parseChunk($groupWrapTpl, $params,'[+','+]');
			$inner .= $innerGroup;			
		}
		$_SESSION['purchases'] = serialize($purchases);
		$_SESSION['addit_params'] = serialize($addit_params);
	}
	$e->output($inner);
}
//?>