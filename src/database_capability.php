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
	public $RoomType;
	public $CheckIn;
	public $CheckOut;
	public $GuestCount;
	public $QuotedPrice;
	public $CreatedAt;
	public $SpecialRequests;
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
			"SELECT Id, Email, FirstName, LastName, RoleId FROM `User` WHERE `User`.Id = :id;"
			);

		$stmt->execute([':id' => $id]);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
		$result = $stmt->fetch();

		return $result;
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
	 * Returns the userId of the created User.
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
}

?>
