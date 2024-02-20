<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
        <style>
            .right-top-table td{
                padding:0px 10px;
            }
            #template_header_image{
                text-align: left;
            }
            .right-email-header h1{
                margin-bottom: 0px;
                color:#005ea0;
                text-align:right;
				font-weight: bold;
				font-size: 2em !important;
            }
            .right-top-table{
                float:right;
            }
            .right-email-header h4{
                margin-top:0px;
            }
            .invoice-table-container,
            .order-details,
			#addresses{
                max-width:1000px;
				margin-top:20px;

            }
            .right-column-td{
                text-align: right;
            }
            #addresses h2{
                font-size: 16px;
                color:#000;
				margin:0px;
            }
			#addresses,
			#addresses td,
			#addresses th,
			#addresses tr,
			#addresses address{
				font-size: 14px;
				border:none;
				padding:5px;
				font-style:normal;
				color:#000;
			}
            .order-details,
            .order-payment{
                margin-top:20px;
                border-color:none;
				border:none;
            }
            .order-details td,
            .order-details th,
            .order-details tr{
                border-color:#000;
                border:none;
				padding:10px;
                font-weight: 400;
                color:#000;
            }
			.order-details,
			.footer-not{
				border-top: 2px solid #161616;
			}
			.footer-not{
				padding-top:10px;
			}
            .order-details th{
                color:#000;
				text-decoration: underline;
                font-weight:bold;
            }
            .right-top-table{
                text-align:center;
            }
			#invoice-wrapper{
				max-width:1000px;
				padding:0px;
				background:#fff;
			}
            .order-payment td,
            .order-payment th,
            .order-payment tr{
                border-color:#000;
                border:none;
				padding:10px;
                font-weight: 400;
                color:#000;
            }
            .order-payment th{
                color:#000;
                font-weight:bold;
            }
			.left-column-td,
			.right-column-td{
				width:50%;
			}
			.total-purchase strong{
				display:block;
				text-align:right;
			}
			div#invoice-wrapper {
			    margin: 0 auto;
			}
			div#template_header_image {
			    max-width: 385px;
			}
			#template_header_table-2{
				width: 100%;
			}
			#template_header_table{
				width:100%;
				padding-top:2em;
				padding-bottom: 2em;
			}
			div#template_header_address {
				text-align: right;
			}
			body{
				font-family: arial;
				line-height: 1.5em;
			}
			div#template_header_address span {
    display: inline-block;
    margin-bottom: 6px;
}
.footer-not strong {
    clear: both;
    display: block;
    margin-bottom: 12px;
}
.eft {
    display: inline-block;
    max-width: 280px;
	width: 100%;
    float: left;
    border: 1px solid #000;
    padding: 10px;
    margin-right: 20px;
	 min-height: 85px;
}
.paypal {
    display: inline-block;
    max-width: 280px;
width: 100%;
    float: left;
    border: 1px solid #000;
    padding: 10px;
    margin-right: 20px;
    min-height: 85px;
}
.footer-not img {
    max-width: 120px !important;
}
p.footer-text {
    clear: both;
    display: block;
    padding-top: 20px;
    font-size: 14px;
    font-style: italic;
}
address.address {
    font-size: 17px !important;
}
table#addresses {
	margin-top: 0;
    border-top: 2px solid #161616 !important;
    padding-top: 20px !important;
}
#template_header_title{
	text-align: center;
}
#template_header_title h2{
	font-size:30px;
	color:#000;
	display: block;
	margin-bottom: 0px;
	text-align: center;
}
td#template_header_address1,
td#template_header_abn1 {
    width: 30%;
}
        </style>
	</head>
    <?php
    $date_created = esc_html( wc_format_datetime( $order->get_date_created() ) );
	$order_number =  $order->get_order_number();
    ?>
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<div id="invoice-wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
			<table  id="template_header_table">
				<tr>
					<td>
						<div id="template_header_image">
							<?php
							if ( $img = get_option( 'woocommerce_email_header_image' ) ) {
								echo '<p style="margin-top:0;"><img src="' . esc_url( $img ) . '" alt="' . get_bloginfo( 'name', 'display' ) . '" /></p>';
							}
				              if(get_option( 'woocommerce_address') > ''){
				                  echo get_option( 'woocommerce_address');
				              }
							?>
						</div>
					</td>
					<td>
						<div id="template_header_address">
						<?php
							if(get_option( 'right_invoice_header') > ''){
								echo get_option( 'right_invoice_header');
							}
						?>
						</div>
					</td>
				</tr>
			</table>
			<table  id="template_header_table-2">
				<tr>
					<td id="template_header_abn1">
						<div id="template_header_abn">
							<?php
								if(get_option( 'woocommerce_abn') > ''){
									echo "<b>".get_option( 'woocommerce_abn')."</b>";
								}
							?>
						</div>
					</td>
					<td id="template_header_title1">
						<div id="template_header_title">
							<?php
								if(get_option( 'tax_invoice') > ''){
									echo "<h2>".get_option( 'tax_invoice')."</h2>";
								}
							?>
						</div>
					</td>
					<td id="template_header_address1">
						<div id="template_header_address">
						<?php
							if(get_option( 'right_invoice_header') > ''){
								echo "<b>Number: ".$order_number."</b><br>";
								echo "<b>Date: ".$date_created."</b>";

							}
						?>
						</div>
					</td>
				</tr>
			</table>
