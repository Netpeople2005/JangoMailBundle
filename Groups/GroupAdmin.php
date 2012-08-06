<?php

namespace Netpeople\JangoMailBundle\Groups;

use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\JangoMail;
use Netpeople\JangoMailBundle\Recipients\RecipientInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;

/**
 * Description of GroupManagement
 *
 * @author manuel
 */
class GroupAdmin implements ChoiceListInterface
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

    public function addGroup(Group $group)
    {
        $params = $this->prepareParameters(array(
            'GroupName' => $group->getName(),
                ));
        try {
            return $this->jangoMail->getJangoInstance()
                            ->AddGroup($params);
        } catch (SoapFault $e) {
            $this->jangoMail->setError($e->getMessage());
        } catch (\Exception $e) {
            $this->jangoMail->setError($e->getMessage());
        }
        return FALSE;
    }

    public function addMembers(Group $group)
    {
        $params = $this->prepareParameters(array(
            'GroupName' => $group->getName(),
            'FieldNames' => array('Name'),
                ));

        $results = array();
        foreach ($group->getRecipients() as $e) {
            $params['EmailAddress'] = $e->getEmail();
            $params['FieldValues'] = array($e->getName());
            try {
                $results[] = $this->jangoMail->getJangoInstance()
                        ->AddGroupMember($params);
            } catch (SoapFault $e) {
                $this->jangoMail->setError($e->getMessage());
                return FALSE;
            } catch (\Exception $e) {
                $this->jangoMail->setError($e->getMessage());
                return FALSE;
            }
        }
        return $results;
    }

    public function countMembers(Group $group)
    {
        $params = $this->prepareParameters(array(
            'GroupName' => $group->getName(),
                ));

        try {
            $response = $this->jangoMail->getJangoInstance()
                    ->GetGroupMemberCount($params);
            return (int) $response->GetGroupMemberCountResult;
        } catch (SoapFault $e) {
            $this->jangoMail->setError($e->getMessage());
        } catch (\Exception $e) {
            $this->jangoMail->setError($e->getMessage());
        }
        return FALSE;
    }

    public function getGroups()
    {
        $params = $this->prepareParameters();

        try {
            $response = $this->jangoMail->getJangoInstance()
                    ->Groups_GetList_XML($params);
            $xml = $response->Groups_GetList_XMLResult->any;
            $groups = array();
            foreach (simplexml_load_string($xml)->Groups as $e) {
                $group = new Group();
                $groups[] = $group->setName((string) $e->GroupName)
                        ->setGroupID((string) $e->GroupID)
                        ->setMemberCount((string) $e->MemberCount);
            }
            return $groups;
        } catch (SoapFault $e) {
            $this->jangoMail->setError($e->getMessage());
        } catch (\Exception $e) {
            $this->jangoMail->setError($e->getMessage());
        }
        return FALSE;
    }

    /**
     * Metodo Usado para devolver un arreglo con los grupos disponibles en
     * JangoMail.
     * 
     * @return array 
     */
    public function getChoices()
    {
        $choices = array();
//        foreach ($this->getGroups() as $group) {
//            $choices[$group->getName()] = $group->getName();
//        }
        $choices['hola'] = 'hola';
        $choices['chao'] = 'chao';
        return $choices;
    }

    protected function prepareParameters(array $aditionals = array())
    {
        $config = $this->jangoMail->getConfig();

        return array(
            'Username' => $config['username'],
            'Password' => $config['password'],
                ) + $aditionals;
    }

}
