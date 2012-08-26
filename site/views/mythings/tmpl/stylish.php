<?php
/**
 * Joomla! 2.5 - Erweiterungen programmieren
 * MyThings alternative Listenansicht (stylish)
 *
 * @package    Mythings
 * @subpackage Frontend
 * @author     webmechanic.biz
 * @license	   GNU/GPL
 */
defined('_JEXEC') or die;

/* Stylesheet aus dem media-Ordner einbinden */
JFactory::getDocument()->addStyleSheet( JURI::base(true) . 'media/mythings/css/mythings.css');

/* falls angemeldet, werden die ausgeliehenen Things hervorgehoben */
$user = JFactory::getUser();

/* Das Null-Datum der Datenbank, als Vergleichswert */
$nullDate = JFactory::getDbo()->getNullDate();

/* Nach dieser Spalte wird die Tabelle sortiert */
$listOrder = $this->escape($this->state->get('list.ordering'));

/* Die Sortierrichtung - aufsteigend oder absteigend */
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<div class="mythings <?php echo count($this->items) ? '' : 'no-things' ?>">
	<h2><span><?php echo JText::_('COM_MYTHINGS_SLOGAN') ?></span></h2>

<?php if (count($this->items)) { ?>
<table class="things">
	<thead>
	<tr>
		<th class="title"><?php echo JHtml::_('grid.sort', 'COM_MYTHINGS_TITLE', 'a.title', $listDirn, $listOrder) ?></th>
		<th class="category"><?php echo JHtml::_('grid.sort', 'COM_MYTHINGS_CATEGORY', 'category', $listDirn, $listOrder) ?></th>
		<th class="lentby"><?php echo JHtml::_('grid.sort', 'COM_MYTHINGS_LENT_BY', 'lent_by', $listDirn, $listOrder) ?></th>
		<th class="date"><?php echo JHtml::_('grid.sort', 'COM_MYTHINGS_LENT', 'lent_from', $listDirn, $listOrder) ?></th>
	</tr>
	</thead>
	<tbody class="thing-list">
<?php foreach ($this->items as $item) {
	// Hilfsvariablen
	$link   = JRoute::_("index.php?option=com_mythings&view=mything&id=" . (int) $item->id);
	$avail  = ($item->lent_from == $nullDate)
			? 'avail' : 'unavail';
	$mine   = ($item->lent_by == $user->username)
			? 'mine' : '';
	$lent   = ($item->lent_from != $nullDate)
			? JHtml::_('date', $item->lent_from, 'DATE_FORMAT_LC4')
			: '&nbsp;';
?>
	<tr class="thing-item <?php echo "{$avail} {$mine}" ?>">
		<td class="title"><a href="<?php echo $link ?>"><?php echo $this->escape($item->title) ?></a></td>
		<td class="category"><?php echo $this->escape($item->category) ?></td>
		<td class="lentby"><?php echo $this->escape($item->lent_by) ?></td>
		<td class="date"><?php echo $lent ?></td>
	</tr>
<?php } ?>
	</tbody>
</table>

<form action="<?php echo JRoute::_('index.php?option=com_mythings&view=mythings') ?>"
      method="post" name="adminForm" id="adminForm">
	<div class="pagination"><?php echo $this->pagination->getListFooter() ?></div>

	<input type="hidden" name="layout" value="stylish" />

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
	<?php echo JHtml::_('form.token') ?>
</form>
<?php } ?>

</div>
