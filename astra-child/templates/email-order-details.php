<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';
$currency_code = $order->get_currency();
$currency_symbol = get_woocommerce_currency_symbol( $currency_code );
$order_status_completed = $order->has_status('completed');
$additional_content =  get_option('woocommerce_payment_instuction');
?>

<div style="margin-bottom: 40px;">
    <?php
    /**
     * Hook for woocommerce_email_customer_details.
     *
     * @hooked WC_Emails::customer_details() Shows customer details
     * @hooked WC_Emails::email_address() Shows email address
     */
    do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
    ?>
	<table class="td order-details" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'ID', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( '', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Qty', 'woocommerce' ); ?></th>
                <th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
                <th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$order,
				array(
					'show_sku'      => $sent_to_admin,
					'show_image'    => false,
					'image_size'    => array( 32, 32 ),
					'plain_text'    => $plain_text,
					'sent_to_admin' => $sent_to_admin,
				)
			);


            if($order->get_total_discount() > 0 ){ ?>
                <tr>
                    <td colspan="4" class="total-purchase">
                        <strong>Discount:</strong>
                    </td>
                    <td><strong> - <?php echo $currency_symbol. $order->get_total_discount();?></strong></td>
                </tr>
            <?php }
            if($order->get_total_tax() > 0 ){?>
                   <tr>
                       <td colspan="4" class="total-purchase"><strong>GST:</strong></td>
                       <td><strong><?php echo $currency_symbol.$order->get_total_tax()?></strong></td>
                   </tr>
           <?php } ?>
            <tr>
                <td colspan="4" class="total-purchase">
                    <strong>Total</strong>
                </td>
                <td><strong><?php echo $currency_symbol. $order->get_total();?></strong></td>
            </tr>
        </tbody>
	</table>

</div>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
<?php 
	if($order->get_payment_method() === "invoice"){
			echo "<strong>Please pay within 7 days.</strong><br>"	;
	}
?>
