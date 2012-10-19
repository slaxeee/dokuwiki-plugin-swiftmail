<?php
/**
 * Swiftmail Plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 */
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class admin_plugin_swiftmail extends DokuWiki_Admin_Plugin {

    /**
     * return sort order for position in admin menu
     */
    function getMenuSort() {
        return 200;
    }

    /**
     * handle user request
     */
    function handle() {
        global $INPUT;
        global $conf;
        if(!$INPUT->bool('send')) return;

        // make sure debugging is on;
        $conf['plugin']['swiftmail']['debug'] = 1;

        // send a mail
        $mail = new Mailer();
        if($INPUT->str('to')) $mail->to($INPUT->str('to'));
        if($INPUT->str('cc')) $mail->to($INPUT->str('cc'));
        if($INPUT->str('bcc')) $mail->to($INPUT->str('bcc'));
        $mail->subject('SwiftMail Plugin says hello');
        $mail->setBody("Hi @USER@\n\nThis is a test from @DOKUWIKIURL@");
        $ok = $mail->send();

        // check result
        if($ok){
            msg('Message was sent. Swiftmail seems to work.',1);
        }else{
            msg('Message wasn\'t sent. Swiftmail seems not to work properly.',-1);
        }
    }

    /**
     * Output HTML form
     */
    function html() {
        global $INPUT;
        echo $this->locale_xhtml('intro');

        $form = new Doku_Form(array());
        $form->startFieldset('Testmail');
        $form->addHidden('send', 1);
        $form->addElement(form_makeField('text', 'to', $INPUT->str('to'), 'To:', '', 'block'));
        $form->addElement(form_makeField('text', 'cc', $INPUT->str('cc'), 'Cc:', '', 'block'));
        $form->addElement(form_makeField('text', 'bcc', $INPUT->str('bcc'), 'Bcc:', '', 'block'));
        $form->addElement(form_makeButton('submit', '', 'Send Email'));

        $form->printForm();
    }

}
