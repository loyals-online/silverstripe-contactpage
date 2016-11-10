<?php

class ContactForm extends Form
{
    protected $page;

    public function __construct(\Controller $controller, $name)
    {
        parent::__construct($controller, $name, FieldList::create(), FieldList::create(), null);

        $this->fields    = $this->getFieldList();
        $this->actions   = $this->getActions();
        $this->validator = $this->getRequiredFields();

        // setup form errors (re-init after parent::__construct call)
        parent::setupFormErrors();

        // trigger foundation abide validation
        $this->setAttribute('data-abide', 'ajax');
        $this->addExtraClass('prospect-form');

        // add som emore page/controller requirement
        Requirements::javascript(JSEND_DIR . '/js/jsend.js');
    }

    public function getFieldList()
    {
        $fieldlist = CompositeField::create($this->getBareFieldList());

        // Build actual form layout
        $row = CompositeField::create([
            $fieldlist,
        ])
            ->addExtraClass('row');

        return (new FieldList([
            $row,
        ]))->setForm($this);
    }

    public function getBareFieldList()
    {
        return [
            TextField::create(
                'Name',
                _t('ContactSubmission.Name', 'Name*')
            )
                ->setFieldHolderTemplate('FormField_holder'),
            TextField::create(
                'Email',
                _t('ContactSubmission.Email', 'E-mail*')
            )
                ->setFieldHolderTemplate('FormField_holder'),
            TextField::create(
                'Subject',
                _t('ContactSubmission.Subject', 'Subject*')
            )
                ->setFieldHolderTemplate('FormField_holder'),
            TextareaField::create(
                'Message',
                _t('ContactSubmission.Message', 'Message*')
            )
                ->setRows(10)
                ->setFieldHolderTemplate('FormField_holder'),
        ];
    }

    public function getActions()
    {
        return (new FieldList($this->getBareActions()))->setForm($this);
    }

    public function getBareActions()
    {
        return [
            FormAction::create(
                'SendContactForm',
                $this->controller->data()->SubmitButtonText
            )
                ->addExtraClass('button tiny right ContactformAction')
                ->setUseButtonTag(true),
        ];
    }

    public function getRequiredFields()
    {
        return (new RequiredFields($this->getBareRequiredFields()))->setForm($this);
    }

    public function getBareRequiredFields()
    {
        return [
            'Name',
            'Email',
            'Subject',
            'Message',
        ];
    }

    function SendContactForm($data, $form)
    {
        //Save and email submission
        $submission = new ContactSubmission();
        $form->saveInto($submission);
        $submission->PageID = $this->controller->data()->ID;
        $submission->write();

        //Set data
        $From    = $data['Email'];
        $Sender  = sprintf('%1$s <%2$s>', $this->controller->data()->NameFrom, $this->controller->data()->MailFrom);
        $To      = $this->controller->data()->MailTo;
        $Subject = sprintf('%1$s: %2$s', $this->controller->data()->MailSubject, $data['Subject']);
        $email   = new Email($Sender, $To, $Subject);

        // Set reply-to naar persoon die het formulier heeft ingevuld
        $email->addCustomHeader('Reply-To', $From);

        //set template
        $email->setTemplate('ContactFormEmail');

        //populate template
        $email->populateTemplate([
            'ContactName'    => $data['Name'],
            'ContactEmail'   => $data['Email'],
            'ContactSubject' => $data['Subject'],
            'ContactMessage' => $data['Message'],
        ]);

        if (
            ($fileField = $form->Fields()
                ->dataFieldByName('CV')) &&
            ($file = File::get()
                ->byID((int) $fileField->value))
        ) {
            $email->attachFile($file->getFilename());
        }

        //send mail
        $email->send();

        //return to successpage
        $this->controller->redirect(Director::baseURL() . $this->controller->data()->URLSegment . "/?success=1");
    }
}