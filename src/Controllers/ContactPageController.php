<?php

namespace Loyals\ContactPage\Controllers;

use PageController;
use Loyals\ContactPage\Form\ContactForm;
use Loyals\ContactPage\Pages\ContactPage;

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
        return ContactForm::create($this, 'ContactForm')
            ->addExtraClass('Contactform')->setAttribute('data-abide', 'data-abide');
    }

    public function Success()
    {
        return isset($_REQUEST['success']) && $_REQUEST['success'] == "1";
    }
}