<?php
/**
 * Render a list of ads included in an ad group
 *
 * @package   Advanced_Ads_Admin
 * @author    Thomas Maier <support@wpadvancedads.com>
 * @license   GPL-2.0+
 * @link      https://wpadvancedads.com
 * @copyright since 2013 Thomas Maier, Advanced Ads GmbH
 *
 * @var array $ad_form_rows HTML to render ad form.
 * @var array $ads_for_select array with ads that can be choosen from for the group.
 * @var Advanced_Ads_Group $group ad group object.
 * @var string $new_ad_weights HTML for new ad weights form.
 */

?><table class="advads-group-ads">
	<?php if ( $ads_for_select ) : ?>	
	<thead><tr><th>
	<?php
	esc_attr_e( 'Ad', 'advanced-ads' );
	?>
	</th><th colspan="2">
	
		<?php esc_html_e( 'weight', 'advanced-ads' ); ?>
	
	</th></tr></thead>
	<?php endif; ?>

	<tbody>
<?php
if ( count( $ad_form_rows ) ) {
	foreach ( $ad_form_rows as $_row ) {
		echo $_row;
	}
}
?>
	</tbody>
</table>

<?php if ( $ads_for_select ) : ?>
	<fieldset class="advads-group-add-ad">
		<legend><?php esc_attr_e( 'New Ad', 'advanced-ads' ); ?></legend>
		<select class="advads-group-add-ad-list-ads">
			<?php
			foreach ( $ads_for_select as $_ad_id => $_ad_title ) {
				echo '<option value="advads-groups[' . absint( $group->id ) . '][ads][' . absint( $_ad_id ) . ']">' . esc_html( $_ad_title ) . '</option>';
			}
			?>
		</select>
		<?php echo $new_ad_weights; ?>
		<button type="button" class="button"><?php esc_attr_e( 'add', 'advanced-ads' ); ?></button>
	</fieldset>
<?php else: ?>
	<a class="button" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=advanced_ads' ) ); ?>"><?php esc_html_e( 'Create your first ad', 'advanced-ads' ); ?></a>
	<?php
endif;
