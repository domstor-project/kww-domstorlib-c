<div class="domstor_filter">
<?php $this->displayOpenTag()?>
	<?php $this->displayHidden()?>
	<div class="domstor_filter_layout">
		<div class="domstor_filter_fields">
			<table>
				<tr>
					<td class="nasn"><strong><?php $this->displayLabel('room_count')?></strong></td>
					<td>
						<?php $this->displayField('room_count')?>
					</td>
				</tr>
				<tr>
					<td class="nasn"><strong>Бюджет:</strong></td>
					<td>
						<?php $this->getField('rent')->displayLabelField('min')?>
						<?php $this->getField('rent')->displayLabelField('max')?> р.
						<?php $this->getField('rent')->displayLabelField('period')?>
					</td>
				</tr>
				<tr>
					<td class="nasn"><strong>Площадь дома:</strong></td>
					<td>
						<?php $this->displayLabelField('squareh_min')?> <?php $this->displayLabelField('squareh_max')?> кв.м.
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
