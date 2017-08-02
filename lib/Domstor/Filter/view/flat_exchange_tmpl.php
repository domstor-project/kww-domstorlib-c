<div class="domstor_filter">
<?php $this->displayOpenTag()?>
	<?php $this->displayHidden()?>
	<div class="domstor_filter_layout">
		<div class="domstor_filter_fields">
			<table>
				<tr>
					<td class="nasn"><strong><?php $this->displayLabel('room_count')?></strong></td>
					<td>
						<?php $this->displayField('room_count')?><br />
						<?php $this->displayFieldLabel('in_communal')?>
					</td>
				</tr>
				<tr>
					<td class="nasn"><strong>Цена:</strong></td>
					<td>
						<?php $this->getField('price')->displayLabelField('min')?>
						<?php $this->getField('price')->displayLabelField('max')?> тыс.р.
					</td>
				</tr>
				<tr>
					<td class="nasn"><strong>Этаж:</strong></td>
					<td>
						<?php $this->displayField('floor_type')?><br />
						<?php $this->displayLabelField('max_floor')?> этажа
					</td>
				</tr>
				<tr>
					<td class="nasn"><strong><?php $this->displayLabel('code')?></strong></td>
					<td>
						<?php $this->displayField('code')?>
					</td>
				</tr>
			</table>
		</div>
		<div class="domstor_filter_list">
			<table>
				<tr>
					<td class="type">
						<strong><?php $this->displayLabel('type')?></strong>
						<?php $this->displayField('type')?>
					</td>
					<td class="district">
						<strong><?php $this->displayLabel('district')?></strong>
						<?php $this->displayField('district')?>
					</td>
                    <?php if ($this->hasField('suburban')): ?>
                    <td class="suburban">
						<strong><?php $this->displayLabel('suburban')?></strong>
						<?php $this->displayField('suburban')?>
					</td>
                    <?php endif ?>
				</tr>
			</table>
		</div>
	</div>
	<noscript>
		<div class="center"><?php $this->displayField('submit')?></div>
	</noscript>
	<?php $this->displayCloseTag()?>
	<div class="center"><?php $this->displayField('submit_link')?></div>
</div>