<?php declare(strict_types=1);
/*
 * CSD 460: Capstone in Software Development
 * Gold Team
 *  Isaac Ellingson
 *  Patrice Moracchini
 *  Cannon Rivera
 *  José Velázquez Sáenz
 *
 * Describes database capabilities - objects which represent authorization to interact with
 * a resource in a particular way.
 *
 * THIS FILE REQUIRES TWO .ini FILES WHICH ARE NOT INCLUDED IN THE GIT REPOSITORY!
 *
 * - user_MoffatBayRead.ini
 * - user_MoffatBayReadWrite.ini
 *
 * These files look like:
 *
 *     host = 'localhost'
 *     port = '3306'
 *     db = 'MoffatBay'
 *     user = 'foo'
 *     pass = 'bar'
 *
 * And they must match your read and read/write users. To the team and client, I will be
 * separately shipping a .sql script to create test users, plus .ini files for these users.
 *
 * Thanks! -- Isaac
 */

/**
 * An entry in the User table of the database
 */
class User {
	public $Id;
	public $Email;
	public $FirstName;
	public $LastName;
	public $PhoneNumber;
	// PasswordHash omitted! We generally don't want to move this value around.
	public $CreatedAt;
	public $RoleId;
}

/**
 * An entry in the Reservation table of the database
 */
class Reservation {
	public $Id;
	public $UserId;
	public $ConfirmationNumber;
	public $RoomTypeId;
	public $CheckIn;
	public $CheckOut;
	public $GuestCount;
	public $QuotedPrice;
	public $CreatedAt;
	public $SpecialRequests;
}

class RoomType {
	public $Id = -1;
	public $Name;
	public $Description = NULL;
	public $MaxGuests = 1;
	public $NightlyRate;
	public $Active = TRUE;
}

/**
 * An entry in the ContactMessage table of the database
 */
class ContactMessage {
	public $Id;
	public $UserId;
	public $FullName;
	public $Email;
	public $Phone;
	public $CreatedAt;
	public $Status;
	public $Subject;
	public $Message;

	/**
	 * Static factory method. Creates a ContactMessage with user details filled
	 * in automatically from a User object.
	 *
	 * @param User $user
	 *   The user to fill in details from
	 * @param string $subject
	 *   The subject line for this ContactMessage
	 * @param string $message
	 *   The message body text for this ContactMessage
	 *
	 * @return ContactMessage
	 *   A ContactMessage with everything filled in.
	 */
	public static function of(User $user, string $subject, string $message): ContactMessage {
		$result = new ContactMessage();
		$result->UserId = $user->Id;
		$result->FullName = $user->FirstName . ' ' . $user->LastName;
		$result->Email = $user->Email;
		$result->Phone = $user->PhoneNumber;
		$result->Subject = $subject;
		$result->Message = $message;

		return $result;
	}
}

/**
 * A "read-only" database capability. All methods are going to be
 * accessor methods like "get" something or "is" something.
 */
class ReadCapability {
	protected $connection;

	function __construct() {
		$settings = parse_ini_file('user_MoffatBayRead.ini');
		$this->connection = new PDO(
			'mysql:dbname=' .
			$settings['db'] .
			';host=' . $settings['host'] .
			';port=' . $settings['port'],

			$settings['user'],

			$settings['pass']
		);
	}

	function __destruct() {
		unset($this->connection);
	}

	/**
	 * Gets a User by their Id.
	 *
	 * @param int $id
	 *   The Id of the User to retrieve.
	 *
	 * @return User|false
	 *   If the User exists, returns it. If none was found, returns false.
	 */
	function getUser(int $id): User|false {
		$stmt = $this->connection->prepare(
			"SELECT Id, Email, FirstName, LastName, PhoneNumber, RoleId FROM `User` WHERE `User`.Id = :id;"
			);

		$stmt->execute([':id' => $id]);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
		$result = $stmt->fetch();

		return $result;
	}

	function getLoggedInUser(): User|false {
		if (!isset($_SESSION['user_id'])) return FALSE;

		return $this->getUser((int) $_SESSION['user_id']);
	}

	function getPermissions(User|int $user): array {
		$stmt = $this->connection->prepare(
			<<<SQL

			SELECT Permission.Name
			FROM `User`
			LEFT JOIN Role
				ON `User`.RoleId = Role.Id
			LEFT JOIN RolePermission
				ON RolePermission.RoleId = Role.Id
			LEFT JOIN Permission
				ON RolePermission.PermissionId = Permission.Id

			WHERE `User`.Id = :id;

			SQL
			);
		$args = [':id' => ($user instanceof User) ? $user->Id : $user];
		$stmt->execute($args);
		$stmt->setFetchMode(PDO::FETCH_COLUMN, 0);
		$result = $stmt->fetchAll();
		return ($result===FALSE) ? [] : $result;
	}

	function authenticateUser($email, #[SensitiveParameter] string $password) : User|false {
		$stmt = $this->connection->prepare("SELECT * FROM `User` WHERE Email = :email;");
		$stmt->execute([':email' => $email]);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$result = $stmt->fetch();

		if ($result === FALSE) return FALSE;

		if (password_verify($password, $result['PasswordHash'])) {
			// TODO: Check password_needs_rehash to see if we need to reset the user's password?

			$user = new User();
			$user->Id = $result['Id'];
			$user->Email = $result['Email'];
			$user->FirstName = $result['FirstName'];
			$user->LastName = $result['LastName'];
			$user->RoleId = $result['RoleId'];

			return $user;

		} else {
			return FALSE;
		}
	}

	/**
	 * Gets a Reservation by its Id.
	 *
	 * @param int $id
	 *   The Id of the Reservation to retrieve.
	 *
	 * @return Reservation|false
	 *   If the Reservation exists, returns it. If none was found, returns false.
	 */
	function getReservation(int $id): Reservation|false {
		$stmt = $this->connection->prepare(
			"SELECT * FROM Reservation WHERE Reservation.Id = :id;"
			);

		$stmt->execute([':id' => $id]);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Reservation');
		$result = $stmt->fetch();

		return $result;
	}

	/**
	 * Gets all Reservations for the user indicated.
	 *
	 * @param User|int $user
	 *   The User to get Reservations for
	 *
	 * @return Reservation[]
	 *   If the User has Reservations, returns an array of Reservation objects. If no
	 *   Reservations exist for this user, returns an empty array.
	 */
	function getReservations(User|int $user): array {
		$stmt = $this->connection->prepare(
			"SELECT * FROM Reservation WHERE Reservation.UserId = :id;"
			);

		$args = [':id' => ($user instanceof User) ? $user->Id : $user];
		$stmt->execute($args);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Reservation');
		$result = $stmt->fetchAll();

		return is_array($result) ? $result : [];
	}

	/**
	 * Gets a RoomType by its Id.
	 *
	 * @param int Id
	 *   The Id of the RoomType to retrieve
	 *
	 * @return RoomType|false
	 *   If the RoomType can be found, returns it as an object. If none was found,
	 *   returns false.
	 */
	function getRoomType(int $id): RoomType|false {
		$stmt = $this->connection->prepare(
			"SELECT * FROM RoomType WHERE RoomType.Id = :id;"
			);

		$stmt->execute([':id' => $id]);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'RoomType');
		$result = $stmt->fetch();

		return $result;
	}

	/**
	 * Gets all RoomTypes
	 */
	function getRoomTypes(bool $includeInactive = FALSE): array {
		if ($includeInactive) {
			$stmt = $this->connection->prepare("SELECT * FROM RoomType;");
		} else {
			$stmt = $this->connection->prepare(
				"SELECT * FROM RoomType WHERE Active;"
				);
		}
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'RoomType');
		$result = $stmt->fetchAll();

		return is_array($result) ? $result : [];
	}


	/**
	 * Gets a ContactMessage by its Id.
	 *
	 * @param int $id
	 *   The Id of the ContactMessage to retrieve.
	 *
	 * @return ContactMessage|false
	 *   If the ContactMessage exists, returns it. If none was found, returns false.
	 */
	function getContactMessage(int $id): ContactMessage|false {
		$stmt = $this->connection->prepare(
			"SELECT * FROM ContactMessage WHERE ContactMessage.Id = :id;"
			);

		$stmt->execute([':id' => $id]);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'ContactMessage');
		$result = $stmt->fetch();

		return $result;
	}

	/**
	 * Gets all ContactMessages.
	 *
	 * @param bool $includeResolved
	 *   When TRUE, includes ContactMessages that have already been marked as resolved.
	 *   Defaults to FALSE.
	 *
	 * @return ContactMessage[]
	 *   Returns an array of ContactMessage objects. If there are no messages, returns
	 *   an empty array.
	 */
	function getContactMessages(bool $includeResolved = FALSE): array {
		if ($includeResolved) {
			$stmt = $this->connection->prepare("SELECT * FROM ContactMessage;");
		} else {
			$stmt = $this->connection->prepare(
				"SELECT * FROM ContactMessage WHERE Status != 'Resolved';"
				);
		}
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'ContactMessage');
		$result = $stmt->fetchAll();

		return is_array($result) ? $result : [];

	}



	// TODO: Remove this later!
	function getRawConnection(): PDO {
		return $this->connection;
	}
}

/**
 * A databse capability that can make changes to the database if acquired.
 * This includes all the accessor methods from ReadCapability, plus ones for editing data.
 * Additionally, the backing connection operates with write priveleges.
 */
class ReadWriteCapability extends ReadCapability {
	function __construct() {
		$settings = parse_ini_file('user_MoffatBayReadWrite.ini');
		$this->connection = new PDO(
			'mysql:dbname=' .
			$settings['db'] .
			';host=' . $settings['host'] .
			';port=' . $settings['port'],

			$settings['user'],

			$settings['pass']
		);
	}

	/**
	 * Creates the supplied User in the database. Does not do any password validation.
	 * Ignores the "Id" and "CreatedAt" field of the provided User. These will be automatically
	 * determined during the insert.
	 *
	 * @param User   $user     The User to create
	 * @param string $password The password to set for the new user
	 *
	 * @return int
	 *   The userId of the created User. If there was a problem creating the user, FALSE is returned.
	 */
	function createUser(User $user, #[SensitiveParameter] string $password): int|false {
		$passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
		try {
			$stmt = $this->connection->prepare(
				<<<SQL
				INSERT INTO `User`(Email, FirstName, LastName, PhoneNumber, PasswordHash, RoleId)
				VALUES(:email, :firstName, :lastName, :phoneNumber, :passwordHash, :roleId);
				SQL
				);
			$args = [
				':email' => $user->Email,
				':firstName' => $user->FirstName,
				':lastName' => $user->LastName,
				':phoneNumber' => $user->PhoneNumber,
				':passwordHash' => $passwordHash,
				':roleId' => $user->RoleId
				];

			$result = $stmt->execute($args);

			return ($result === FALSE) ? FALSE : (int) $this->connection->lastInsertId();
		} catch (Exception $e) {
			return FALSE;
		}
	}

	/**
	 * Creates a new reservation.
	 * Ignores the "Id" and "CreatedAt" fields of the provided Reservation. These will be
	 * automatically determined during the insert.
	 *
	 * @param Reservation $reservation The Reservation to create.
	 *
	 * @return int|false
	 *   If the reservation was successfully created, returns its new Id. If a problem occurred,
	 *   FALSE is returned.
	 */
	function createReservation(Reservation $reservation): int|false {
		try {
			$stmt = $this->connection->prepare(
				<<<SQL
				INSERT INTO Reservation(
					UserId, ConfirmationNumber, RoomTypeId, CheckIn, CheckOut, GuestCount,
					QuotedPrice, SpecialRequests
				)
				VALUES(
					:userId, :confirmationNumber, :roomTypeId, :checkIn, :checkOut, :guestCount,
					:quotedPrice, :specialRequests
				);
				SQL
				);
			$args = [
				':userId' => $reservation->UserId,
				':confirmationNumber' => $reservation->ConfirmationNumber,
				':roomTypeId' => $reservation->RoomTypeId,
				':checkIn' => $reservation->CheckIn,
				':checkOut' => $reservation->CheckOut,
				':guestCount' => $reservation->GuestCount,
				':quotedPrice' => $reservation->QuotedPrice,
				':specialRequests' => $reservation->SpecialRequests
				];

			$result = $stmt->execute($args);

			return ($result === FALSE) ? FALSE : (int) $this->connection->lastInsertId();
		} catch (Exception $e) {
			return FALSE;
		}
	}

	function createContactMessage(ContactMessage $message): int|FALSE {
		try {
			$stmt = $this->connection->prepare(
				<<<SQL
				INSERT INTO ContactMessage(
					UserId, FullName, Email, Phone, Subject, Message
				)
				VALUES(
					:userId, :fullName, :email, :phone, :subject, :message
				);
				SQL
				);
			$args = [
				':userId' => $message->UserId,
				':fullName' => $message->FullName,
				':email' => $message->Email,
				':phone' => $message->Phone,
				':subject' => $message->Subject,
				':message' => $message->Message
				];

			$result = $stmt->execute($args);

			return ($result === FALSE) ? FALSE : (int) $this->connection->lastInsertId();
		} catch (Exception $e) {
			print_r($e);
			return FALSE;
		}

	}
}

?>
