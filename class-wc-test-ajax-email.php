<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A custom Expedited Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class WC_Test_Ajax_Email extends WC_Email {
    /**
     * Set email defaults
     *
     * @since 0.1
     */
    public function __construct() {

        // set ID, this simply needs to be a unique name
        $this->id = 'wc_text_ajax_email';

        // this is the title in WooCommerce Email settings
        $this->title = 'Test Ajax';

        // this is the description in WooCommerce email settings
        $this->description = 'Text emails are sent when ajax button is clicked';

        // these are the default heading and subject lines that can be overridden using the settings
        $this->heading = 'Test Ajax';
        $this->subject = 'Test Ajax';

        // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
        $this->template_base  = um_ajax_email_plugin_path() . "/templates/";
        $this->template_html  = 'emails/test.php';
        $this->template_plain = 'emails/plain/test.php';

        // Trigger on new paid orders
        add_action( 'um_trigger_ajax_email', array( $this, 'trigger' ) );

        // Call parent constructor to load any other defaults not explicity defined here
        parent::__construct();

        // this sets the recipient to the settings defined below in init_form_fields()
        $this->recipient = $this->get_option( 'recipient' );

        // if none was entered, just use the WP admin email as a fallback
        if ( ! $this->recipient )
            $this->recipient = get_option( 'admin_email' );
    }


    /**
     * Determine if the email should actually be sent and setup email merge variables
     *
     * @since 0.1
     * @param int $order_id
     */
    public function trigger() {

        if ( ! $this->is_enabled() || ! $this->get_recipient() )
            return;

        // woohoo, send the email!
        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
    }


    /**
     * get_content_html function.
     *
     * @since 0.1
     * @return string
     */
    public function get_content_html() {
        return wc_get_template_html( $this->template_html, array(
            'email_heading' => $this->get_heading(),
            'sent_to_admin' => false,
            'plain_text'    => false,
            'email'         => $this
        ),
        '',
        um_ajax_email_plugin_path() . "/templates/emails/test.php" );
    }


    /**
     * get_content_plain function.
     *
     * @since 0.1
     * @return string
     */
    public function get_content_plain() {
        return wc_get_template_html( $this->template_plain, array(
            'email_heading' => $this->get_heading(),
            'sent_to_admin' => false,
            'plain_text'    => false,
            'email'         => $this
        ),
        '',
        um_ajax_email_plugin_path() . "/templates/emails/plain/test.php" );
    }


    /**
     * Initialize Settings Form Fields
     *
     * @since 2.0
     */

    public function init_form_fields() {

		$this->form_fields = array(
			'enabled'    => array(
				'title'   => __( 'Enable/Disable', 'woocommerce' ),
				'type'    => 'checkbox',
				'label'   => 'Enable this email notification',
				'default' => 'yes'
			),
			'subject'    => array(
				'title'       => __( 'Subject', 'woocommerce' ),
				'type'        => 'text',
				'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
				'placeholder' => '',
				'default'     => ''
			),
			'heading'    => array(
				'title'       => __( 'Email Heading', 'woocommerce' ),
				'type'        => 'text',
				'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
				'placeholder' => '',
				'default'     => ''
			),
			'email_type' => array(
				'title'       => __( 'Email type', 'woocommerce' ),
				'type'        => 'select',
				'description' => __( 'Choose which format of email to send.', 'woocommerce' ),
				'default'       => 'html',
				'class'         => 'email_type wc-enhanced-select',
				'options'     => array(
					'plain'	    => __( 'Plain text', 'woocommerce' ),
					'html' 	    => __( 'HTML', 'woocommerce' ),
					'multipart' => __( 'Multipart', 'woocommerce' ),
				)
			)
		);
	}
		
}
