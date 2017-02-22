<?php
/**
 * Circles - bring cloud-users closer
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@pontapreta.net>
 * @copyright 2017
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

script('circles', 'circles');

script('circles', 'notyf');
style('circles', 'notyf');

script('circles', 'navigation');
style('circles', 'navigation');

?>


<div id="app-navigation" class="noborder" style="position: relative">
	<div class="navigation-element" style="height: 100%; padding-top: 15px">
		<input id="circles_new_name" type="text"
			   placeholder="<?php p($l->t('Create a new circle')); ?>"/>
		<select id="circles_new_type" style="display: none;">

			<?php

			// Personal Circle
			if ($_['allowed_circles'][\OCA\Circles\Model\Circle::CIRCLES_PERSONAL]) {
				print_unescaped(
					sprintf(
						'<option value="%d">%s</option>',
						\OCA\Circles\Model\Circle::CIRCLES_PERSONAL,
						"Create a Personal Circle"
					)
				);
			}

			// Hidden Circle
			if ($_['allowed_circles'][\OCA\Circles\Model\Circle::CIRCLES_HIDDEN]) {
				print_unescaped(
					sprintf(
						'<option value="%d">%s</option>', \OCA\Circles\Model\Circle::CIRCLES_HIDDEN,
						"Create an Hidden Circle"
					)
				);
			}

			// Private Circle
			if ($_['allowed_circles'][\OCA\Circles\Model\Circle::CIRCLES_PRIVATE]) {
				print_unescaped(
					sprintf(
						'<option value="%d">%s</option>',
						\OCA\Circles\Model\Circle::CIRCLES_PRIVATE,
						"Create a Private Circle"
					)
				);
			}

			// Public Circle
			if ($_['allowed_circles'][\OCA\Circles\Model\Circle::CIRCLES_PUBLIC]) {
				print_unescaped(
					sprintf(
						'<option value="%d">%s</option>', \OCA\Circles\Model\Circle::CIRCLES_PUBLIC,
						"Create a Public Circle"
					)
				);
			}
			?>

		</select>
		<input id="circles_new_submit" type="submit" value="Creation" style="display: none;"/>

		<div id="circles_new_type_definition" style="display: none;">
			<div id="circles_new_type_1"><b>A Personal Circle is a list of users known only
					to
					yourself.</b><br/>Use this if you want to send messsage or share thing
				repeatedly to the same group of people.
			</div>
			<div id="circles_new_type_2"><b>An Hidden Circle is an open group that can be
					protected by
					a password.</b><br/>Select this circle to create a community not displayed as a
				Public Circle.
			</div>
			<div id="circles_new_type_4"><b>A Private Circle require invitation or a
					confirmation
					from an admin.</b> <br/>This is the best circle if you are looking for privacy
				when sharing your files or your ideas.
			</div>
			<div id="circles_new_type_8"><b>A Public Circle is an open group visible to anyone
					that dare to join. </b><br/>Your circle will be visible to everyone and everyone
				will be able to join the circle.
			</div>
		</div>
	</div>
	<div id="circles_list">

		<?php
		if ($_['allowed_circles'][\OCA\Circles\Model\Circle::CIRCLES_PERSONAL]) {
			print_unescaped('<div circle-type="personal">Personal Circles</div>');
		}

		if ($_['allowed_circles'][\OCA\Circles\Model\Circle::CIRCLES_HIDDEN]) {
			print_unescaped('<div circle-type="hidden">Hidden Circles</div>');
		}

		if ($_['allowed_circles'][\OCA\Circles\Model\Circle::CIRCLES_PRIVATE]) {
			print_unescaped('<div circle-type="private">Private Circles</div>');
		}

		if ($_['allowed_circles'][\OCA\Circles\Model\Circle::CIRCLES_PUBLIC]) {
			print_unescaped('<div circle-type="public">Public Circles</div>');
		}
		?>

	</div>

</div>

<div id="app-navigation" class="circles" style="display: none;">
	<input id="circles_search" type="text"
		   placeholder="<?php p($l->t('Search circles')); ?>"/>
</div>

<script id="tmpl_circle" type="text/template">
	<div class="title">%title%</div>
	<div class="type"><b>%type%</b> (%status%)</div>
	<div class="owner"><b>Owner:</b> %owner%</div>
	<div class="resume"><b>%count% members</b> since %creation%</div>
</script>

<div id="emptycontent">
	<div class="icon-circles"></div>
	<h2><?php p($l->t('No circle selected')); ?></h2>
</div>

<div id="app-content" style="position: relative">

	<div id="mainui">

		<div id="memberslist">
			<table class="table">
				<tr class="header">
					<td class="username">Username</td>
					<td class="level">Level</td>
					<td class="status">Status</td>
					<td class="joined">Joined</td>
					<td class="note">Notes</td>
				</tr>
			</table>

			<script id="tmpl_member" type="text/template">
				<tr class="entry">
					<td class="username">%username%</td>
					<td class="level">%level%</td>
					<td class="status">%status%</td>
					<td class="joined">%joined%</td>
					<td class="note">%note%</td>
				</tr>
			</script>

		</div>

		<div id="rightpanel">
			<div id="circledetails">

			</div>

			<input id="addmember" type="text"
				   placeholder="<?php p($l->t('Add a new member')); ?>"/>
			<div id="members_search_result">

			</div>

			<div id="memberdetails"></div>
		</div>


	</div>
</div>
