<?php

namespace Netpeople\JangoMailBundle\Groups;

use Exception;
use Netpeople\JangoMailBundle\Exception\JangoMailException;
use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\JangoMail;
use Netpeople\JangoMailBundle\Recipients\Recipient;
use SoapFault;

/**
 * Description of GroupManagement
 *
 * @author manuel
 */
class GroupAdmin
{

    /**
     * Contiene el servicio JangoMail
     *
     * @var JangoMail 
     */
    protected $jangoMail;

    public function __construct(JangoMail $jangoMail)
    {
        $this->jangoMail = $jangoMail;
    }

    /**
     * Agrega un Grupo a jango
     * @param Group $group
     * @return boolean|Group el grupo creado ó false si hay error
     */
    public function add(Group $group)
    {
        try {
            $response = $this->jangoMail->call('AddGroup', array(
                'GroupName' => $group->getName(),
            ));
            $response = preg_split('/\n/m', $response->AddGroupResult);
            if (0 == $response[0]) {
                $group->setGroupID($response[2]);

                if ($group->getRecipients()->count()) {
                    $this->addMembers($group);
                }

                return $group;
            } else {
                $this->jangoMail->setError("No se pudo crear el Grupo");
            }
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
        return false;
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
    public function edit(Group $group, $newName)
    {
        try {
            $response = $this->jangoMail->call('Groups_Rename', array(
                'OldGroupName' => $group->getName(),
                'NewGroupName' => $newName,
            ));
            $response = preg_split('/\n/m', $response->AddGroupResult);
            if (0 == $response[0]) {
                return $response[2]; //el id del grupo
            }
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
        return false;
    }

    /**
     * Obtiene un grupo a traves del ID del mismo.
     * @param string $groupID
     * @return Group|null el grupo encontrado ó nulo si no existe.
     */
    public function getById($groupID)
    {
        foreach ($this->getAll() as $group) {
            if ($groupID == $group->getGroupID()) {
                return $group;
            }
        }
        return null;
    }

    /**
     * Obtiene un grupo a traves del nombre del mismo.
     * @param string $name
     * @return Group|null el grupo encontrado ó nulo si no existe.
     */
    public function getByName($name)
    {
        foreach ($this->getAll() as $group) {
            if ($name == $group->getName()) {
                return $group;
            }
        }
        return null;
    }

    /**
     * Agrega Miembros a un grupo.
     * @param Group $group clase grupo con los miembros establecidos.
     * @return boolean 
     */
    public function addMembers(Group $group)
    {
        $params = array(
            'GroupName' => $group->getName(),
            'FieldNames' => array('Name'),
        );

        $result = true;

        /* @var $groupJango Group */
        $groupJango = $this->getMembers(new Group($group->getName()));
        if ($groupJango !== false) {
            $group->getRecipients()->removeIfExistInCollection($groupJango->getRecipients());
            foreach ($group->getRecipients() as $e) {
                $params['EmailAddress'] = $e->getEmail();
                $params['FieldValues'] = array($e->getName());
                try {
                    $response = $this->jangoMail->call('AddGroupMember', $params);
                    $response = preg_split('/\n/m', $response->AddGroupMemberResult);
                    if (!0 == $response[0]) {
                        $result = false;
                    }
                } catch (SoapFault $e) {
                    throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
                }
            }
            foreach ($groupJango->getRecipients() as $e) {
                $group->addRecipient($e); // insertamos los miembros que estan en jango al objeto.
            }
            if ($result) {
                return true;
            } else {
                throw new JangoMailException("No se Pudieron Guardar todos los Destinatarios");
                return false;
            }
        } else {
            throw new JangoMailException("No se pudo obtener la Información del Grupo en Jango");
        }
    }

    /**
     * Devuelve el numero de miebros que posee un grupo
     * @param Group $group
     * @return boolean 
     */
    public function countMembers(Group $group)
    {
        try {
            $response = $this->jangoMail->call('GetGroupMemberCount', array(
                'GroupName' => $group->getName(),
            ));
            return (int) $response->GetGroupMemberCountResult;
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
        return false;
    }

    /**
     *
     * @return boolean 
     */
    public function getAll()
    {
        try {
            $response = $this->jangoMail->call('Groups_GetList_XML');
            $xml = $response->Groups_GetList_XMLResult->any;
            $groups = array();
            foreach (simplexml_load_string($xml)->Groups as $e) {
                $group = new Group();
                $groups[] = $group->setName((string) $e->GroupName)
                        ->setGroupID((string) $e->GroupID)
                        ->setMemberCount((string) $e->MemberCount);
            }
            return $groups;
        } catch (Exception $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
        return false;
    }

    /**
     *
     * @return boolean 
     */
    public function getMembers(Group $group)
    {
        try {
            $response = $this->jangoMail->call('Groups_GetMembers_XML', array(
                'GroupName' => $group->getName()
            ));
            $xml = $response->Groups_GetMembers_XMLResult->any;
            foreach (simplexml_load_string($xml)->GroupMembers as $e) {
                $recipient = new Recipient((string) $e->emailaddress);
                $group->addRecipient($recipient);
            }
            return $group;
        } catch (Exception $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
        return false;
    }

    /**
     * Crea y agrega miembros a un grupo buscando por ID si este no existe.
     * si existe solo agrega miembros nuevos.
     * @param \Netpeople\JangoMailBundle\Groups\Group $group
     * @return \Netpeople\JangoMailBundle\Groups\Group
     */
    public function addIfNotExist(Group $group)
    {
        if ($this->getById($group->getGroupID())) {
            //si existe solo agregamos los miembros que no existan
            $this->addMembers($group);
        } else {
            $this->add($group);
        }

        return $group;
    }

    /**
     * Crea y agrega miembros a un grupo buscando por el Nombre si este no existe.
     * si existe solo agrega miembros nuevos.
     * @param \Netpeople\JangoMailBundle\Groups\Group $group
     * @return \Netpeople\JangoMailBundle\Groups\Group
     */
    public function addIfNameNotExist(Group $group)
    {
        if ($this->getByName($group->getName())) {
            //si existe solo agregamos los miembros que no existan
            $this->addMembers($group);
        } else {
            $this->add($group);
        }

        return $group;
    }

}
