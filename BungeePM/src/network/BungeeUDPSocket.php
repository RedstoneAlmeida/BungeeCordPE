<?php


namespace BungeePM\network;

error_reporting(E_ALL|E_STRICT);

use BungeePM\network\protocol\{
    DataPacket, PacketInfo
};

use BungeePM\utils\Color;
use BungeePM\utils\Logger;

class BungeeUDPSocket
{

    /**
     * @var array $data
     */
    private $data = [];

    //TODO: Client.php class
    private $clients = [];

    /**
     * @var array $socketClients
     */
    private $socketClients = [];

    /**
     * @var ServerSession[] $servers
     */
    private $servers = [];

    /**
     * @var resource $socketResource
     */
    private $socketResource;

    /**
     * BungeeUDPSocket constructor.
     * @param string $host
     * @param string $port
     * @param $password
     */
    public function __construct(string $host, string $port,$password)
    {
        if(strlen(strval($password)) < 8){
            Logger::error("Password is too short! Use at least 8 characters.");
        }
        $this->data['password'] = $password;
        if(!filter_var($host, FILTER_VALIDATE_IP)){
            Logger::error("IP is not valid!");
        }
        $this->data['host'] = $host;
        $this->data['port'] = $port;
        $this->socketResource = socket_create(AF_INET, SOCK_STREAM, SOL_UDP);
        if(!$this->socketResource){
            Logger::error("Failed to CREATE socket: " . socket_strerror(socket_last_error($this->socketResource)));
            return;
        }
        if(@socket_bind($this->socketResource, $host, $port)){
            socket_setopt($this->socketResource,SOL_SOCKET,  SO_REUSEADDR, 0);
            socket_listen($this->socketResource, 5);
            socket_set_nonblock($this->socketResource);
        }else{
            Logger::error("Failed to bind socket on that port! PERHAPS SERVER IS ALREADY USING THIS PORT?");
            return;
        }
        $this->data['total_players'] = 0;
        $this->data['max_players'] = 100;
        $this->data['last_tick_elapsed'] = time();
        Logger::logActionFinish("BungeeCordPE started on " . $host . ":" . $port);
    }

    /**
     * @param string $writeHost
     * @param int $writePort
     * @param DataPacket $packet
     */
    public function writePacket(string $writeHost, int $writePort, DataPacket $packet){
        if(!$packet->isEncoded()){
            try{
                $packet->encode();
            }catch(\Exception $e){
                Logger::error("Failed to encode decoded||unknown packet!");
            }
        }
        $data = serialize($packet->getData());
        socket_write($this->socketResource, $data, strlen($data));
    }

    /**
     * @param array $connectData
     */
    public function addClient(array $connectData){
        Logger::log("Client {$connectData['clientName']}  {$connectData['clientHost']}:{$connectData['clientPort']} has connected to server ");
        $this->clients[$connectData['clientName']] = new Client($connectData['clientName'], $connectData['clientHost'], $connectData['clientPort']);
    }

    /**
     * @param array $serverData
     * @return bool
     */
    public function addServerSession(array $serverData) : bool{
        if(!strcasecmp($serverData['password'], $this->data['password'])){
            Logger::error("Password is invalid!");
            return false;
        }
        $this->servers[$serverData['serverId']] = new ServerSession($serverData);
        Logger::logActionFinish("Added server " . $serverData['serverId'] . " to server queue.");
        return true;
    }

    /**
     * @return ServerSession[]
     */
    public function getServers() : array{
        return $this->servers;
    }

    /**
     * @return mixed
     */
    public function listen(){
       socket_recvfrom($this->socketResource,$buffer, 65535, MSG_WAITALL, $address, $port);
       //TODO: Compare address with address of other servers/clients.
       return $buffer;
    }

    /**
     * @param string $data
     */
    public function write(string $data) : void{
        socket_write($this->socketResource, $data);
    }

    public function readMaxBytes(){

    }

    public function acceptConnection(){
        if (($sock = socket_accept($this->socketResource)) !== false) {
            $this->socketClients[count($this->socketClients)+1] = $sock;
            $message = Color::GREEN . "Logged to central server";
            socket_write($sock, $message, strlen($message));
        }
    }

    /**
     * Closes the connection
     */
    public function close(){
        return socket_close($this->socketResource);
    }

}