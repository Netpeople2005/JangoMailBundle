<?php

namespace Netpeople\JangoMailBundle\Groups;

use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\JangoMail;
use Netpeople\JangoMailBundle\Recipients\Recipient;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;

/**
 * Description of GroupManagement
 *
 * @author manuel
 */
class GroupAdmin implements ChoiceListInterface
{

    /**
     * Contiene el servicio JangoMail
     *
     * @var JangoMail 
     */
    protected $jangoMail = NULL;

    public function __construct(JangoMail $jangoMail)
    {
        $this->jangoMail = $jangoMail;
    }

    /**
     * Agrega un Grupo a jango
     * @param Group $group
     * @return boolean|Group el grupo creado ó false si hay error
     */
    public function addGroup(Group $group)
    {
        $params = $this->prepareParameters(array(
            'GroupName' => $group->getName(),
                ));
        try {
            $response = $this->jangoMail->getJangoInstance()
                    ->AddGroup($params);
            $response = preg_split('/\n/m', $response->AddGroupResult);
            if (0 == $response[0]) {
                return $group->setGroupID($response[2]);
            } else {
                $this->jangoMail->setError("No se pudo crear el Grupo");
            }
        } catch (\Exception $e) {
            $this->jangoMail->setError($e->getMessage());
        }
        return FALSE;
    }

    /**
     * Este metodo no funciona del todo bien, ya que está creando un nuevo grupo
     * en ves de editar. No usar por ahora
     * 
     * @deprecated
     *
     * @param Group $group
     * @param type $newName
     * @return boolean 
     */
    public function editGroup(Group $group, $newName)
    {
        $params = $this->prepareParameters(array(
            'OldGroupName' => $group->getName(),
            'NewGroupName' => $newName,
                ));
        try {
            $response = $this->jangoMail->getJangoInstance()
                    ->Groups_Rename($params);
            $response = preg_split('/\n/m', $response->AddGroupResult);
            if (0 == $response[0]) {
                return $response[2]; //el id del grupo
            }
        } catch (\Exception $e) {
            $this->jangoMail->setError($e->getMessage());
        }
        return FALSE;
    }

    /**
     * Obtiene un grupo a travez del ID del mismo.
     * @param string $groupID
     * @return Group|NULL el grupo encontrado ó nulo si no existe.
     */
    public function getGroupByGroupID($groupID)
    {
        foreach ($this->getGroups() as $group) {
            if ($groupID == $group->getGroupID()) {
                return $group;
            }
        }
        return NULL;
    }

    /**
     * Agrega Miembros a un grupo.
     * @param Group $group clase grupo con los miembros establecidos.
     * @return boolean 
     */
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
                $response = $this->jangoMail->getJangoInstance()
                        ->AddGroupMember($params);
                $response = preg_split('/\n/m', $response->AddGroupMemberResult);
                var_dump($response);
                if (0 == $response[0]) {
                    $results[$response[2]] = $e;
                } else {
                    break;
                }
            } catch (\Exception $e) {
                $this->jangoMail->setError($e->getMessage());
                return FALSE;
            }
        }
        if (count($results) === count($group->getRecipients())) {
            return $results;
        } else {
            $this->jangoMail->setError("No se Pudieron Guardar todos los Destinatarios");
            return FALSE;
        }
    }

    /**
     * Devuelve el numero de miebros que posee un grupo
     * @param Group $group
     * @return boolean 
     */
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

    /**
     *
     * @return boolean 
     */
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
     *
     * @return boolean 
     */
    public function getMembers(Group $group)
    {
        $params = $this->prepareParameters(array(
            'GroupName' => $group->getName()
        ));

        try {
            $response = $this->jangoMail->getJangoInstance()
                    ->Groups_GetMembers_XML($params);
            $xml = $response->Groups_GetMembers_XMLResult->any;
            $recipients = array();
            foreach (simplexml_load_string($xml)->GroupMembers as $e) {
                $recipient = new Recipient((string) $e->emailaddress);
                $recipients[] = $recipient->setGroup($group);
            }
            return $recipients;
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
