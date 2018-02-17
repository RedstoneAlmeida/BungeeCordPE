<?php


namespace BungeePM\network;

error_reporting(E_ALL|E_STRICT);

use BungeePM\network\protocol\{
    DataPacket, PacketInfo
};

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

    public function readPackets(){
       $rec = socket_recvfrom($this->socketResource,$buffer, 65535, 0, $address, $port);
       if($rec){
           switch(ord($buffer{0})){
               case PacketInfo::PACKET_PING;
               $data = [];
               // :/
               $pk = new DataPacket($data);
               break;
           }
           $packet = new DataPacket($buffer);
           $packet->decode();
           if(!is_array($packet->getData())){
               return;
           }
           $type = $packet->getData()['packetId'];
           $packet->setType($type);
           @$data = $packet->getData();
           if(empty($data['serverId'])){
               Logger::error("Server ID is not specified in packet data!");
               return;
           }
           switch($type){
               case PacketInfo::MESSAGE_SEND_PACKET;
               Logger::log("[Message from " . $packet->getData()['serverId'] . "] " . $packet->getData()['message']);
               break;
               case PacketInfo::SERVER_LOGIN_PACKET;
               $this->addClient($data);
               break;
               case PacketInfo::SERVER_DISCONNECT_PACKET;
               break;
               case PacketInfo::SERVER_ADD_REQUEST_PACKET;
               $this->addServerSession($data);
               break;
           }
       }

    }

    public function convertDataToPacket(){
        //TODO: Move back from readPackets()
    }

}