<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category Zend
 * @package  Mail_Bank_Zend_Mail
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd     New BSD License
 * @version $Id$
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/** Mime Part File Included
 * Mail_Bank_Zend_Mail_Part
 */
if ( file_exists( MAIL_BANK_DIR_PATH . 'lib/zend/mail/class-mail-bank-zend-mime-part.php' ) ) {
	require_once MAIL_BANK_DIR_PATH . 'lib/zend/mail/class-mail-bank-zend-mime-part.php';
}

/** Mail Message Interface File Included
 * Mail_Bank_Zend_Mail_Message_Interface
 */
if ( file_exists( MAIL_BANK_DIR_PATH . 'lib/zend/mail/message/interface.php' ) ) {
	require_once MAIL_BANK_DIR_PATH . 'lib/zend/mail/message/interface.php';
}
/** Class Begins here.

 * @category    Zend
 * @package  Mail_Bank_Zend_Mail
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license  http://framework.zend.com/license/new-bsd    New BSD License
 */
class Mail_Bank_Zend_Mail_Message extends Mail_Bank_Zend_Mail_Part implements Mail_Bank_Zend_Mail_Message_Interface {
	/**
	 * Flags for this message.
	 *
	 * @var array
	 */
	protected $_flags = array();
	/** Public constructor
	 *
	 * In addition to the parameters of Mail_Bank_Zend_Mail_Part::__construct() this constructor supports:
	 * - file  filename or file handle of a file with raw message content
	 * - flags array with flags for message, keys are ignored, use constants defined in Mail_Bank_Zend_Mail_Storage
	 *
	 * @param  array $params  MANDATORY.
	 * @throws Mail_Bank_Zend_Mail_Exception Throws Exception.
	 */
	public function __construct( array $params ) {
		if ( isset( $params['file'] ) ) {
			if ( ! is_resource( $params['file'] ) ) {
				$params['raw'] = wp_remote_get( $params['file'] );
				if ( false === $params['raw'] ) {
					/** Exception File Included.

				* @see Mail_Bank_Zend_Mail_Exception
				*/
					if ( file_exists( MAIL_BANK_DIR_PATH . 'lib/zend/mail/class-mail-bank-zend-mail-exception.php' ) ) {
						require_once MAIL_BANK_DIR_PATH . 'lib/zend/mail/class-mail-bank-zend-mail-exception.php';
					}

					throw new Mail_Bank_Zend_Mail_Exception( 'could not open file' );
				}
			} else {
				$params['raw'] = stream_get_contents( $params['file'] );
			}
			$params['raw'] = preg_replace( "/(?<!\r)\n/", "\r\n", $params['raw'] );
		}

		if ( ! empty( $params['flags'] ) ) {
			// Set key and value to the same value for easy lookup.
			$this->_flags = array_merge( $this->_flags, array_combine( $params['flags'], $params['flags'] ) );
		}

		parent::__construct( $params );
	}
	/**
	 * Return toplines as found after headers.
	 *
	 * @return string toplines
	 */
	public function get_top_lines() {
		return $this->top_lines;
	}
	/**
	 * Check if flag is set.
	 *
	 * @param mixed $flag a flag name, use constants defined in Mail_Bank_Zend_Mail_Storage.
	 * @return bool true if set, otherwise false.
	 */
	public function hasFlag( $flag ) {
		return isset( $this->_flags[ $flag ] );
	}
	/**
	 * Get all set flags.
	 *
	 * @return array array with flags, key and value are the same for easy lookup
	 */
	public function getFlags() {
		return $this->_flags;
	}
}
