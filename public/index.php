<?php
/**
 * This file is part of Ticketconverter.
 *
 * @category developer tool
 * @package ticketconverter
 *
 * @author Christoph Jaecks <christoph.jaecks@fashionforhome.de>
 * @author Claudia Hüttenrauch <claudia.hüttenrauch@fashionforhome.de>
 * @author Tino Stöckel <tino.stoeckel@fashionforhome.de>
 *
 * @copyright (c) 2015 by fashion4home GmbH <www.fashionforhome.de>
 * @license GPL-3.0
 * @license http://opensource.org/licenses/GPL-3.0 GNU GENERAL PUBLIC LICENSE
 *
 * @version 1.0.0
 *
 * Date: 30.10.2015
 * Time: 01:30
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

session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Ticketconverter</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="shortcut icon" href="icon.ico" type="image/x-icon"/>
	<link rel="stylesheet" type="text/css" href="main.css"/>
	<script type="text/javascript" src="confirmation.js"></script>
</head>
<script>
	<?php if (isset($_SESSION['tickets_to_confirm'])) : ?>
	<?php $ticketsToConfirm = unserialize($_SESSION['tickets_to_confirm']); ?>
	<?php if (!empty($ticketsToConfirm['ticket_ids'])) : ?>

	window.onload = function()
	{
		var project = '<?php echo $ticketsToConfirm['project'] ?>';
		var ids = new Array();
		<?php foreach ($ticketsToConfirm['ticket_ids'] as $id) : ?>
		ids.push('<?php echo $id ?>');
		<?php endforeach; ?>
		var ticketConverter = new TicketConverter.Confirmation(
			project, ids, 'form'
		);

		ticketConverter.confirm();
	};
	<?php endif; ?>
	<?php endif; ?>
</script>

<body>
<div class="tc">
	<div class="header">
		<img class="left" src="images/main_logo.png" width="100"/>

		<h2>F4H Jira-TicketConverter <span>0.5</span></h2>
	</div>
	<form id="form" name="form" class="form" action="ticketconverter.php" method="post">
		<div class="form-data sbs">
			<select id="project" class="left" name="project">
				<option value="DMF" selected="selected">DMF</option>
				<option value="ERP">ERP</option>
				<option value="KAT">KAT</option>
				<option value="OPS">OPS</option>
				<option value="RAS">RAS</option>
				<option value="NAV">NAV</option>
				<option value="PRO">PRO</option>
			</select>
			<textarea id="ids" class="left" name="list" rows="15" cols="20" onKeyDown="onKeyDown(event)"></textarea>
			<ul class="left">
				<li>
					Projekt via <strong>Dropdown</strong> auswählen
				</li>
				<li>
					Ticket-Ids ohne Projektkürzel <strong>untereinander</strong> oder <strong>nebeneinander </strong>(mit
					Leerzeichen getrennt) eingeben<br/>
					<span>z.B. 4114 4794 4775</span>
				</li>
				<li>
					Absenden via Button oder <strong>Strg+Enter</strong>
				</li>
				<li>
					Tickets werden nach dem Absenden <strong>automatisch gedruckt</strong>
				</li>
				<li>
					Wurde ein Ticket schon einmal gedruckt, erscheint eine Meldung und <strong>das erneute Drucken muss
						mit 'OK' bestätigt werden</strong>.<br/>
					<span>Bitte etwas Geduld nach der Bestätigung haben.</span>
				</li>
			</ul>
		</div>
		<input type="submit" value="Absenden"/>
	</form>
</div>
<div class="messages">
	<div class="success">
		<?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])) : ?>
			<p>Folgende Tickets wurden gedruckt</p>
			<ul>
				<?php echo $_SESSION['success']; ?>
			</ul>
		<?php endif; ?>
	</div>
	<div class="notices">

		<?php if (isset($_SESSION['informational']) && !empty($_SESSION['informational'])) : ?>
			<p>Es trat folgender Fehler auf</p>
			<ul>
				<?php echo $_SESSION['informational']; ?>
			</ul>
		<?php endif; ?>

		<?php if (isset($_SESSION['notices']) && !empty($_SESSION['notices'])) : ?>
			<p>Folgende Tickets wurden NICHT gedruckt</p>
			<ul>
				<?php echo $_SESSION['notices']; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>
<?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) : ?>
	<p class="errors"><?php echo $_SESSION['errors']; ?> </p>
<?php endif; ?>

<script>
	function onKeyDown(event)
	{
		if(event.ctrlKey && event.keyCode == 13) {
			document.getElementById('form').submit();
		}
	}
</script>
</body>
</html>
