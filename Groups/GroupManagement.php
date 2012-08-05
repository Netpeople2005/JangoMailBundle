<?php

namespace Netpeople\JangoMailBundle\Groups;

use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\JangoMail;
use Netpeople\JangoMailBundle\Recipients\RecipientInterface;

/**
 * Description of GroupManagement
 *
 * @author manuel
 */
class GroupManagement
{

    /**
     *
     * @var JangoMail 
     */
    protected $jangoMail = NULL;

    public function __construct(JangoMail $jangoMail)
    {
        $this->jangoMail = $jangoMail;
    }

    protected function prepareParameters(array $aditionals = array())
    {
        $config = $this->jangoMail->getConfig();

        return array(
            'Username' => $config['username'],
            'Password' => $config['password'],
                ) + $aditionals;
    }

    public function addGroup(Group $group)
    {
        try {
            return $this->jangoMail->getJangoInstance()
                            ->AddGroup($this->prepareParameters(array(
                                        'GroupName' => $group->getName()
                                    )));
        } catch (SoapFault $e) {
            echo $e;
        }
    }

    public function addGroupMember(Group $group, RecipientInterface $recipient)
    {
        $params = $this->prepareParameters(array(
            'GroupName' => $group->getName(),
            'EmailAddress' => $recipient->getEmail(),
            'FieldNames' => array('Name'),
            'FieldValues' => array($recipient->getName()),
                ));

        try {
            return $this->jangoMail->getJangoInstance()
                            ->AddGroupMember($params);
        } catch (SoapFault $e) {
            echo $e;
        }
    }

}
