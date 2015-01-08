<?php

/**
 * leads Extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2011-2015, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-leads
 */


class LeadsNotification
{

    /**
     * Return true if the notification_center is installed and can be supported
     *
     * @param bool $checkOptions Enable to check for available form notifications
     *
     * @return bool
     */
    public static function available($checkOptions = false)
    {
        $result = in_array('notification_center', \Config::getInstance()->getActiveModules());

        if ($result
            && $checkOptions
            && \NotificationCenter\Model\Notification::countBy('type', 'core_form') === 0
        ) {
            $result = false;
        }

        return $result;
    }

    /**
     * Send lead data using given notification
     *
     * @param int                                    $leadId
     * @param FormModel                              $form
     * @param \NotificationCenter\Model\Notification $notification
     *
     * @return bool
     */
    public static function send($leadId, \FormModel $form, \NotificationCenter\Model\Notification $notification)
    {
        $data   = array();
        $labels = array();

        $leadDataCollection = \Database::getInstance()->prepare("
            SELECT
                name,
                value,
                (SELECT label FROM tl_form_field WHERE tl_form_field.id=tl_lead_data.field_id) AS fieldLabel
            FROM tl_lead_data
            WHERE pid=?
        ")->execute($leadId);

        // Generate the form data and labels
        while ($leadDataCollection->next()) {
            $data[$leadDataCollection->name]   = $leadDataCollection->value;
            $labels[$leadDataCollection->name] = $leadDataCollection->fieldLabel ?: $leadDataCollection->name;
        }

        $formHelper = new \NotificationCenter\tl_form();

        // Send the notification
        $result = $notification->send($formHelper->generateTokens($data, $form->row(), array(), $labels));

        return !in_array(false, $result);
    }
}
