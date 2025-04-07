<?php
require realpath(__DIR__ . '/../../vendor/autoload.php');


use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use React\EventLoop\Loop;
use React\Socket\SocketServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\IoServer;

class VideoCallServer implements MessageComponentInterface
{
    protected $clients = [];
    protected $users = [];
    protected $callWindows = [];
    protected $userConnections = [];
    protected $role = null;
    protected $AdminMapMonitoring = [];
    protected $mapMonitoring = [];
    protected $adminConnections = [];
    protected $ongoingCalls = [];
    protected $reportSubmitted = [];
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "🔗 New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $db_host = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "bfp-taguig-db";

        $conn_db = new mysqli($db_host, $db_user, $db_pass, $db_name);
        $data = json_decode($msg, true);
        if (!$data || !isset($data['type'])) return;

        switch ($data['type']) {
            case 'newIncidentReport':
                $userId = htmlspecialchars(strip_tags($data['userId']));
                $incident_id = htmlspecialchars(strip_tags($data['incident_id']));
                $reporter_name = htmlspecialchars(strip_tags($data['name']));
                $contact_number = htmlspecialchars(strip_tags($data['contact_number']));
                $location = htmlspecialchars(strip_tags($data['location']));
                $message = htmlspecialchars(strip_tags($data['message']));
                $report_status = htmlspecialchars(strip_tags($data['report_status']));
                $submitted_at = htmlspecialchars(strip_tags($data['submitted_at']));
                $testgps = $data['gps_location'];
                echo "GPS Location $testgps.\n";
                // Handle GPS Location (sanitize it)
                $gpsLocation = isset($data['gps_location']) ? htmlspecialchars(strip_tags($data['gps_location'])) : "Not Available";
                if (isset($this->reportSubmitted[$userId])) {
                    unset($this->reportSubmitted[$userId]);
                }
                echo "GPS Location $gpsLocation.\n";
                $this->reportSubmitted[$userId] = $report_status;
                foreach ($this->adminConnections as $adminData) {
                    if (in_array($adminData['position'], ["Command Officer Head", "Command Officer Staff"])) {
                        // Send to the admin positions "Command Officer Head" or "Command Officer Staff"
                        $adminData['connection']->send(json_encode([
                            'type' => 'newIncidentReport',
                            'userId' => $userId,
                            'incident_id' => $incident_id,
                            'reporter_name' => $reporter_name,
                            'contact_number' => $contact_number,
                            'location' => $location,
                            'gpsLocation' => $gpsLocation,
                            'message' => $message,
                            'report_status' => $report_status,
                            'submitted_at' => $submitted_at,
                            'status' => 'Connected'
                        ]));
                    }
                }
                echo "📞 Sent call request to admin! user: $userId\n";
                break;
            case 'reportUpdate':
                $userId = $data['userId'];
                $update = $data['update'];

                if ($update === 'processing') {
                    foreach ($this->adminConnections as $adminUserId => $adminData) {
                        // Send to HigherAdmin first
                        if (isset($adminData['position']) && $adminData['position'] === 'Command Officer Head' || $adminData['position'] === 'Command Officer Staff') {
                            $conn = $adminData['connection'];  // Get the connection for this admin
                            $conn->send(json_encode($data));   // Send the data to the admin with MapMonitoring position
                        }
                    }
                } else {
                    foreach ($this->adminConnections as $adminUserId => $adminData) {
                        // Send to HigherAdmin first
                        if (isset($adminData['position']) && $adminData['position'] === 'Command Officer Head' || $adminData['position'] === 'Command Officer Staff') {
                            $conn = $adminData['connection'];  // Get the connection for this admin
                            $conn->send(json_encode($data));   // Send the data to the admin with MapMonitoring position
                        }
                    }
                    if (isset($this->userConnections[$userId])) {
                        echo "Report of $userId updated to $update\n";
                        $this->userConnections[$userId]->send(json_encode([
                            'type' => 'reportUpdate',
                            'userId' => $userId,
                            'update' => $update
                        ]));
                        if (isset($this->reportSubmitted[$userId])) {
                            unset($this->reportSubmitted[$userId]);
                        }
                    } else {
                        if (isset($this->reportSubmitted[$userId]) && $this->reportSubmitted[$userId] === "pending") {
                            $this->reportSubmitted[$userId] = $update;
                        }
                    }
                }
                break;
            case 'requestCall':
                $userId = $data['userId'];
                if (isset($this->userConnections[$userId])) { // ✅ Use the correct array
                    $targetConnection = $this->userConnections[$userId];
                    $targetConnection->send(json_encode([
                        'type' => 'startCall',
                        'userId' => $userId
                    ]));
                    echo "📞 Sent Call Request to User: $userId\n";
                } else {
                    echo "❌ User $userId is not connected.\n";
                }
                break;
            case 'adminConnection':
                $userId = $data['userId'];
                $adminPosition = $data['admin_position'];

                if (isset($this->adminConnections[$userId])) {
                    $existingConn = $this->adminConnections[$userId]['connection'];

                    // If the new connection is different, close the old one and remove it
                    if ($existingConn !== $from) {
                        echo "🔄 Replacing old connection for Admin $userId\n";
                        $existingConn->close(); // Close old connection
                    }
                }
                $this->adminConnections[$userId] = [
                    'connection' => $from,
                    'userId' => $data['userId'],
                    'position' => $data['admin_position'],
                    'branch' => $data['admin_branch'],
                    'session_id' => $data['session_id']
                ];

                if (isset($this->adminConnections[$userId]) && in_array($adminPosition, ["Command Officer Head", "Command Officer Staff"])) {
                    $adminConn = $this->adminConnections[$userId]['connection'];
                    foreach ($this->userConnections as $connectedUserId => $conn) {
                        $adminConn->send(json_encode([
                            'type' => 'userconnections',
                            'userId' => $connectedUserId,
                            'status' => 'Connected'
                        ]));
                    }
                }

                break;
            case 'AdminMapMonitoring':
                $userId = $data['userId'];
                $position = $data['position'];
                echo "📡 Recieved Connection From $userId, $position\n";

                if (!isset($this->AdminMapMonitoring[$userId][$position])) {
                    $this->AdminMapMonitoring[$userId][$position] = [];
                }
                $this->AdminMapMonitoring[$userId][$position][] = $from;

                break;
            case 'MapMonitoring':
                $lat = $data['lat'];
                $lon = $data['lon'];
                $userId = $data['userId'];
                $admin_position = $data['admin_position'];
                $admin_branch = $data['admin_branch'];
                echo "📍 Received Location Update from $admin_position $userId from $admin_branch coords: [$lat, $lon]\n";


                if (isset($data['reason']) && $data['reason'] === 'updateAdminLoc') {
                    foreach ($this->adminConnections as $adminUserId => $adminData) {
                        // Send to HigherAdmin first
                        if (isset($adminData['position']) && $adminData['position'] === 'MapMonitoring' && $adminData['userId'] === $data['toUser']) {
                            $conn = $adminData['connection'];  // Get the connection for this admin
                            $conn->send(json_encode($data));   // Send the data to the admin with MapMonitoring position
                        }
                    }
                } else {
                    foreach ($this->adminConnections as $adminUserId => $adminData) {
                        // Send to HigherAdmin first

                        if (isset($adminData['position']) && $adminData['position'] === 'MapMonitoring') {
                            $conn = $adminData['connection'];  // Get the connection for this admin
                            $conn->send(json_encode($data));   // Send the data to the admin with MapMonitoring position
                        }
                    }
                }
                break;
            case 'UpdateConnectedGps':
                $sentUserId = $data['userId'];
                echo "sent userid from admin $sentUserId\n";

                if (isset($data['admin_position']) && $data['admin_position'] === 'Command Officer Head' || $data['admin_position'] === 'Command Officer Staff') {
                    echo "sent userid from admin $sentUserId\n";
                    foreach ($this->adminConnections as $userId => $adminData) {
                        // Send to admins with position "Fire Officer"
                        if (isset($adminData['position']) && $adminData['position'] === 'Fire Officer') {
                            $conn = $adminData['connection'];  // Get the connection for this admin
                            $conn->send(json_encode([
                                'type' => 'updateAdminLoc',
                                'userId' => $sentUserId
                            ]));  // Send the update to the Fire Officer

                        }
                    }
                }
                // foreach ($this->adminConnections as $userId => $positions) {
                //     // Send to HigherAdmin first
                //     if (isset($positions['LowerAdmin'])) {
                //         foreach ($positions['LowerAdmin'] as $conn) {
                //             $conn->send(json_encode(['type' => 'updateAdminLoc',]));
                //         }
                //     }
                // }
                break;

            case 'callWindowConnected':
                $userId = $data['userId'];
                $this->callWindows[$userId] = $from;
                echo "✅ Call Window Registered for User $userId\n";
                break;

            case 'ongoingCalls':
                $userId = $data['userId'];
                $status = $data['status'];
                if (!isset($this->ongoingCalls[$userId])) {
                    $this->ongoingCalls[$userId] = $status;
                }

                break;
            case 'offer':
                $userId = $data['userId'];
                echo "📩 Received WebRTC Offer from User $userId\n";

                if (isset($this->callWindows[$userId])) {
                    echo "📤 Forwarding WebRTC Offer to Call Window for User $userId\n";
                    $this->callWindows[$userId]->send(json_encode([
                        'type' => 'offer',
                        'offer' => $data['offer'],
                        'userId' => $userId
                    ]));
                }
                if (isset($this->userConnections[$userId])) {
                    echo "📤 Forwarding WebRTC Offer to Call Window for User $userId\n";
                    $this->userConnections[$userId]->send(json_encode([
                        'type' => 'offer',
                        'offer' => $data['offer'],
                        'userId' => $userId
                    ]));
                }
                break;
            case 'answer':
                $userId = $data['userId'];
                echo "📩 Received WebRTC Answer from Call Window for User $userId\n";
                if ($this->userConnections[$userId]) {
                    echo "📤 Forwarding WebRTC Answer to User $userId\n";
                    $this->userConnections[$userId]->send(json_encode([
                        'type' => 'answer',
                        'answer' => $data['answer'],
                        'userId' => $userId
                    ]));
                }
                break;
            case 'candidate':
                $userId = $data['userId'];
                $candidate = $data['candidate'];

                echo "📩 Received ICE Candidate from $userId\n";
                if (isset($this->callWindows[$userId])) {
                    echo "📤 Forwarding ICE Candidate to Call Window for User $userId\n";
                    $this->callWindows[$userId]->send(json_encode([
                        'type' => 'candidate',
                        'candidate' => $candidate,
                        'userId' => $userId
                    ]));
                }
                break;
            case 'callEndedByUser':
                $userId = $data['userId'];
                echo "✅ Call ended by user $userId\n";
                unset($this->ongoingCalls[$userId]);
                if (isset($this->callWindows[$userId])) {
                    $this->callWindows[$userId]->send(json_encode([
                        'type' => 'callEndedByUser', // call ended by user
                        'userId' => $userId
                    ]));
                }

                break;
            case 'callEndedByAdmin':
                $userId = $data['userId'];
                echo "✅ Call ended by admin $userId\n";
                unset($this->ongoingCalls[$userId]);
                if (isset($this->userConnections[$userId])) {
                    $this->userConnections[$userId]->send(json_encode([
                        'type' => 'callEndedByAdmin',
                        'userId' => $userId
                    ]));
                }
                break;
            case 'userConnectionMonitoring':
                $userId = $data['userId'];
                if (isset($this->userConnections[$userId]) && $this->userConnections[$userId] !== $from) {
                    echo "🔄 User $userId reconnected, closing old connection.\n";
                    $oldConn = $this->userConnections[$userId];
                    unset($this->userConnections[$userId]);
                    $oldConn->close(); // ✅ Close old connection
                }
                $this->userConnections[$userId] = $from;
                if (isset($this->reportSubmitted[$userId])) {
                    $status = $this->reportSubmitted[$userId];
                    if ($status === "Declined") {
                        $this->userConnections[$userId]->send(json_encode([
                            'type' => 'reportUpdate',
                            'userId' => $userId,
                            'update' => $status
                        ]));
                        unset($this->reportSubmitted[$userId]);
                    } else {
                        $this->userConnections[$userId]->send(json_encode([
                            'type' => 'reportUpdate',
                            'userId' => $userId,
                            'update' => $status
                        ]));
                        unset($this->reportSubmitted[$userId]);
                        echo "📡 Sending userConnected to all admins for $userId\n";
                        foreach ($this->adminConnections as $adminData) {
                            if (in_array($adminData['position'], ["Command Officer Head", "Command Officer Staff"])) {
                                $adminData['connection']->send(json_encode([
                                    'type' => 'userConnected',
                                    'userId' => $userId
                                ]));
                            }
                        }
                        $stmt = $conn_db->prepare("UPDATE incident_report SET connection_status = 'Connected' WHERE connection_id = ?");
                        $stmt->bind_param("s", $userId);
                        if ($stmt->execute()) {
                            echo "✅ Database Updated: User $userId marked as Disconnected\n";
                        } else {
                            echo "❌ Database Update Failed: " . $stmt->error . "\n";
                        }
                        $stmt->close();
                    }
                }
                break;

                // case 'MapGpsExchange':
                error_log("MapGpsExchange case triggered"); // Log start of execution

                if (!isset($data['incidentId'], $data['userId'], $data['incidentLocation'], $data['infoMessage'], $data['lat'], $data['lon'], $data['position'])) {
                    error_log("Missing required fields.");
                    break;
                }

                // 🔹 Database Connection
                $mysqli = new mysqli('localhost', 'root', '', 'bfp-taguig-db');
                if ($mysqli->connect_error) {
                    error_log("Database connection failed: " . $mysqli->connect_error);
                    break;
                }
                error_log("Database connected successfully");

                if ($data['position'] === "LowerAdmin") {
                    $incident_id = htmlspecialchars($data['incidentId'], ENT_QUOTES, 'UTF-8');
                    $currentUserId = htmlspecialchars($data['userId'], ENT_QUOTES, 'UTF-8');
                    $incident_location = htmlspecialchars($data['incidentLocation'], ENT_QUOTES, 'UTF-8');
                    $info_message = htmlspecialchars($data['infoMessage'], ENT_QUOTES, 'UTF-8');
                    $latitude = floatval($data['lat']);
                    $longitude = floatval($data['lon']);
                    $status = "ongoing";

                    // 🔹 Prepare SQL Query
                    $stmt = $mysqli->prepare("INSERT INTO rescue_details 
                            (incident_id, sent_by, incident_location, info_message, status, latitude, longitude, submitted_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

                    if ($stmt) {
                        error_log("SQL statement prepared successfully.");
                        $stmt->bind_param("sssssdd", $incident_id, $currentUserId, $incident_location, $info_message, $status, $latitude, $longitude);

                        if ($stmt->execute()) {
                            error_log("Data inserted into database successfully.");

                            foreach ($this->AdminMapMonitoring as $userId => $positions) {
                                // Then send to LowerAdmin
                                if (isset($positions['LowerAdmin'])) {
                                    foreach ($positions['LowerAdmin'] as $conn) {
                                        $conn->send(json_encode($data));
                                    }
                                }
                                if (isset($positions['MapMonitoring'])) {
                                    foreach ($positions['MapMonitoring'] as $conn) {
                                        $conn->send(json_encode($data));
                                    }
                                }
                            }
                        } else {
                            error_log("Database insert failed: " . $stmt->error);
                        }
                        $stmt->close();
                    } else {
                        error_log("Failed to prepare SQL statement.");
                    }
                }
                // 🔹 Close Database Connection
                $mysqli->close();
                error_log("Database connection closed.");
                break;
        }
    }
    public function onClose(ConnectionInterface $conn)
    {
        echo "🔍 Checking connection closure...\n";

        $db_host = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "bfp-taguig-db";

        $conn_db = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn_db->connect_error) {
            echo "❌ Database Connection Failed: " . $conn_db->connect_error . "\n";
            return;
        }
        foreach ($this->callWindows as $userId => $client) {
            if ($client === $conn) {
                unset($this->callWindows[$userId]);
                echo "❌ Connection closed from admin call $userId\n";
                $elapsedTime = 0; // Track elapsed time
                $timer = null;
                if (!isset($this->ongoingCalls[$userId])) {
                    return;
                }
                if (isset($this->userConnections[$userId])) {
                    $this->userConnections[$userId]->send(json_encode([
                        "type" => "adminCallReconnecting",
                        'userId' => $userId
                    ]));
                }
                $timer = Loop::addPeriodicTimer(1, function () use ($userId, &$timer, &$elapsedTime) {
                    $elapsedTime++;
                    if (isset($this->callWindows[$userId])) {

                        echo "✅ User $userId reconnected!\n";
                        if (!isset($this->userConnections[$userId])) {
                            echo "⚠️ No active connection for user $userId!\n";
                            return;
                        }
                        $this->userConnections[$userId]->send(json_encode([
                            "type" => "callWindowReconnected",
                            'userId' => $userId
                        ]));
                        Loop::cancelTimer($timer); // Stop the timer
                        return;
                    }
                    echo "❌ Not yet connected ($elapsedTime sec) - User $userId\n";
                    if ($elapsedTime >= 10) { // Stop checking after 5 seconds
                        echo "⏳ Admin did not reconnect within 5 seconds. Closing.\n";
                        Loop::cancelTimer($timer);
                        unset($this->ongoingCalls[$userId]);
                        if (isset($this->userConnections[$userId])) {
                            $this->userConnections[$userId]->send(json_encode([
                                "type" => "adminCallDisconnected",
                                'userId' => $userId
                            ]));
                        }
                    }
                });
            }
        }
        foreach ($this->userConnections as $userId => $client) { // ✅ Handle user disconnection
            if ($client === $conn) {
                unset($this->userConnections[$userId]);
                echo "❌ User Disconnected: $userId\n";
                $elapsedTime = 0; // Track elapsed time
                $timer = null;
                if (!isset($this->ongoingCalls[$userId])) {
                    foreach ($this->adminConnections as $connectedAdmin => $adminData) { // 🔥 Loop through all admins
                        if (isset($adminData['position']) && in_array($adminData['position'], ["Command Officer Head", "Command Officer Staff"])) {
                            $adminData['connection']->send(json_encode([
                                'type' => 'userconnections',
                                'userId' => $userId,
                                'status' => 'Disconnected'
                            ]));
                        }
                    }

                    //✅ Update database: Set user status to "Disconnected"
                    $stmt = $conn_db->prepare("UPDATE incident_report SET connection_status = 'Disconnected' WHERE connection_id = ?");
                    $stmt->bind_param("s", $userId);
                    if ($stmt->execute()) {
                        echo "✅ Database Updated: User $userId marked as Disconnected\n";
                    } else {
                        echo "❌ Database Update Failed: " . $stmt->error . "\n";
                    }
                    $stmt->close();
                    return;
                }

                if (isset($this->userConnections[$userId])) {
                    $this->userConnections[$userId]->send(json_encode([
                        "type" => "userCallReconnecting",
                        'userId' => $userId
                    ]));
                }
                if (isset($this->callWindows[$userId])) {
                    $this->callWindows[$userId]->send(json_encode([
                        "type" => "userCallReconnecting",
                        'userId' => $userId
                    ]));
                }
                $timer = Loop::addPeriodicTimer(1, function () use ($userId, &$timer, &$elapsedTime) {
                    $elapsedTime++;
                    if (isset($this->userConnections[$userId])) {
                        echo "✅ User $userId reconnected!\n";
                        if (!isset($this->callWindows[$userId])) {
                            $this->userConnections[$userId]->send(json_encode([
                                "type" => "adminCallDisconnected",
                                'userId' => $userId
                            ]));
                            return;
                        }
                        $this->userConnections[$userId]->send(json_encode([
                            "type" => "userCallReconnected",
                            'userId' => $userId
                        ]));
                        $this->callWindows[$userId]->send(json_encode([
                            "type" => "userCallReconnected",
                            'userId' => $userId
                        ]));
                        Loop::cancelTimer($timer); // Stop the timer
                        return;
                    }
                    echo "❌ Not yet connected ($elapsedTime sec) - User $userId\n";
                    if ($elapsedTime >= 10) { // Stop checking after 5 seconds
                        echo "⏳ user did not reconnect within 10 seconds. Closing.\n";
                        Loop::cancelTimer($timer);
                        unset($this->ongoingCalls[$userId]);
                        if (isset($this->callWindows[$userId])) {
                            $this->callWindows[$userId]->send(json_encode([
                                "type" => "userCallDisconnected",
                                'userId' => $userId
                            ]));
                        }
                        foreach ($this->adminConnections as $connectedAdmin => $adminData) { // 🔥 Loop through all admins
                            if (isset($adminData['position']) && in_array($adminData['position'], ["Command Officer Head", "Command Officer Staff"])) {
                                $adminData['connection']->send(json_encode([
                                    'type' => 'userconnections',
                                    'userId' => $userId,
                                    'status' => 'Disconnected'
                                ]));
                            }
                        }

                        $db_host = "localhost";
                        $db_user = "root";
                        $db_pass = "";
                        $db_name = "bfp-taguig-db";

                        $conn_db = new mysqli($db_host, $db_user, $db_pass, $db_name);
                        //✅ Update database: Set user status to "Disconnected"
                        $stmt = $conn_db->prepare("UPDATE incident_report SET connection_status = 'Disconnected' WHERE connection_id = ?");
                        $stmt->bind_param("s", $userId);
                        if ($stmt->execute()) {
                            echo "✅ Database Updated: User $userId marked as Disconnected\n";
                        } else {
                            echo "❌ Database Update Failed: " . $stmt->error . "\n";
                        }
                        $stmt->close();
                    }
                });
            }
        }
        echo "✅ Detaching connection {$conn->resourceId}\n";
        $this->clients->detach($conn);
        echo "3❌ Connection closed\n";;
    }
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "❌ Error: {$e->getMessage()}\n";
        $conn->close();
    }
    // ✅ Function to check user activity and disconnect if inactive
    public function checkInactiveUsers()
    {
        $now = time();
    }
}

// ✅ Fix: Initialize `$loop` before `addPeriodicTimer`
$loop = Loop::get();

// ✅ Create the WebSocket server instance
$serverInstance = new VideoCallServer();

// ✅ Start a periodic timer to check inactive users
$loop->addPeriodicTimer(5, function () use ($serverInstance) {
    $serverInstance->checkInactiveUsers();
});

$context = [
    'tls' => [
        'local_cert'  => 'C:/xampp/apache/conf/ssl.crt/localhost.pem',
        'local_pk'    => 'C:/xampp/apache/conf/ssl.key/localhost-key.pem',
        'allow_self_signed' => true,
        'verify_peer' => false,
    ]
];

// $socket = new SocketServer(0.0.0.0:8081', [], $loop);
$socket = new SocketServer('tls://0.0.0.0:8081', $context, $loop);
$server = new IoServer(
    new HttpServer(
        new WsServer($serverInstance)
    ),
    $socket,
    $loop
);

echo "🟢 WebSocket Server started on port 8081\n";
$loop->run();