<?php
/**
 * Single ad item in the list of ads in a group.
 *
 * @var string $ad_title           Ad title.
 * @var string $ad_edit_link       Ad edit link.
 * @var string $ad_schedule_output Ad schedule output.
 * @var string $ad_weight_percentage Ad weight percentage.
 * @var Advanced_Ads_Group $group  Group object.
 * @var int $weight_sum            Sum of all ad weights.
 */
?>
<div style="display: <?php echo $i > 3 ? 'none' : 'flex'; ?>">
	<div>
		<a href="<?php echo esc_url( $ad_edit_link ); ?>"><?php echo esc_html( $ad_title ); ?></a>
	</div>
	<div>
		<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the output is already escaped
			echo $ad_schedule_output;
		?>
	</div>
	<div class="advads-ad-group-list-ads-weight">
		<?php if ( $group->type === 'default' && $weight_sum ) : ?>
			<span title="<?php esc_attr_e( 'Ad weight', 'advanced-ads' ); ?>"><?php echo esc_html( $ad_weight_percentage ); ?></span>
		<?php endif; ?>
	</div>
</div>
