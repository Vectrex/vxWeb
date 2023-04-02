<?php
/*
 * This file is part of the vxPHP/vxWeb framework
 *
 * (c) Gregor Kofler <info@gregorkofler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace vxWeb\User\Notification;

use vxPHP\Application\Application;
use vxPHP\Application\Exception\ApplicationException;
use vxPHP\User\User;

/**
 * A notification for users with a representation in the database
 *  
 *
 * @author Gregor Kofler, info@gregorkofler.com
 * @version 0.4.1 2023-04-02
 * 
 */
class Notification
{
	/**
	 * the primary key of the stored notification
	 * 
	 * @var integer
	 */
	private $id;
	
	/**
	 * the alias name of the notification
	 *
	 * @var string
	 */
	private $alias;

	/**
	 * the subject of the notification
	 *
	 * @var string
	 */
	private $subject;

	/**
	 * the message body of the notification
	 *
	 * @var string
	 */
	private $message;
	
	/**
	 * description of notification, serves labelling purposes
	 *
	 * @var string
	 */
	private $description;
	
	/**
	 * list of attachments which are added to notification message
	 * 
	 * @var array
	 */
	private $attachment;
	
	/**
	 * signature which is added to notification message 
	 * 
	 * @var string
	 */
	private $signature;
	
	/**
	 * the alias of the admin group the notification is tied to
	 * 
	 * @var string
	 */
	private $group_alias;
	
	/**
	 * flag which indicates whether the notification will be displayed
	 * when editing user and notification data
	 * 
	 * @var boolean
	 */
	private $not_displayed;
	
	/**
	 * the list of users who get notified by the notification
	 * 
	 * @var User[]
	 */
	private $notifies;

	private static $cachedNotificationData;

	/**
	 * create a notification instance identified by its alias
	 * 
	 * @param string $alias
	 */
	public function __construct(string $alias)
    {
		if(!isset(self::$cachedNotificationData)) {
			self::queryAllNotifications();
		}

		if(isset(self::$cachedNotificationData[$alias])) {
			foreach(self::$cachedNotificationData[$alias] as $k => $v) {
				$k = strtolower($k);
				if(property_exists($this, $k)) {
					$this->$k = $v;
				}
			}
		}
	}

    /**
     * expose private properties
     *
     * @param string $property
     * @return mixed
     */
	public function __get(string $property)
    {
        return $this->$property ?? null;
	}

    /**
     * expose private properties
     *
     * @param string $property
     * @param $value
     */
	public function __set(string $property, $value): void
    {
        if(property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    /**
     * expose private properties
     *
     * @param string $property
     * @return bool
     */
    public function __isset(string $property): bool
    {
        return property_exists($this, $property);
    }

	public function __toString()
    {
		return $this->alias;
	}

    /**
     * get all notification instances assigned to an admingroup identified by $groupAlias
     *
     * @param string|null $groupAlias
     * @return Notification[]
     */
	public static function getAvailableNotifications(string $groupAlias = null): array
    {
		if(!isset(self::$cachedNotificationData)) {
			self::queryAllNotifications();
		}

		$result = [];

		foreach(self::$cachedNotificationData as $v) {
			if(!isset($groupAlias) || strtoupper($v['group_alias']) === strtoupper($groupAlias)) {
				$n = new Notification($v['alias']);
				$result[(string) $n] = $n;
			}
		}

		return $result;
	}

	private static function queryAllNotifications(): void
    {
		$db = Application::getInstance()->getVxPDO();
		
		$rows = $db->doPreparedQuery("
			SELECT
				notificationsID as id,
				n.Alias,
				COALESCE(Description, n.Alias) AS Description,
				Subject,
				Message,
				Signature,
				Attachment,
				Not_Displayed,
				ag.Alias as group_alias

			FROM
				notifications n
				INNER JOIN admingroups ag ON ag.admingroupsID = n.admingroupsID
		");

		self::$cachedNotificationData = [];

		$stmt = $db->getConnection()->prepare("SELECT adminID FROM admin_notifications WHERE notificationsID = ?");

		foreach($rows as $r) {
			
			$stmt->execute([$r['id']]);
			$adminIds = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

			if($r['attachment']) {
				$r['attachment'] = preg_split('~\s*,\s*~', $r['attachment']);
			}
			else {
				$r['attachment'] = null;
			}

			$r['notifies'] = $adminIds;	

			self::$cachedNotificationData[$r['alias']] = $r;
		}
	}

    /**
     * check whether a user will be notified by the notification
     *
     * @param User $user
     * @return bool
     */
	public function notifies(User $user): bool
    {
		return ($id = (int) $user->getAttribute('id')) && in_array($id, $this->notifies, true);
	}

    /**
     * adds a user to the list of notified users by inserting a record
     * in admin_notifications table; table is left unchanged if user is
     * already on the list
     * does no further checking of insertion result
     *
     * @param User $user
     * @return Notification
     * @throws ApplicationException
     */
	public function subscribe(User $user): Notification
    {
		if(($id = (int) $user->getAttribute('id')) && !in_array($id, $this->notifies, true)) {
			
			Application::getInstance()->getVxPDO()->insertRecord('admin_notifications', ['adminID' => $id, 'notificationsID' => $this->id]);
			$this->notifies[] = $id;

		}
		
		return $this;
	}

    /**
     * adds a user to the list of notified users by inserting a record
     * in admin_notifications table; table is left unchanged if user is
     * already on the list
     * does no further checking of insertion result
     *
     * @param User $user
     * @return Notification
     * @throws ApplicationException
     */
	public function unSubscribe(User $user): Notification
    {
		if(($id = (int) $user->getAttribute('id')) && in_array($id, $this->notifies, true)) {
				
			Application::getInstance()->getVxPDO()->deleteRecord('admin_notifications', ['adminID' => $id, 'notificationsID' => $this->id]);
			array_splice($this->notifies, array_search($id, $this->notifies, true));
	
		}
	
		return $this;
	}
	
	/**
	 * fill placeholders in notification message
	 * returns message
	 * 
	 * @param array $fieldValues
	 * @return string
	 */
	public function fillMessage(array $fieldValues = []): string
    {
		$txt = $this->message;

		if(empty($txt)) {
			return '';
		}

		foreach ($fieldValues as $key => $val) {
			$txt = preg_replace('/{\\s*' . preg_quote($key, '/') . '\\s*}/i', $val, $txt);
		}
		return $txt;
	}
}
