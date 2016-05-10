<?php

class WC_CCPending_Order_Email extends WC_Email {

    /**
     * Constructor
     */
    function __construct() {

        $this->id 				= 'ccpending_order';
        $this->title 			= 'Canvas Pending order';
        $this->description		= '';

        $this->heading 			= __( 'Thank you for your order', 'woocommerce' );
        $this->subject      	= __( 'Your {site_title} order receipt from {order_date}', 'woocommerce' );

        $this->template_html 	= 'emails/ccpending-order.php';

        // Call parent constructor
        parent::__construct();

        // Other settings
        $this->recipient = $this->get_option( 'recipient' );

        if ( ! $this->recipient )
            $this->recipient = get_option( 'admin_email' );
    }

    /**
     * trigger function.
     *
     * @access public
     * @return void
     */
    function trigger( $order_id ) {
        $this->object 		= wc_get_order( $order_id );
        $this->recipient	= $this->object->billing_email;

        $this->find['order-date']      = '{order_date}';
        $this->find['order-number']    = '{order_number}';

        $this->replace['order-date']   = date_i18n( wc_date_format(), strtotime( $this->object->order_date ) );
        $this->replace['order-number'] = $this->object->get_order_number();

        if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
            return;
        }

        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
    }

    /**
     * get_content_html function.
     *
     * @access public
     * @return string
     */
    function get_content_html() {
        ob_start();
        wc_get_template( $this->template_html, array(
            'order'         => $this->object,
            'email_heading' => $this->get_heading(),
            'sent_to_admin' => true,
            'plain_text'    => false
        ) );
        return ob_get_clean();
    }

    /**
     * get_content_plain function.
     *
     * @access public
     * @return string
     */
    function get_content_plain() {
        ob_start();
        wc_get_template( $this->template_plain, array(
            'order'         => $this->object,
            'email_heading' => $this->get_heading(),
            'sent_to_admin' => true,
            'plain_text'    => true
        ) );
        return ob_get_clean();
    }

    function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'         => __( 'Enable/Disable', 'woocommerce' ),
                'type'          => 'checkbox',
                'label'         => __( 'Enable this email notification', 'woocommerce' ),
                'default'       => 'yes'
            ),
            'recipient' => array(
                'title'         => __( 'Recipient(s)', 'woocommerce' ),
                'type'          => 'text',
                'description'   => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', 'woocommerce' ), esc_attr( get_option('admin_email') ) ),
                'placeholder'   => '',
                'default'       => ''
            ),
            'subject' => array(
                'title'         => __( 'Subject', 'woocommerce' ),
                'type'          => 'text',
                'description'   => sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'woocommerce' ), $this->subject ),
                'placeholder'   => '',
                'default'       => ''
            ),
            'heading' => array(
                'title'         => __( 'Email Heading', 'woocommerce' ),
                'type'          => 'text',
                'description'   => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'woocommerce' ), $this->heading ),
                'placeholder'   => '',
                'default'       => ''
            ),
            'email_type' => array(
                'title'         => __( 'Email type', 'woocommerce' ),
                'type'          => 'select',
                'description'   => __( 'Choose which format of email to send.', 'woocommerce' ),
                'default'       => 'html',
                'class'         => 'email_type wc-enhanced-select',
                'options'       => $this->get_email_type_options()
            )
        );
    }
}