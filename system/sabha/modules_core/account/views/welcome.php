<div id="dashboard-summary">
	<div id="dashboard-welcome-back" class="dashboard-item">
		<div class="dashboard-title">Account Details</div>
		<div class="dashboard-content">
			<table class="dashboard-summary-table">
				<tbody>
					<tr>
						<td><div>Account for Financial Year <strong><?php echo date_mysql_to_php_display($this->mdl_mcb_data->setting('account_fy_start')) . " - " . date_mysql_to_php_display($this->mdl_mcb_data->setting('account_fy_end')); ?></strong></div></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="clear"></div>
	<div id="dashboard-cash-bank" class="dashboard-item">
		<div class="dashboard-title">Bank and Cash accounts</div>
		<div class="dashboard-content">
			<?php
				if ($bank_cash_account)
				{
					echo "<table class=\"dashboard-cashbank-table\">";
					echo "<tbody>";
					foreach ($bank_cash_account as $id => $row)
					{
						echo "<tr>";
						echo "<td>" . anchor('account/report/ledgerst/' . $row['id'], $row['name'], array('title' => $row['name'] . ' Statement')) . "</td>";
						echo "<td>" . convert_amount_dc($row['balance']) . "</td>";
						echo "</tr>";
					}
					echo "</tbody>";
					echo "</table>";
				} else {
					echo "You have not created any bank or cash account";
				}
			?>
		</div>
	</div>
	<div id="dashboard-summary" class="dashboard-item">
		<div class="dashboard-title">Account Summary</div>
		<div class="dashboard-content">
			<?php
				echo "<table class=\"dashboard-summary-table\">";
				echo "<tbody>";
				echo "<tr><td>Assets Total</td><td>" . convert_amount_dc($asset_total) . "</td></tr>";
				echo "<tr><td>Liabilities Total</td><td>" . convert_amount_dc($liability_total) . "</td></tr>";
				echo "<tr><td>Incomes Total</td><td>" . convert_amount_dc($income_total) . "</td></tr>";
				echo "<tr><td>Expenses Total</td><td>" . convert_amount_dc($expense_total) . "</td></tr>";
				echo "</tbody>";
				echo "</table>";
			?>
		</div>
	</div>
</div>
<?php if (check_access('view log')) { ?>
	<div id="dashboard-log">
		<div id="dashboard-recent-log" class="dashboard-log-item">
			<div class="dashboard-log-title">Recent Activity</div>
			<div class="dashboard-log-content">
				<?php
				if ($logs)
				{
					echo "<ul id=\"recent-activity-list\">";
					foreach ($logs->result() as $row)
					{
						echo "<li>" . $row->message_title . "</li>";
					}
					echo "</ul>";
				} else {
					echo "No Recent Activity";
				}
				?>
			</div>
			<?php
				if ($logs)
				{
					echo "<div class=\"dashboard-log-footer\">";
					echo "<span>";
					echo anchor("account/log", "more...", array('class' => 'anchor-link-a'));
					echo "</span>";
				}
			?>
			</div>
		</div>
	</div>
<?php } ?>
<div class="clear"></div>
