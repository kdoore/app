<?php
$values = $object->getValues();
if (empty($values)):?>
<p class="empty">empty</p>
<?php else:
	echo ($rendererName == '@list') ? '<ol>' : '<ul>';
	foreach($values as $reference) {
		echo '<li>';
		$referenceHTML = false;
		if (is_object($reference) && (!is_null($reference->object))) {
			$referenceHTML = $reference->object->render( $context );
		}
		if ($referenceHTML !== false) {
			echo $referenceHTML;
		} else {
			if (is_object($reference) &&  isset($reference->id)) {
				$name = (!is_null($reference->object)) ? $reference->object->getName() : 'id:'.$reference->id;
				echo '<a href="?method=showObject&id='.$reference->id.'">'.htmlspecialchars($name).'</a>';
			} else {
				echo '<pre>';print_r($reference);echo '</pre>';
			}
		}
		echo '</li>';

	}

	echo ($rendererName == '@list') ? '</ol>' : '</ul>';
	?>
<?php endif;?>