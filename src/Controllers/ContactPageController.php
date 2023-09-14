<?php

namespace Loyals\ContactPage\Controllers;

use PageController;
use Loyals\ContactPage\Form\ContactForm;
use Loyals\ContactPage\Pages\ContactPage;
use SilverStripe\Core\Config\Config;
use SilverStripe\SpamProtection\Extension\FormSpamProtectionExtension;

/**
 * Class ContactPage_Controller
 *
 * @property ContactPage dataRecord
 * @method ContactPage data()
 * @mixin ContactPage dataRecord
 */
class ContactPageController extends PageController
{
    private static $allowed_actions = [
        'ContactForm',
    ];

    public function ContactForm()
    {
        $form = ContactForm::create($this, 'ContactForm')
            ->addExtraClass('Contactform')->setAttribute('data-abide', 'data-abide');

        if (($config = Config::inst()->get(FormSpamProtectionExtension::class))
            && isset($config['enable_spam_protection'])
            && $config['enable_spam_protection']
        ) {
            $form->enableSpamProtection();
        }

        return $form;
    }

    public function Success()
    {
        return isset($_REQUEST['success']) && $_REQUEST['success'] == "1";
    }
}