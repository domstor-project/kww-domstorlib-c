<div class="domstor_filter">
	<?php $this->displayOpenTag()?>
	<?php $this->displayHidden()?>

	<div class="domstor_filter_layout">
		<div class="domstor_filter_fields">
			<table>
				<tr>
					<td class="nasn"><strong>Цена:</strong></td>
					<td>
						<?php $this->getField('price')->displayLabelField('min')?>
						<?php $this->getField('price')->displayLabelField('max')?> тыс.р.
					</td>
				</tr>
				<tr>
					<td class="nasn"><strong>Ширина:</strong></td>
					<td>
						<?php $this->displayLabelField('x_min')?> <?php $this->displayLabelField('x_max')?> м.
					</td>
				</tr>
				<tr>
					<td class="nasn"><strong>Длина:</strong></td>
					<td>
						<?php $this->displayLabelField('y_min')?> <?php $this->displayLabelField('y_max')?> м.
					</td>
				</tr>
				<tr>
					<td class="nasn"><strong>Высота:</strong></td>
					<td>
						<?php $this->displayLabelField('z_min')?> <?php $this->displayLabelField('z_max')?> м.
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

